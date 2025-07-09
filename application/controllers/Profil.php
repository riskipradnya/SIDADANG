<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('validasi');
        $this->validasi->validasiakun(); 
        $this->load->model('Profil_model');
        
        // MENAMBAHKAN LIBRARY UNTUK FORM & SESSION
        $this->load->library('form_validation');
        $this->load->library('session');
    }

    public function index()
    {
        $nik = $this->session->userdata('NIK');
        $level = $this->session->userdata('Level'); 
        
        $data = [];
        $data['user_data'] = $this->Profil_model->get_user_details($nik, $level);

        if (!$data['user_data']) {
            show_error('Data pengguna tidak ditemukan. Pastikan session NIK dan Level sudah benar.', 404);
            return;
        }

        if ($level == 'KALING') {
            $kaling_id = $data['user_data']->kodeDaftar;
            $data['specific_data']['jumlah_pj'] = $this->Profil_model->count_pj_by_kaling($kaling_id);
            $data['specific_data']['jumlah_pendatang'] = $this->Profil_model->count_pendatang_by_kaling($kaling_id);
        
        } elseif ($level == 'PJ') {
            $pj_id = $data['user_data']->kodeDaftar;
            $kaling_id = $data['user_data']->id_kepala_lingkungan;
            $data['specific_data']['kaling_info'] = $this->Profil_model->get_kaling_info_for_pj($kaling_id);
            $data['specific_data']['jumlah_pendatang'] = $this->Profil_model->count_pendatang_by_pj($pj_id);
        }

        $layout_data['konten'] = $this->load->view('profil_view', $data, TRUE);
        $this->load->view('admin_view', $layout_data);
    }

    // =======================================================
    // FUNGSI BARU UNTUK PROSES UBAH PASSWORD
    // =======================================================
    public function proses_ubah_password() {
        // Aturan validasi form
        $this->form_validation->set_rules('password_lama', 'Password Lama', 'required');
        $this->form_validation->set_rules('password_baru', 'Password Baru', 'required|min_length[6]', [
            'min_length' => 'Password baru minimal harus 6 karakter.'
        ]);
        $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Password', 'required|matches[password_baru]', [
            'matches' => 'Konfirmasi password tidak cocok dengan password baru.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembalikan ke halaman profil dengan notifikasi error
            $this->session->set_flashdata('password_error', validation_errors());
            redirect('profil'); // Redirect ke method index() dari controller ini
        } else {
            // Jika validasi berhasil, lanjutkan proses
            // Menggunakan 'kodeDaftar' sebagai ID unik user, sesuai struktur di method index()
            $id_user = $this->session->userdata('KodeLogin'); 
            $password_lama = $this->input->post('password_lama');
            $password_baru = $this->input->post('password_baru');

            // echo "ID User dari Session ('KodeLogin'): ";
            // var_dump($id_user);
            // echo "<br>Password Lama dari Form: ";
            // var_dump($password_lama);
            // die; 


            // 1. Panggil model untuk cek kebenaran password lama
            $is_password_valid = $this->Profil_model->cek_password_lama($id_user, $password_lama);

            if ($is_password_valid) {
                // 2. Jika password lama benar, panggil model untuk update password baru
                $this->Profil_model->update_password($id_user, $password_baru);
                $this->session->set_flashdata('password_success', 'Password berhasil diubah!');
            } else {
                // Jika password lama salah
                $this->session->set_flashdata('password_error', 'Password lama yang Anda masukkan salah.');
            }

            redirect('profil'); // Kembali ke halaman profil
        }
    }
}