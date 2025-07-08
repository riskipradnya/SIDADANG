<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        // Menambahkan helper 'string' untuk membuat token acak
        $this->load->helper(array('form', 'url', 'string'));
        $this->load->library(array('form_validation', 'session', 'email')); // Menambahkan library 'email'
    }

    public function index()
    {
        $this->load->view('halamanRegister');
    }

    // Fungsi register (TANPA HASHING)
    public function register()
    {
        // 1. ATURAN VALIDASI (TANPA PASSWORD)
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('namaLengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('nomerTelepon', 'Nomor Telepon', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->input->post('jabatan') === 'PJ') {
            $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        }

        // 2. JALANKAN VALIDASI
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('halamanRegister');
        } else {
            // 3. GENERATE PASSWORD SEMENTARA & TOKEN
            $email = $this->input->post('email');
            
            // Membuat password acak sementara (8 karakter)
            $temporary_password = random_string('alnum', 8); 
            // Baris hashing password dihapus
            
            // Membuat token unik untuk reset password
            $reset_token = random_string('alnum', 32); 

            // 4. LOGIKA SIMPAN DATA BERDASARKAN JABATAN
            $jabatan = $this->input->post('jabatan');
            $nik = $this->input->post('nik');

            if ($jabatan == 'KALING') {
                $check = $this->db->get_where('tbkaling', ['NIK' => $nik])->row();
                if ($check) { return; }

                $data_kaling = array(
                    'NIK'            => $nik,
                    'namaLengkap'    => $this->input->post('namaLengkap'),
                    'email'          => $email,
                    'telp'           => $this->input->post('nomerTelepon'),
                    'jenisAkun'      => $jabatan,
                    'password'       => $temporary_password, // Simpan password asli
                    'statusAktivasi' => 'Belum',
                    'reset_token'    => $reset_token
                );
                $this->db->insert('tbkaling', $data_kaling);

            } elseif ($jabatan == 'PJ') {
                $check = $this->db->get_where('tbpj', ['NIK' => $nik])->row();
                if ($check) { return; }
                
                $data_pj = array(
                    'NIK'                   => $nik,
                    'namaLengkap'           => $this->input->post('namaLengkap'),
                    'alamat'                => $this->input->post('alamat'),
                    'telp'                  => $this->input->post('nomerTelepon'),
                    'email'                 => $email,
                    'jenisAkun'             => $jabatan,
                    'id_kepala_lingkungan'  => 18,
                    'password'              => $temporary_password, // Simpan password asli
                    'statusAktivasi'        => 'Belum',
                    'reset_token'           => $reset_token
                );
                $this->db->insert('tbpj', $data_pj);
            }

            // 5. KIRIM EMAIL DAN BERI FEEDBACK
            if ($this->db->affected_rows() > 0) {
                $this->_kirim_email_aktivasi($reset_token, $email);
                
                $this->session->set_flashdata('pesan', 'Registrasi berhasil! Silakan cek email Anda untuk mengatur password.');
                $this->session->set_flashdata('alert_type', 'success');
                redirect('Halaman');
            } else {
                $this->session->set_flashdata('pesan', 'Registrasi gagal. Coba lagi.');
                $this->session->set_flashdata('alert_type', 'danger');
                redirect('register');
            }
        }
    }

    // Fungsi private baru untuk mengirim email
    // Fungsi private baru untuk mengirim email (VERSI SUDAH DIPERBAIKI)
    private function _kirim_email_aktivasi($token, $email)
    {
        // Konfigurasi email sudah dimuat secara otomatis di constructor.
        // Jadi, kita tidak perlu load dan initialize lagi di sini.
        // Baris di bawah ini yang menyebabkan error dan harus dihapus:
        // $this->config->load('email');
        // $this->email->initialize($this->config->item(null));

        // Langsung gunakan library email
        $this->email->from($this->config->item('smtp_user'), 'Admin SIDADANG');
        $this->email->to($email);
        $this->email->subject('Aktivasi Akun dan Pengaturan Password SIDADANG');

        $message  = "<h4>Terima kasih telah mendaftar di SIDADANG.</h4>";
        $message .= "<p>Akun Anda telah dibuat. Silakan klik tautan di bawah ini untuk mengatur password Anda:</p>";
        $message .= "<h3><a href='" . site_url('auth/reset_password/' . $token) . "'>Atur Password Saya</a></h3>";
        $message .= "<br><br>";
        $message .= "<p>Jika Anda tidak merasa mendaftar, abaikan email ini.</p>";

        $this->email->message($message);

        if (!$this->email->send()) {
            // Jika pengiriman email masih gagal, hapus tanda komentar di bawah ini
            // untuk melihat pesan error dari server email, lalu refresh halaman error.
            echo $this->email->print_debugger();
            die;
        }
    }
}