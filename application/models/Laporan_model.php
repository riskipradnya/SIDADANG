<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 1. Deklarasi Class Model yang benar
class Laporan_model extends CI_Model {

    // 2. Fungsi untuk mengambil data laporan (Hanya ada satu fungsi yang benar)
    public function get_laporan_pendatang($bulan_tahun, $id_pj)
    {
        $this->db->select('
            p.nik as nik_pendatang,
            p.nama as nama_pendatang,
            p.jenis_kelamin,
            p.alamat_asal,
            p.alamat_sekarang as alamat_tujuan,
            p.tgl_masuk as tanggal_datang,
            p.tujuan as pekerjaan,
            pj.namaLengkap as nama_pj 
        ');
        $this->db->from('tbpendatang p');
        $this->db->join('tbpj pj', 'p.id_penanggung_jawab = pj.kodeDaftar', 'left');

        // Hanya ambil pendatang yang sudah terverifikasi
        $this->db->where('p.statusAktivasi', 'Terverifikasi');

        // Terapkan filter bulan jika dipilih
        if (!empty($bulan_tahun)) {
            $this->db->where("DATE_FORMAT(p.tgl_masuk, '%Y-%m') =", $bulan_tahun);
        }

        // Terapkan filter PJ jika dipilih
        if (!empty($id_pj)) {
            $this->db->where('p.id_penanggung_jawab', $id_pj);
        }

        $this->db->order_by('p.tgl_masuk', 'DESC');
        
        return $this->db->get()->result();
    }

} // 3. Penutup Class