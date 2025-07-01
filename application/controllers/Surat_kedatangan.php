<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Surat_kedatangan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        
        // Memuat semua library, helper, dan model yang dibutuhkan
        $this->load->helper('url'); 
        $this->load->library(['session', 'form_validation']);
        $this->load->model('surat_model'); 

        $this->load->model('validasi');
        $this->validasi->validasiakun();
    }

    public function index()
    {
        // 1. Siapkan data untuk Form Pengajuan
        $data_form['pendatang_terverifikasi'] = $this->db->where('statusAktivasi', 'Terverifikasi')->order_by('nama', 'ASC')->get('tbpendatang')->result();
        $data_form['list_keperluan'] = $this->db->where('is_active', 1)->order_by('nama_keperluan', 'ASC')->get('tbkeperluansurat')->result();
        $template_data['konten'] = $this->load->view('surat_kedatangan_view', $data_form, TRUE);

        // 2. Siapkan data untuk Tabel Semua Pengajuan
        $data_tabel_semua['list_pengajuan'] = $this->surat_model->get_all_pengajuan();
        $view_tabel_semua = $this->load->view('pengajuan_table', $data_tabel_semua, TRUE);
        
        // 3. Siapkan data untuk Tabel Terverifikasi
        $data_tabel_terverifikasi['list_pengajuan'] = $this->surat_model->get_verified_pengajuan(); 
        $view_tabel_terverifikasi = $this->load->view('terverifikasi_table', $data_tabel_terverifikasi, TRUE);
        
        // 4. Gabungkan kedua view tabel
        $template_data['table'] = $view_tabel_semua . $view_tabel_terverifikasi;
        
        // 5. Muat layout utama dengan semua data
        $this->load->view('admin_view', $template_data);
    }

    public function proses_pengajuan()
    {
        $this->form_validation->set_rules('id_pendatang', 'Pendatang', 'required|numeric');
        $this->form_validation->set_rules('id_keperluan', 'Keperluan', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('pesan', 'Gagal mengajukan surat. Pastikan semua data yang wajib diisi sudah dipilih.');
        } else {
            // --- Pembuatan Nomor Surat Otomatis ---
            $id_pendatang = $this->input->post('id_pendatang');
            $nomor_urut   = str_pad($id_pendatang, 3, '0', STR_PAD_LEFT);
            $kode_statis  = "SKSD/JB";
            $tanggal      = date('d');
            $bulan        = date('m');
            $tahun        = date('y');
            $nomor_surat_final = "{$nomor_urut}/{$kode_statis}/{$tanggal}/{$bulan}/{$tahun}";

            // Siapkan data untuk disimpan ke tbpengajuansurat
            $data_to_insert = [
                'id_pendatang'        => $id_pendatang,
                'id_keperluan'        => $this->input->post('id_keperluan'),
                // 'keperluan_lainnya_text' tidak diisi sesuai permintaan
                'nomor_surat'         => $nomor_surat_final,
                'tanggal_pengajuan'   => date('Y-m-d H:i:s'),
                'status'              => 'Menunggu Verifikasi'
            ];

            $insert = $this->db->insert('tbpengajuansurat', $data_to_insert);
            
            if ($insert) {
                $this->session->set_flashdata('pesan', 'Berhasil mengajukan surat pengantar!');
            } else {
                $this->session->set_flashdata('pesan', 'Terjadi kesalahan saat menyimpan data ke database.');
            }
        }
        
        redirect('surat_kedatangan', 'refresh');
    }

    public function verifikasi_pengajuan($id)
    {
        $id_verifikator = $this->session->userdata('kodeDaftar'); // Ganti 'kodeDaftar' jika key session Anda berbeda

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

    public function tolak_pengajuan($id)
    {
        $alasan = $this->input->get('alasan');
        $id_verifikator = $this->session->userdata('kodeDaftar'); // Ganti 'kodeDaftar' jika key session Anda berbeda

        $data_update = [
            'status'              => 'Ditolak',
            'tanggal_verifikasi'  => date('Y-m-d H:i:s'),
            'id_verifikator'      => $id_verifikator,
            'catatan_penolakan'   => $alasan
        ];

        $this->db->where('id', $id);
        $this->db->update('tbpengajuansurat', $data_update);

        $this->session->set_flashdata('pesan', 'Satu pengajuan telah ditolak.');
        redirect('surat_kedatangan', 'refresh');
    }

    public function hapus_pengajuan($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('tbpengajuansurat');

        $this->session->set_flashdata('pesan', 'Data pengajuan berhasil dihapus secara permanen.');
        
        redirect('surat_kedatangan', 'refresh');
    }

    /**
     * Fungsi untuk menangani penambahan tipe keperluan baru dari form modal.
     */
    public function tambah_keperluan()
    {
        // Atur aturan validasi
        // 'required' -> tidak boleh kosong
        // 'trim' -> hapus spasi di awal dan akhir
        // 'is_unique' -> pastikan nama keperluan belum ada di database
        $this->form_validation->set_rules(
            'nama_keperluan', 
            'Nama Keperluan', 
            'required|trim|is_unique[tbkeperluansurat.nama_keperluan]',
            [
                'is_unique' => 'Nama keperluan ini sudah ada di dalam sistem.'
            ]
        );

        if ($this->form_validation->run() == FALSE) {
            // Jika validasi gagal, kirim pesan error kembali
            $this->session->set_flashdata('error_keperluan', validation_errors());
        } else {
            // Jika validasi berhasil, siapkan data untuk disimpan
            $nama_keperluan_baru = $this->input->post('nama_keperluan');
            
            $data = [
                'nama_keperluan' => $nama_keperluan_baru,
                'is_active'      => 1 // Keperluan baru langsung diaktifkan
                // Kolom 'template_file' akan otomatis NULL karena tidak kita sertakan
            ];

            // Simpan data ke database
            $this->db->insert('tbkeperluansurat', $data);

            // Kirim pesan sukses
            $this->session->set_flashdata('pesan', 'Tipe keperluan baru "' . $nama_keperluan_baru . '" berhasil ditambahkan!');
        }

        // Kembalikan pengguna ke halaman formulir utama
        redirect('surat_kedatangan');
    }

    /**
     * Fungsi untuk mencetak surat domisili berdasarkan ID Pengajuan.
     * Kode ini telah diperbarui untuk mengambil nama keperluan secara dinamis.
     */
    public function cetak_surat($id_pengajuan = NULL)
    {
        // 1. Validasi Input ID
        if ($id_pengajuan === NULL || !is_numeric($id_pengajuan) || (int)$id_pengajuan <= 0) {
            $this->session->set_flashdata('pesan', 'Permintaan tidak valid untuk mencetak surat.');
            redirect('surat_kedatangan');
            return;
        }

        // 2. Query untuk mengambil data gabungan (termasuk nama keperluan)
        $this->db->select('
            ps.*, 
            p.*,  
            pj.namaLengkap as pj_namaLengkap,
            k.nama_keperluan
        ');
        $this->db->from('tbpengajuansurat ps');
        $this->db->join('tbpendatang p', 'ps.id_pendatang = p.id', 'inner');
        $this->db->join('tbpj pj', 'p.id_penanggung_jawab = pj.kodeDaftar', 'left');
        $this->db->join('tbkeperluansurat k', 'ps.id_keperluan = k.id', 'left'); // Join ke tabel keperluan
        $this->db->where('ps.id', (int)$id_pengajuan);
        $pengajuan = $this->db->get()->row();

        // 3. Cek apakah data pengajuan ditemukan
        if (!$pengajuan) {
            $this->session->set_flashdata('pesan', 'Data pengajuan surat tidak ditemukan.');
            redirect('surat_kedatangan');
            return;
        }

        // 4. Cek Status, hanya yang 'Terverifikasi' boleh dicetak
        if ($pengajuan->status !== 'Terverifikasi') {
            $this->session->set_flashdata('pesan', 'Surat hanya dapat dicetak untuk pengajuan yang sudah terverifikasi.');
            redirect('surat_kedatangan');
            return;
        }

        // 5. Format data tanggal lahir pendatang
        $tgl_lahir_pendatang_formatted = $this->_tgl_indonesia($pengajuan->tgl_lahir);

        // 6. Siapkan semua data yang akan dikirim ke view PDF
        $data_surat = [
            'pendatang'             => $pengajuan,
            'tgl_lahir_pendatang'   => $tgl_lahir_pendatang_formatted,
            'nomor_surat'           => $pengajuan->nomor_surat,
            'tanggal_surat'         => $this->_tgl_indonesia(date('Y-m-d')),
            'pj_nama'               => !empty($pengajuan->pj_namaLengkap) ? $pengajuan->pj_namaLengkap : '',
            'nama_pejabat'          => '(Wayan Kardiyasa, S.Pd)',
            'nip_pejabat'           => '(NIP PERBEKEL JIKA ADA)',
            'jabatan_pejabat'       => 'Perbekel Desa Jimbaran',
            'keperluan_surat'       => !empty($pengajuan->nama_keperluan) ? $pengajuan->nama_keperluan : '(keperluan tidak disebutkan)'
        ];

        // 7. Proses pembuatan PDF dengan Dompdf
        $html = $this->load->view('template_surat_domisili_pdf', $data_surat, TRUE);

        require_once APPPATH . 'libraries/dompdf/autoload.inc.php';
        
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', TRUE);
        $options->setChroot(FCPATH); 
        
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = "Surat_Domisili_" . str_replace(' ', '_', $pengajuan->nama) . "_" . $pengajuan->nik . ".pdf";
        
        $dompdf->stream($filename, ["Attachment" => 0]); 
        exit();
    }
    
    /**
     * Helper function untuk mengubah format tanggal Y-m-d menjadi format Indonesia.
     */
    private function _tgl_indonesia($tanggal)
    {
        if(empty($tanggal) || $tanggal == '0000-00-00') {
            return '-';
        }
        $bulan = [ 1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember' ];
        $pecahkan = explode('-', $tanggal);
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }
}