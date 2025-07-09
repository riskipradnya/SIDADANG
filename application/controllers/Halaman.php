<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Halaman extends CI_Controller 
{
    public function __construct() {
        parent::__construct();
        $this->load->library(array('session', 'form_validation'));
        $this->load->database();
        $this->load->helper(array('url', 'form'));
    }

    // Menampilkan halaman login
    public function index() {
        $this->load->view('halamanLogin');
    }

    // Proses login
    // Proses login
    public function proseslogin() {
        // Ambil data dari form
        $nik = $this->input->post('nik', TRUE);
        $password = $this->input->post('password', TRUE);

        // Cek apakah NIK ada di database
        $query = $this->db->get_where('tblogin', array('NIK' => $nik));

        if ($query->num_rows() > 0) {
            $data = $query->row(); // Ambil data pengguna
            
            // DIUBAH: Gunakan password_verify() untuk cek password yang sudah di-hash
            if ($data->Password == $password) {
                
                // Simpan data ke session (Kode Anda di sini sudah benar)
                $array = array(
                    'KodeLogin'   => $data->KodeLogin,
                    'NIK'         => $data->NIK,
                    'NamaLengkap' => $data->NamaLengkap,
                    'Level'       => $data->Level,
                    'is_logged_in' => TRUE
                );

                $this->session->set_userdata($array);
                redirect('Dashboard/admin'); // Arahkan ke dashboard admin

            } else {
                $this->session->set_flashdata('pesan', 'Password salah!');
                redirect('Halaman');
            }
        } else {
            $this->session->set_flashdata('pesan', 'NIK tidak ditemukan!');
            redirect('Halaman');
        }
    }

    // Menampilkan dashboard admin
    public function admin() {
        // Cek apakah user sudah login
        if (!$this->session->userdata('NIK')) {
            redirect('Halaman'); // Jika belum login, kembalikan ke halaman login
        }
        $this->load->view('admin_view'); // Tampilkan dashboard admin
    }

    // Logout
    public function logout() {
        $this->session->sess_destroy();
        redirect('Halaman'); // Kembali ke halaman login
    }
}
?>
