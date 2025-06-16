<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_kedatangan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        
        // 1. PASTIKAN SEMUA YANG DIBUTUHKAN SUDAH DI-LOAD DI SINI
        $this->load->helper('url'); 
        $this->load->library(['session', 'form_validation']);
        $this->load->model('surat_model'); 
    }

    public function index()
    {
        // 1. Siapkan data untuk FORM
        $data_form['pendatang_terverifikasi'] = $this->db->where('statusAktivasi', 'Terverifikasi')->order_by('nama', 'ASC')->get('tbpendatang')->result();
        $data_form['list_keperluan'] = $this->db->where('is_active', 1)->order_by('nama_keperluan', 'ASC')->get('tbkeperluansurat')->result();
        $template_data['konten'] = $this->load->view('surat_kedatangan_view', $data_form, TRUE);

        // 2. Siapkan data & view untuk TABEL SEMUA PENGAJUAN
        $data_tabel_semua['list_pengajuan'] = $this->surat_model->get_all_pengajuan();
        $view_tabel_semua = $this->load->view('pengajuan_table', $data_tabel_semua, TRUE);
        
        // 3. Siapkan data & view untuk TABEL TERVERIFIKASI
        // Pastikan fungsi get_verified_pengajuan() ada di model Anda
        $data_tabel_terverifikasi['list_pengajuan'] = $this->surat_model->get_verified_pengajuan(); 
        $view_tabel_terverifikasi = $this->load->view('terverifikasi_table', $data_tabel_terverifikasi, TRUE);
        
        // 4. GABUNGKAN kedua view tabel menjadi satu string HTML
        $template_data['table'] = $view_tabel_semua . $view_tabel_terverifikasi;
        
        // 5. Muat layout utama dengan semua data yang sudah dirakit
        $this->load->view('admin_view', $template_data);
    }

    public function proses_pengajuan()
    {
        $this->form_validation->set_rules('id_pendatang', 'Pendatang', 'required|numeric');
        $this->form_validation->set_rules('id_keperluan', 'Keperluan', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('pesan', 'Gagal mengajukan surat. Pastikan semua data yang wajib diisi sudah dipilih.');
        } else {
            // --- AWAL KODE BARU UNTUK NOMOR SURAT ---

            // 1. Ambil ID Pendatang dari form
            $id_pendatang = $this->input->post('id_pendatang');

            // 2. Buat semua komponen nomor surat sesuai pola
            $nomor_urut  = str_pad($id_pendatang, 3, '0', STR_PAD_LEFT); // Format ID jadi 3 digit, misal: 7 -> "007"
            $kode_statis = "SKSD/JB";
            $tanggal     = date('d'); // Format DD, misal: "17"
            $bulan       = date('m');   // Format MM, misal: "06"
            $tahun       = date('y');   // Format YY, misal: "25"

            // 3. Gabungkan semua komponen menjadi satu
            $nomor_surat_final = "{$nomor_urut}/{$kode_statis}/{$tanggal}/{$bulan}/{$tahun}";
            // Contoh hasil: "007/SKSD/JB/17/06/25"

            // --- AKHIR KODE BARU ---

            // Siapkan data untuk disimpan, SEKARANG TERMASUK nomor_surat
            $data_to_insert = [
                'id_pendatang'           => $id_pendatang,
                'id_keperluan'           => $this->input->post('id_keperluan'),
                'keperluan_lainnya_text' => $this->input->post('keperluan_lainnya_text'),
                'nomor_surat'            => $nomor_surat_final, // <-- NOMOR SURAT DISIMPAN
                'tanggal_pengajuan'      => date('Y-m-d H:i:s'),
                'status'                 => 'Menunggu Verifikasi'
            ];

            $insert = $this->db->insert('tbpengajuansurat', $data_to_insert);
            
            if ($insert) {
                $this->session->set_flashdata('pesan', 'Berhasil mengajukan surat pengantar!');
            } else {
                $this->session->set_flashdata('pesan', 'Terjadi kesalahan saat menyimpan data ke database.');
            }
        }
        
        // Kembalikan pengguna ke halaman tabel
        redirect('surat_kedatangan', 'refresh');
    }

    public function verifikasi_pengajuan($id)
    {
        // Ambil ID admin yang sedang login dari session
        // GANTI 'kodeDaftar' jika key session Anda berbeda
        $id_verifikator = $this->session->userdata('kodeDaftar');

        $data_update = [
            'status'             => 'Terverifikasi',
            'tanggal_verifikasi' => date('Y-m-d H:i:s'),
            'id_verifikator'     => $id_verifikator
        ];

        $this->db->where('id', $id);
        $this->db->update('tbpengajuansurat', $data_update);

        $this->session->set_flashdata('pesan', 'Satu pengajuan berhasil diverifikasi!');
        redirect('surat_kedatangan', 'refresh');
    }

    /**
     * Fungsi untuk menolak pengajuan.
     * Dipanggil dari tombol Tolak di tabel.
     */
    public function tolak_pengajuan($id)
    {
        // Ambil alasan penolakan dari parameter GET di URL
        $alasan = $this->input->get('alasan');

        // Ambil ID admin yang sedang login dari session
        // GANTI 'kodeDaftar' jika key session Anda berbeda
        $id_verifikator = $this->session->userdata('kodeDaftar');

        $data_update = [
            'status'              => 'Ditolak',
            'tanggal_verifikasi'  => date('Y-m-d H:i:s'),
            'id_verifikator'      => $id_verifikator,
            'catatan_penolakan'   => $alasan // Simpan alasan penolakan
        ];

        $this->db->where('id', $id);
        $this->db->update('tbpengajuansurat', $data_update);

        $this->session->set_flashdata('pesan', 'Satu pengajuan telah ditolak.');
        redirect('surat_kedatangan', 'refresh');
    }

    public function hapus_pengajuan($id)
    {
        // Perintah untuk menghapus baris dari tabel 'tbpengajuansurat'
        // di mana kolom 'id' cocok dengan $id yang dikirim dari URL.
        $this->db->where('id', $id);
        $this->db->delete('tbpengajuansurat');

        // Buat pesan feedback untuk ditampilkan ke pengguna
        $this->session->set_flashdata('pesan', 'Data pengajuan berhasil dihapus secara permanen.');
        
        // Kembalikan pengguna ke halaman tabel
        redirect('surat_kedatangan', 'refresh');
    }
}