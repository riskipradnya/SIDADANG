<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form', 'url', 'string'));
        $this->load->library(array('form_validation', 'session', 'email'));
    }

    public function index()
    {
        $this->load->view('halamanRegister');
    }

    public function register()
    {
        $this->form_validation->set_rules('nik', 'NIK', 'required');
        $this->form_validation->set_rules('namaLengkap', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('nomerTelepon', 'Nomor Telepon', 'required');
        $this->form_validation->set_rules('jabatan', 'Jabatan', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

        if ($this->input->post('jabatan') === 'PJ') {
            $this->form_validation->set_rules('alamat', 'Alamat', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('halamanRegister');
        } else {
            $email = $this->input->post('email');
            
            $temporary_password = random_string('alnum', 8); 
            $reset_token = random_string('alnum', 32); 

            $jabatan = $this->input->post('jabatan');
            $nik = $this->input->post('nik');

            if ($jabatan == 'KALING') {
                $check = $this->db->get_where('tbkaling', ['NIK' => $nik])->row();
                if ($check) { 
                    $this->session->set_flashdata('pesan', 'NIK sudah terdaftar.');
                    $this->session->set_flashdata('alert_type', 'danger');
                    redirect('register');
                    return; 
                }

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
                if ($check) { 
                    $this->session->set_flashdata('pesan', 'NIK sudah terdaftar.');
                    $this->session->set_flashdata('alert_type', 'danger');
                    redirect('register');
                    return; 
                }
                
                $data_pj = array(
                    'NIK'                 => $nik,
                    'namaLengkap'         => $this->input->post('namaLengkap'),
                    'alamat'              => $this->input->post('alamat'),
                    'telp'                => $this->input->post('nomerTelepon'),
                    'email'               => $email,
                    'jenisAkun'           => $jabatan,
                    'id_kepala_lingkungan' => 18,
                    'password'            => $temporary_password, // Simpan password asli
                    'statusAktivasi'      => 'Belum',
                    'reset_token'         => $reset_token
                );
                $this->db->insert('tbpj', $data_pj);
            }

            if ($this->db->affected_rows() > 0) {
                // *** PERUBAHAN DI SINI: Teruskan $temporary_password ***
                $this->_kirim_email_aktivasi($reset_token, $email, $temporary_password);
                
                $this->session->set_flashdata('pesan', 'Registrasi berhasil! Silakan cek email Anda untuk detail akun.');
                $this->session->set_flashdata('alert_type', 'success');
                redirect('Halaman');
            } else {
                $this->session->set_flashdata('pesan', 'Registrasi gagal. Coba lagi.');
                $this->session->set_flashdata('alert_type', 'danger');
                redirect('register');
            }
        }
    }

    // *** PERUBAHAN DI SINI: Tambahkan $default_password sebagai parameter ***
    private function _kirim_email_aktivasi($token, $email, $default_password)
    {
        $this->email->from($this->config->item('smtp_user'), 'Admin SIDADANG');
        $this->email->to($email);
        $this->email->subject('Aktivasi Akun dan Pengaturan Password SIDADANG');

        $message  = "<h4>Terima kasih telah mendaftar di SIDADANG.</h4>";
        $message .= "<p>Akun Anda telah dibuat dengan detail sebagai berikut:</p>";
        $message .= "<p>Email: <b>" . $email . "</b></p>";
        $message .= "<p>Password Default: <b>" . $default_password . "</b></p>"; // Tampilkan password default
        $message .= "<br>";
        $message .= "<p>Untuk keamanan, kami sangat menyarankan Anda untuk segera mengubah password setelah login pertama kali. Silakan klik tautan di bawah ini untuk mengatur password baru Anda:</p>";
        $message .= "<h3><a href='" . site_url('auth/reset_password/' . $token) . "'>Atur Password Saya</a></h3>";
        $message .= "<br><br>";
        $message .= "<p>Jika Anda tidak merasa mendaftar, abaikan email ini.</p>";

        $this->email->message($message);

        if (!$this->email->send()) {
            echo $this->email->print_debugger();
            die;
        }
    }
}