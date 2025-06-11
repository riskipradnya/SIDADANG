<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_kedatangan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        // Memuat komponen yang dibutuhkan, sama seperti di controller Pendatang.php
        $this->load->database();
        $this->load->library('session');
        $this->load->helper('url');

        // PENTING: Tambahkan pengecekan login di sini
        // Contoh:
        // if (!$this->session->userdata('is_logged_in')) {
        //     redirect('auth');
        // }
    }

    /**
     * Menampilkan halaman daftar pendatang yang siap cetak surat.
     */
    public function index()
    {
        // === Query Database Langsung di Controller ===
        $this->db->from('tbpendatang');
        $this->db->where('statusAktivasi', 'Terverifikasi');
        $this->db->order_by('nama', 'ASC'); 
        $query = $this->db->get();
        $data['pendatang_terverifikasi'] = $query->result();
        // ===========================================

        // Memuat view dan mengirimkan data
        $template_data['konten'] = $this->load->view('surat_kedatangan_view', $data, TRUE);
        $template_data['title']  = 'Cetak Surat Kedatangan'; // Opsional, jika template Anda menggunakan title
        $this->load->view('admin_view', $template_data);
    }
}