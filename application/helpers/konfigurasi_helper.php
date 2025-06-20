<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('get_email_config')) {
    /**
     * Mengambil konfigurasi email dari database dan mengubahnya menjadi array
     * yang siap digunakan oleh library Email CodeIgniter.
     */
    function get_email_config()
    {
        // Dapatkan instance CodeIgniter
        $CI =& get_instance();

        // Load database jika belum
        if (!isset($CI->db)) {
            $CI->load->database();
        }

        // --- PERUBAHAN DI SINI ---
        // Mengambil data dari tabel 'tbkonfigurasi' sesuai nama tabel Anda
        $email_config_from_db = $CI->db->get('tbkonfigurasi')->result();

        $config = array();
        foreach ($email_config_from_db as $item) {
            if ($item->nama_konfigurasi == 'smtp_port') {
                $config[$item->nama_konfigurasi] = (int)$item->nilai_konfigurasi;
            } else {
                $config[$item->nama_konfigurasi] = $item->nilai_konfigurasi;
            }
        }

        return $config;
    }
}