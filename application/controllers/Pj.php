<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pj extends CI_Controller
{
    // Ganti dengan ID Kepala Lingkungan default di sistem Anda
    const DEFAULT_KALING_ID = 14; 

    public function __construct()
    {
        parent::__construct();
        // Memuat semua library & model yang dibutuhkan di satu tempat
        $this->load->database();
        $this->load->model('validasi');
        $this->load->library('form_validation');
        $this->load->library('session');
        // Library email akan di-load saat dibutuhkan
        // Helper 'konfigurasi' sudah di-load via autoload.php

        // Menjalankan validasi login untuk setiap fungsi di controller ini
        $this->validasi->validasiakun();
    }

    // Menampilkan halaman utama (form dan tabel)
    public function index()
    {
        $datalist['hasil'] = $this->tampildata();
        $data['konten'] = $this->load->view('pj_view', '', TRUE);
        $data['table'] = $this->load->view('pj_table', $datalist, TRUE);
        $this->load->view('admin_view', $data);
    }
    
    // Fungsi untuk verifikasi status aktivasi
    public function verifikasidata($kodeDaftar)
    {
        $this->db->where('kodeDaftar', $kodeDaftar);
        $this->db->update('tbpj', ['statusAktivasi' => 'Terverifikasi']);
        $this->session->set_flashdata('pesan', 'Akun berhasil diverifikasi!');
        redirect('pj', 'refresh');
    }

    /**
     * Fungsi Simpan Data FINAL
     * Meng-handle insert (dengan generate password & kirim email) dan update data.
     */
    public function simpandata()
    {
        $kodeDaftar = $this->input->post('kodeDaftar');

        // Mengatur aturan validasi
        // Jika ini adalah data baru (insert), NIK dan Email harus unik
        if (empty($kodeDaftar)) {
            $this->form_validation->set_rules('NIK', 'NIK', 'required|is_unique[tbpj.NIK]|numeric|exact_length[16]', [
                'is_unique' => 'NIK ini sudah terdaftar.',
                'exact_length' => 'NIK harus 16 digit.'
            ]);
            $this->form_validation->set_rules('Email', 'Email', 'required|valid_email|is_unique[tbpj.Email]', [
                'is_unique' => 'Email ini sudah terdaftar.'
            ]);
        } else { // Jika ini update, NIK harus unik kecuali untuk dirinya sendiri
            $this->form_validation->set_rules('NIK', 'NIK', 'required|numeric|exact_length[16]|callback_edit_unique_nik[' . $kodeDaftar . ']');
        }
        $this->form_validation->set_rules('Nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('Telp', 'No. Handphone', 'required|numeric');

        // Menjalankan validasi
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('pj', 'refresh');
            return;
        }

        // Mengambil data dari form
        $NIK = $this->input->post('NIK');
        $NamaLengkap = $this->input->post('Nama');
        $Telp = $this->input->post('Telp');
        $Alamat = $this->input->post('Alamat');
        $Email = $this->input->post('Email');
        $jenisAkun = $this->input->post('jenisAkun');

        // Menyiapkan data untuk disimpan ke database
        $data = [
            'NIK' => $NIK,
            'NamaLengkap' => $NamaLengkap,
            'Telp' => $Telp,
            'Alamat' => $Alamat,
            'Email' => $Email,
            'jenisAkun' => $jenisAkun,
        ];

        if (empty($kodeDaftar)) { // --- PROSES INSERT DATA BARU ---
            
            // 1. Generate password acak 6 digit
            $password_acak = mt_rand(100000, 999999);

            // 2. Hash password sebelum disimpan ke DB (PENTING!)
            $data['Password'] = password_hash($password_acak, PASSWORD_DEFAULT);
            $data['statusAktivasi'] = 'Belum';
            $data['id_kepala_lingkungan'] = self::DEFAULT_KALING_ID;

            // 3. Insert data ke tabel 'tbpj'
            $this->db->insert('tbpj', $data);
            $is_inserted = $this->db->affected_rows() > 0;

            if ($is_inserted) {
                // 4. Jika insert berhasil, kirim email berisi password
                $email_sent = $this->_kirim_password_email($Email, $NamaLengkap, $password_acak);
                
                if ($email_sent) {
                    $this->session->set_flashdata('pesan', 'Data PJ baru berhasil disimpan. Password telah dikirim ke email pendaftar.');
                } else {
                    $this->session->set_flashdata('error', 'Data PJ berhasil disimpan, namun GAGAL mengirim email password. Periksa log error di server.');
                }
            } else {
                 $this->session->set_flashdata('error', 'Gagal menyimpan data ke database.');
            }

        } else { // --- PROSES UPDATE DATA LAMA ---
            $this->db->where('kodeDaftar', $kodeDaftar);
            $this->db->update('tbpj', $data);
            $this->session->set_flashdata('pesan', 'Data Penanggung Jawab berhasil diedit.');
        }

        redirect('pj', 'refresh');
    }

    /**
     * Fungsi private untuk mengirim email dengan konfigurasi dinamis dari Database.
     */
    private function _kirim_password_email($email_tujuan, $nama_lengkap, $password_asli)
    {
        $email_config = get_email_config(); // Mengambil konfigurasi dari helper

        if (empty($email_config) || !isset($email_config['smtp_user'])) {
            log_message('error', 'Konfigurasi email tidak ditemukan di database.');
            return false;
        }

        $this->load->library('email');
        $this->email->initialize($email_config);

        $this->email->from($email_config['smtp_user'], $email_config['smtp_from_name']);
        $this->email->to($email_tujuan);
        $this->email->subject('Pendaftaran Akun Berhasil - Password Anda');
        
        $pesan = "
            <h3>Halo, " . htmlspecialchars($nama_lengkap, ENT_QUOTES, 'UTF-8') . "</h3>
            <p>Pendaftaran akun Anda sebagai Penanggung Jawab di sistem SIDADANG telah berhasil.</p>
            <p>Gunakan informasi di bawah ini untuk login ke sistem:</p>
            <ul>
                <li><strong>Email:</strong> " . htmlspecialchars($email_tujuan, ENT_QUOTES, 'UTF-8') . "</li>
                <li><strong>Password:</strong> <strong style='font-size: 1.2em;'>" . $password_asli . "</strong></li>
            </ul>
            <p>Harap segera login dan ganti password Anda untuk keamanan.</p>
            <br>
            <p>Terima kasih.</p>
        ";

        $this->email->message($pesan);

        if ($this->email->send()) {
            return true;
        } else {
            log_message('error', $this->email->print_debugger(array('headers')));
            return false;
        }
    }

    // Callback untuk validasi NIK unik saat proses edit
    public function edit_unique_nik($nik, $kodeDaftar) 
    {
        $this->db->where('NIK', $nik);
        $this->db->where('kodeDaftar !=', $kodeDaftar);
        $query = $this->db->get('tbpj');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('edit_unique_nik', 'NIK ini sudah digunakan oleh Penanggung Jawab lain.');
            return FALSE;
        }
        return TRUE;
    }

    // Menampilkan data untuk tabel
    public function tampildata()
    {
        $this->db->select('tbpj.*, tbkaling.namaLengkap as nama_kaling');
        $this->db->from('tbpj');
        $this->db->join('tbkaling', 'tbkaling.kodeDaftar = tbpj.id_kepala_lingkungan', 'left');
        $this->db->order_by('tbpj.kodeDaftar', 'DESC');
        $query = $this->db->get();

        return ($query->num_rows() > 0) ? $query->result() : [];
    }
    
    // Menghapus data
    public function hapusdata($kodeDaftar)
    {
        $is_used = $this->db->where('id_penanggung_jawab', $kodeDaftar)->count_all_results('tbpendatang');
        if ($is_used > 0) {
            $this->session->set_flashdata('error', 'Data Penanggung Jawab tidak bisa dihapus karena masih digunakan di data pendatang.');
        } else {
            $this->db->where('kodeDaftar', $kodeDaftar);
            $this->db->delete('tbpj');
            $this->session->set_flashdata('pesan', 'Data berhasil dihapus!');
        }
        redirect('pj', 'refresh');
    }

    // Mengambil satu baris data untuk di-edit dan dikirim sebagai JSON
    public function editdata($kodeDaftar)
    {
        $query = $this->db->get_where('tbpj', ['kodeDaftar' => $kodeDaftar]);
        if ($query->num_rows() > 0) {
            echo json_encode($query->row());
        }
    }
}