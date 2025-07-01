<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'session'));
    }

    public function index()
    {
        $this->load->view('halamanRegister'); 
    }

    private function buatpwd()
    {
        $kata = "ABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
        return substr(str_shuffle($kata), 0, 6);
    }

    public function register()
    {
        // 1. ATURAN VALIDASI
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('namaLengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('nomerTelepon', 'Nomor Telepon', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        // Alamat hanya wajib jika jabatan adalah PJ
        if ($this->input->post('jabatan') === 'PJ') {
            $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        }

        // 2. JALANKAN VALIDASI
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('halamanRegister');
        } else {
            // Ambil data jabatan dan NIK dari form
            $jabatan = $this->input->post('jabatan');
            $nik = $this->input->post('nik');

            // 3. LOGIKA UTAMA BERDASARKAN JABATAN
            if ($jabatan == 'KALING') {
                // Cek duplikasi NIK di tbkaling
                $check = $this->db->get_where('tbkaling', ['NIK' => $nik])->row();
                if ($check) {
                    $this->session->set_flashdata('pesan', 'NIK sudah terdaftar sebagai Kepala Lingkungan.');
                    $this->session->set_flashdata('alert_type', 'danger');
                    redirect('register');
                    return;
                }

                // Siapkan data untuk tbkaling
                $data_kaling = array(
                    'NIK'            => $nik,
                    'namaLengkap'    => $this->input->post('namaLengkap'),
                    'email'          => $this->input->post('email'),
                    'telp'           => $this->input->post('nomerTelepon'),
                    'jenisAkun'      => $jabatan,
                    'password'       => $this->buatpwd(),
                    'statusAktivasi' => 'Belum'
                );
                $this->db->insert('tbkaling', $data_kaling);

            } elseif ($jabatan == 'PJ') {
                // Cek duplikasi NIK di tbpj
                $check = $this->db->get_where('tbpj', ['NIK' => $nik])->row();
                if ($check) {
                    $this->session->set_flashdata('pesan', 'NIK sudah terdaftar sebagai Penanggung Jawab.');
                    $this->session->set_flashdata('alert_type', 'danger');
                    redirect('register');
                    return;
                }

                // Siapkan data untuk tbpj (sesuai kolom yang ada di form)
                $data_pj = array(
                    'NIK'            => $nik,
                    'namaLengkap'    => $this->input->post('namaLengkap'),
                    'alamat'         => $this->input->post('alamat'),
                    'telp'           => $this->input->post('nomerTelepon'),
                    'email'          => $this->input->post('email'),
                    'jenisAkun'      => $jabatan,
                    'password'       => $this->buatpwd(),
                    'statusAktivasi' => 'Belum'
                    // Kolom lain seperti no_kk, tempat_lahir, dll. akan otomatis NULL
                    // karena tidak kita masukkan ke dalam array $data_pj
                );
                $this->db->insert('tbpj', $data_pj);
            }

            // 4. BERI FEEDBACK DAN REDIRECT
            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('pesan', 'Registrasi berhasil! Akun Anda akan segera diverifikasi oleh Admin.');
                $this->session->set_flashdata('alert_type', 'success');
                redirect('Halaman'); // Redirect ke halaman login
            } else {
                // Kondisi ini jarang terjadi jika validasi sudah lolos,
                // tapi baik untuk penanganan error.
                $this->session->set_flashdata('pesan', 'Registrasi gagal. Terjadi kesalahan pada server.');
                $this->session->set_flashdata('alert_type', 'danger');
                redirect('register');
            }
        }
    }
}
