<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil_model extends CI_Model {

    // Mengambil data detail user dari tbkaling atau tbpj
    public function get_user_details($nik, $level)
    {
        if ($level == 'KALING') {
            return $this->db->get_where('tbkaling', ['NIK' => $nik])->row();
        } elseif ($level == 'PJ') {
            return $this->db->get_where('tbpj', ['NIK' => $nik])->row();
        }
        return null;
    }

    // --- Fungsi untuk Kaling ---
    public function count_pj_by_kaling($kaling_id)
    {
        return $this->db->where('id_kepala_lingkungan', $kaling_id)->count_all_results('tbpj');
    }

    public function count_pendatang_by_kaling($kaling_id)
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('tbpendatang p');
        $this->db->join('tbpj pj', 'p.id_penanggung_jawab = pj.kodeDaftar');
        $this->db->where('pj.id_kepala_lingkungan', $kaling_id);
        return $this->db->get()->row()->total;
    }

    // --- Fungsi untuk PJ ---
    public function get_kaling_info_for_pj($kaling_id)
    {
        return $this->db->get_where('tbkaling', ['kodeDaftar' => $kaling_id])->row();
    }

    public function count_pendatang_by_pj($pj_id)
    {
        return $this->db->where('id_penanggung_jawab', $pj_id)->count_all_results('tbpendatang');
    }
    
    // PENAMBAHAN -> Tanda '/**' untuk membuka blok komentar
    /** * Cek apakah password lama yang diinputkan user sama dengan yang ada di database.
     * @param int $id_user ID user yang sedang login (ini adalah KodeLogin)
     * @param string $password_lama Password lama dari form
     * @return bool TRUE jika password cocok, FALSE jika tidak
     */
    public function cek_password_lama($id_user, $password_lama) {
        $this->db->where('KodeLogin', $id_user);
        $user = $this->db->get('tblogin')->row();

        // DIUBAH: Membandingkan langsung password teks biasa dari form
        // dengan password teks biasa dari database.
        if ($user && $user->Password == $password_lama) {
            return true;
        }
        return false;
    }

    /**
     * Update password user di database.
     * @param int $id_user ID user yang akan diupdate (ini adalah KodeLogin)
     * @param string $password_baru Password baru dari form
     * @return bool Hasil dari operasi update
     */
    public function update_password($id_user, $password_baru) {
        // DIUBAH: Langsung menyimpan password baru sebagai teks biasa
        // tanpa menggunakan password_hash().
        $this->db->where('KodeLogin', $id_user);
        return $this->db->update('tblogin', ['Password' => $password_baru]);
    }
}