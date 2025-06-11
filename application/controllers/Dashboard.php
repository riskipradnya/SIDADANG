<?php
class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('NIK')) {
            $this->session->set_flashdata('pesan', 'Silakan login terlebih dahulu.');
            redirect('Halaman');
        }
        if ($this->session->userdata('Level') !== 'Admin') {
            $this->session->set_flashdata('pesan', 'Anda tidak memiliki akses sebagai admin.');
            redirect('Halaman');
        }
        if (!isset($this->db)) {
            $this->load->database();
        }
        log_message('debug', 'User Level: ' . $this->session->userdata('Level'));
    }

    public function admin()
    {
        $view_data = []; // Data yang akan dikirim ke view konten dashboard

        // 1. Hitung jumlah data TERVERIFIKASI (sudah ada)
        $this->db->where('statusAktivasi', 'Terverifikasi');
        $view_data['jumlah_pendatang_terverifikasi'] = $this->db->count_all_results('tbpendatang');

        $this->db->where('statusAktivasi', 'Terverifikasi');
        $view_data['jumlah_pj_terverifikasi'] = $this->db->count_all_results('tbpj');

        $this->db->where('statusAktivasi', 'Terverifikasi');
        $view_data['jumlah_kaling_terverifikasi'] = $this->db->count_all_results('tbkaling');

        // 2. Hitung jumlah data BELUM TERVERIFIKASI (BARU)
        $this->db->where('statusAktivasi', 'Belum Terverifikasi');
        $view_data['jumlah_pendatang_belum_verif'] = $this->db->count_all_results('tbpendatang');

        $this->db->where('statusAktivasi', 'Belum'); // Sesuaikan jika statusnya 'Belum Terverifikasi'
        $view_data['jumlah_pj_belum_verif'] = $this->db->count_all_results('tbpj');

        $this->db->where('statusAktivasi', 'Belum'); // Sesuaikan jika statusnya 'Belum Terverifikasi'
        $view_data['jumlah_kaling_belum_verif'] = $this->db->count_all_results('tbkaling');
        
        // Siapkan data untuk dikirim ke template utama admin_view.php
        $template_data = [];
        $template_data['title'] = 'Dashboard Admin';
        $template_data['konten'] = $this->load->view('dashboard_utama_view', $view_data, TRUE); 
                                        // Ganti 'dashboard_utama_view' jika nama file Anda berbeda

        $this->load->view('admin_view', $template_data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('Halaman');
    }
}
?>