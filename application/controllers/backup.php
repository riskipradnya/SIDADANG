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
    public function proseslogin() {
        // Ambil data dari form
        $username = $this->input->post('username', TRUE);
        $password = $this->input->post('password', TRUE);

        // Cek apakah username ada di database
        $query = $this->db->get_where('tblogin', array('username' => $username));

        if ($query->num_rows() > 0) {
            $data = $query->row(); // Ambil data pengguna
            
            // Cek apakah password cocok
            if ($data->password == $password) { // **Pastikan password terenkripsi jika di database**
                $array = array(
                    'KodeLogin'   => $data->KodeLogin,
                    'username'    => $data->username,
                    'namaLengkap' => $data->namaLengkap,
                    'Level'       => $data->Level
                );

                $this->session->set_userdata($array);
                redirect('Halaman/admin'); // Arahkan ke dashboard admin
            } else {
                $this->session->set_flashdata('pesan', 'Password salah!');
                redirect('Halaman');
            }
        } else {
            $this->session->set_flashdata('pesan', 'Username tidak ditemukan!');
            redirect('Halaman');
        }
    }

    // Menampilkan dashboard admin
    public function admin() {
        // Cek apakah user sudah login
        if (!$this->session->userdata('username')) {
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

<!-- DASHBOARD -->

<?php
class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Pastikan hanya admin yang bisa mengakses controller ini
        if ($this->session->userdata('Level') !== 'admin') {
            $this->session->set_flashdata('pesan', 'Anda tidak memiliki akses sebagai admin.');
            redirect('Halaman', 'refresh');
        }
        log_message('debug', 'User Level: ' . $this->session->userdata('Level'));

    }

    public function admin()
    {
        $this->load->view('admin_view'); // Load halaman admin
    }

    public function logout()
    {
        $this->session->sess_destroy(); // Hapus session
        redirect('Halaman', 'refresh'); // Redirect ke login
    }
}

?>

<!-- END DASHBOARD -->

	
	
