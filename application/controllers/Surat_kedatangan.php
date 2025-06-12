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
    // application/controllers/Surat_kedatangan.php

    public function index()
    {
        // 1. Query untuk mengambil data pendatang (ini sudah ada)
        $this->db->from('tbpendatang');
        $this->db->where('statusAktivasi', 'Terverifikasi');
        $this->db->order_by('nama', 'ASC'); 
        $query_pendatang = $this->db->get();
        $data['pendatang_terverifikasi'] = $query_pendatang->result();

        // ==========================================================
        // 2. TAMBAHKAN QUERY BARU INI untuk mengambil data keperluan
        // ==========================================================
        $this->db->from('tbkeperluansurat');
        $this->db->where('is_active', 1); // Hanya ambil yang aktif
        $this->db->order_by('nama_keperluan', 'ASC');
        $query_keperluan = $this->db->get();
        $data['list_keperluan'] = $query_keperluan->result();
        // ==========================================================

        // Memuat view dan mengirimkan SEMUA data
        $template_data['konten'] = $this->load->view('surat_kedatangan_view', $data, TRUE);
        $this->load->view('admin_view', $template_data);
    }
}