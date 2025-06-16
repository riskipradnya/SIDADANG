<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Mengambil semua data pengajuan surat dengan informasi pendatang dan keperluannya.
     */
    public function get_all_pengajuan()
    {
        $this->db->select(
            'ps.*, ' . // Mengambil semua kolom dari tbpengajuansurat
            'p.nama as nama_pendatang, ' . // Mengambil nama pendatang dan beri alias 'nama_pendatang'
            'p.nik as nik_pendatang, ' .   // Mengambil NIK pendatang dan beri alias 'nik_pendatang'
            'kp.nama_keperluan' // Mengambil nama keperluan dari tabel keperluan
        );
        $this->db->from('tbpengajuansurat as ps'); // 'ps' adalah alias untuk tbpengajuansurat
        
        // Gabungkan (JOIN) dengan tabel tbpendatang
        $this->db->join('tbpendatang as p', 'ps.id_pendatang = p.id', 'left');

        // Gabungkan (JOIN) dengan tabel tbkeperluansurat
        // ASUMSI: Anda punya tabel 'tbkeperluansurat' dengan kolom 'id' dan 'nama_keperluan'
        $this->db->join('tbkeperluansurat as kp', 'ps.id_keperluan = kp.id', 'left');
        
        $this->db->order_by('ps.tanggal_pengajuan', 'DESC'); // Urutkan berdasarkan tanggal terbaru

        $query = $this->db->get();
        return $query->result(); // Kembalikan hasil sebagai array of objects
    }
}