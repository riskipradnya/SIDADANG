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
        // Fungsi index() Anda sudah benar, tidak perlu diubah.
        // Cukup pastikan tidak ada error di dalamnya.
        $data_form['pendatang_terverifikasi'] = $this->db->where('statusAktivasi', 'Terverifikasi')->order_by('nama', 'ASC')->get('tbpendatang')->result();
        $data_form['list_keperluan'] = $this->db->where('is_active', 1)->order_by('nama_keperluan', 'ASC')->get('tbkeperluansurat')->result();
        $data_tabel['list_pengajuan'] = $this->surat_model->get_all_pengajuan();
        
        $template_data['konten'] = $this->load->view('surat_kedatangan_view', $data_form, TRUE);
        $template_data['table'] = $this->load->view('tabel_pendatang', $data_tabel, TRUE);
        
        $this->load->view('admin_view', $template_data);
    }

    public function proses_pengajuan()
    {
        // Tidak perlu ada perubahan di sini, kode ini sudah benar.
        $this->form_validation->set_rules('id_pendatang', 'Pendatang', 'required|numeric');
        $this->form_validation->set_rules('id_keperluan', 'Keperluan', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Gagal mengajukan surat. Pastikan semua data yang wajib diisi sudah dipilih.</div>');
        } else {
            $data_to_insert = [
                'id_pendatang'          => $this->input->post('id_pendatang'),
                'id_keperluan'          => $this->input->post('id_keperluan'),
                'keperluan_lainnya_text'=> $this->input->post('keperluan_lainnya_text'),
                'tanggal_pengajuan'     => date('Y-m-d H:i:s'),
                'status'                => 'Menunggu Verifikasi'
            ];
            $insert = $this->db->insert('tbpengajuansurat', $data_to_insert);
            if ($insert) {
                $this->session->set_flashdata('pesan', '<div class="alert alert-success">Berhasil mengajukan surat pengantar! Data baru telah ditambahkan ke tabel.</div>');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert alert-danger">Terjadi kesalahan saat menyimpan data ke database.</div>');
            }
        }
        
        // 2. PASTIKAN BARIS INI DIEKSEKUSI
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