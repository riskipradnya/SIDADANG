<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_kedatangan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library(['session', 'form_validation']); // Tambahkan form_validation
        $this->load->helper('url');
        $this->load->model('surat_model'); 
    }

    public function index()
    {
        // ... (fungsi index Anda yang sudah ada, tidak perlu diubah) ...

        // Menyiapkan data untuk form
        $data_form['pendatang_terverifikasi'] = $this->db->where('statusAktivasi', 'Terverifikasi')->order_by('nama', 'ASC')->get('tbpendatang')->result();
        $data_form['list_keperluan'] = $this->db->where('is_active', 1)->order_by('nama_keperluan', 'ASC')->get('tbkeperluansurat')->result();
        
        // Menyiapkan data untuk tabel
        $data_tabel['list_pengajuan'] = $this->surat_model->get_all_pengajuan();
        
        // Menyiapkan pesan dari proses pengajuan (jika ada)
        $data_form['pesan'] = $this->session->flashdata('pesan');
        
        // Merakit View
        $template_data['konten'] = $this->load->view('surat_kedatangan_view', $data_form, TRUE);
        $template_data['table'] = $this->load->view('tabel_pendatang', $data_tabel, TRUE);
        
        $this->load->view('admin_view', $template_data);
    }


    /**
     * ===============================================================
     * INI FUNGSI BARU UNTUK MEMPROSES DATA DARI FORM (MENGHILANGKAN 404)
     * ===============================================================
     */
    public function proses_pengajuan()
    {
        // 1. Atur aturan validasi
        $this->form_validation->set_rules('id_pendatang', 'Pendatang', 'required|numeric');
        $this->form_validation->set_rules('id_keperluan', 'Keperluan', 'required|numeric');
        // 'keperluan_lainnya_text' tidak wajib, jadi tidak perlu divalidasi 'required'

        // 2. Jalankan validasi
        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kembalikan ke halaman form dengan pesan error
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Gagal mengajukan surat. Pastikan semua data yang wajib diisi sudah dipilih.</div>');
            redirect('surat_kedatangan');
        } else {
            // Jika validasi berhasil, lanjutkan proses
            // 3. Siapkan data untuk dimasukkan ke database
            $data_to_insert = [
                'id_pendatang'          => $this->input->post('id_pendatang'),
                'id_keperluan'          => $this->input->post('id_keperluan'),
                'keperluan_lainnya_text'=> $this->input->post('keperluan_lainnya_text'),
                'tanggal_pengajuan'     => date('Y-m-d H:i:s'), // Waktu saat ini
                'status'                => 'Menunggu Verifikasi' // Status default
            ];

            // 4. Masukkan data ke tabel 'tbpengajuansurat'
            $insert = $this->db->insert('tbpengajuansurat', $data_to_insert);

            // 5. Beri pesan feedback dan kembalikan ke halaman utama
            if ($insert) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Berhasil mengajukan surat pengantar! Silakan tunggu proses verifikasi.</div>');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Terjadi kesalahan saat menyimpan data ke database.</div>');
            }

            redirect('surat_kedatangan');
        }
    }
}