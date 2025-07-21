<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    /**
     * Konstruktor ini sekarang hanya memeriksa apakah pengguna sudah login.
     * Pengecekan level ('Admin', 'KALING', 'PJ') dihapus dari sini.
     */
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('NIK')) {
            $this->session->set_flashdata('pesan', 'Silakan login terlebih dahulu.');
            redirect('Halaman');
        }

        // Pastikan database selalu di-load
        if (!isset($this->db)) {
            $this->load->database();
        }
    }

    /**
     * Fungsi index() ini sekarang menjadi pintu masuk utama untuk SEMUA level.
     * Ia akan mengumpulkan data dan menampilkan view dashboard yang sama untuk semua.
     */
    public function index()
    {
        // --- TAMBAHKAN KODE INI UNTUK DEBUGGING ---
        // echo "<pre>";
        // print_r($this->session->all_userdata());
        // echo "</pre>";
        // die; 
        
        // Pindahkan inisialisasi array $view_data ke paling awal
        $view_data = []; // <--- DIPINDAHKAN KE SINI

        // Ambil level pengguna dari session dan masukkan ke $view_data
        $view_data['Level'] = $this->session->userdata('Level'); 

        // 1. Hitung jumlah data TERVERIFIKASI
        $view_data['jumlah_pj_terverifikasi'] = $this->db->where('statusAktivasi', 'Terverifikasi')->count_all_results('tbpj');
        $view_data['jumlah_kaling_terverifikasi'] = $this->db->where('statusAktivasi', 'Terverifikasi')->count_all_results('tbkaling');
        $view_data['jumlah_pendatang_terverifikasi'] = $this->db->where('statusAktivasi', 'Terverifikasi')->count_all_results('tbpendatang');

        // 2. Hitung jumlah data BELUM TERVERIFIKASI
        $view_data['jumlah_pj_belum_verif'] = $this->db->where('statusAktivasi', 'Belum')->count_all_results('tbpj');
        $view_data['jumlah_kaling_belum_verif'] = $this->db->where('statusAktivasi', 'Belum')->count_all_results('tbkaling');
        $view_data['jumlah_pendatang_belum_verif'] = $this->db->where('statusAktivasi', 'Belum Terverifikasi')->count_all_results('tbpendatang');
        
        // 3. Siapkan data untuk dikirim ke template utama
        $template_data = [];
        $template_data['title'] = 'Dashboard SIDADANG';
        // Semua level akan me-load view konten yang sama yaitu 'dashboard_utama_view'
        // $view_data sekarang berisi 'Level' dan semua data statistik
        $template_data['konten'] = $this->load->view('dashboard_utama_view', $view_data, TRUE); 

        // Semua level akan me-load template/kerangka halaman yang sama yaitu 'admin_view'
        $this->load->view('admin_view', $template_data);
    }
    
    /**
     * Fungsi admin() yang lama tidak lagi diperlukan karena sudah digantikan oleh index().
     * Jika Anda masih memiliki link yang mengarah ke /Dashboard/admin, Anda bisa membuat
     * fungsi ini hanya untuk mengarahkan ke index().
     */
    public function admin()
    {
        redirect('Dashboard/index');
    }


    public function logout()
    {
        $this->session->sess_destroy();
        redirect('Halaman');
    }
}