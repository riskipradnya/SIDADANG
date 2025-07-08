<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper(array('form', 'url'));
        $this->load->library(array('form_validation', 'session'));
    }

    // Fungsi untuk menampilkan halaman reset password
    public function reset_password($token)
    {
        if (empty($token)) {
            redirect('Halaman'); // Redirect jika token kosong
        }

        // Cek token di tabel tbkaling
        $user_kaling = $this->db->get_where('tbkaling', ['reset_token' => $token])->row();
        // Cek token di tabel tbpj
        $user_pj = $this->db->get_where('tbpj', ['reset_token' => $token])->row();

        if ($user_kaling || $user_pj) {
            $data['token'] = $token;
            $this->load->view('v_reset_password', $data);
        } else {
            $this->session->set_flashdata('pesan', 'Token tidak valid atau sudah kedaluwarsa.');
            $this->session->set_flashdata('alert_type', 'danger');
            redirect('Halaman'); // Redirect jika token tidak ditemukan
        }
    }

    // Fungsi untuk memproses update password baru
    public function update_password()
    {
        $this->form_validation->set_rules('password', 'Password Baru', 'required|min_length[6]');
        $this->form_validation->set_rules('confirm_password', 'Konfirmasi Password', 'required|matches[password]');
        
        $token = $this->input->post('token');

        if ($this->form_validation->run() == FALSE) {
            $this->reset_password($token);
        } else {
            $user = null;
            $user_table = '';
            $user_id_column = '';

            $user_kaling = $this->db->get_where('tbkaling', ['reset_token' => $token])->row();
            if ($user_kaling) {
                $user = $user_kaling;
                $user_table = 'tbkaling';
                $user_id_column = 'kodeDaftar'; // <-- GANTI JIKA NAMA KOLOM ID ANDA BEDA
            } else {
                $user_pj = $this->db->get_where('tbpj', ['reset_token' => $token])->row();
                if ($user_pj) {
                    $user = $user_pj;
                    $user_table = 'tbpj';
                    $user_id_column = 'kodeDaftar'; // <-- GANTI JIKA NAMA KOLOM ID ANDA BEDA
                }
            }

            if ($user) {
                // Ambil password langsung dari input, tanpa di-hash
                $password = $this->input->post('password');

                $data_login = [
                    'id_asli'     => $user->{$user_id_column},
                    'NIK'         => $user->NIK,
                    'Password'    => $password, // Simpan password asli
                    'NamaLengkap' => $user->namaLengkap,
                    'Level'       => $user->jenisAkun
                ];

                $this->db->insert('tblogin', $data_login);

                $update_data = [
                    'password'       => $password, // Simpan password asli
                    'statusAktivasi' => 'Terverifikasi',
                    'reset_token'    => NULL
                ];
                $this->db->where('reset_token', $token);
                $this->db->update($user_table, $update_data);

                $this->session->set_flashdata('pesan', 'Password berhasil diatur dan akun telah terverifikasi! Silakan login.');
                $this->session->set_flashdata('alert_type', 'success');
                redirect('Halaman');

            } else {
                $this->session->set_flashdata('pesan', 'Token tidak valid atau sudah kedaluwarsa.');
                $this->session->set_flashdata('alert_type', 'danger');
                redirect('Halaman');
            }
        }
    }
}