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
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('namaLengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('nomerTelepon', 'Nomor Telepon', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');

        if ($this->input->post('jabatan') === 'PJ') {
            $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('halamanRegister');
        } else {
            $jabatan = $this->input->post('jabatan');
            $nik = $this->input->post('nik');

            // Cek NIK sudah ada atau belum
            if ($jabatan == 'KALING') {
                $check = $this->db->get_where('tbkaling', ['NIK' => $nik])->row();
            } elseif ($jabatan == 'PJ') {
                $check = $this->db->get_where('tbpj', ['NIK' => $nik])->row();
            } else {
                $check = null;
            }

            if ($check) {
                $this->session->set_flashdata('pesan', 'NIK sudah terdaftar.');
                $this->session->set_flashdata('alert_type', 'danger');
                $this->load->view('halamanRegister');
                return;
            }

            // Data umum
            $data = array(
                'NIK'             => $nik,
                'namaLengkap'     => $this->input->post('namaLengkap'),
                'email'           => $this->input->post('email'),
                'telp'            => $this->input->post('nomerTelepon'),
                'jenisAkun'       => $jabatan,
                'password'        => $this->buatpwd(),
                'statusAktivasi'  => 'Belum'
            );

            if ($jabatan == 'PJ') {
                $data['alamat'] = $this->input->post('alamat'); // hanya ditambahkan jika PJ
                $this->db->insert('tbpj', $data);
            } elseif ($jabatan == 'KALING') {
                $this->db->insert('tbkaling', $data); // tanpa kolom alamat
            }

            if ($this->db->affected_rows() > 0) {
                $this->session->set_flashdata('pesan', 'Registrasi berhasil!');
                $this->session->set_flashdata('alert_type', 'success');
                redirect('halaman');
            } else {
                $this->session->set_flashdata('pesan', 'Registrasi gagal. Silakan coba lagi.');
                $this->session->set_flashdata('alert_type', 'danger');
                redirect('register');
            }
        }
    }
}
