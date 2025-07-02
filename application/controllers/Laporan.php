<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Letakkan di atas Class: Memuat library Dompdf dan dependency-nya
require_once(APPPATH . 'libraries/dompdf/autoload.inc.php');
use Dompdf\Dompdf;
use Dompdf\Options;

class Laporan extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['validasi', 'laporan_model']);
        $this->load->helper('url');
        $this->load->library('session');
        $this->validasi->validasiakun();
    }

    /**
     * Menampilkan halaman utama pusat laporan (tidak berubah).
     */
    public function index()
    {
        $data = [
            'title'   => 'Pusat Laporan',
            'list_pj' => $this->db->order_by('namaLengkap', 'ASC')->get('tbpj')->result()
        ];
        // Anda mungkin menggunakan nama 'v_halaman_laporan.php' dari jawaban sebelumnya
        $template_data['konten'] = $this->load->view('laporan/laporan_pendatang_view', $data, TRUE);
        $this->load->view('admin_view', $template_data);
    }

    /**
     * FUNGSI YANG DIUBAH TOTAL:
     * Memproses filter, membuat PDF, dan memicu download.
     */
    public function generate_laporan_pendatang()
    {
        // 1. Ambil data filter dari form (tetap sama)
        $bulan_tahun = $this->input->post('bulan_tahun');
        $id_pj = $this->input->post('id_pj');

        // 2. Panggil model untuk mendapatkan data laporan (tetap sama)
        $data['laporan_data'] = $this->laporan_model->get_laporan_pendatang($bulan_tahun, $id_pj);

        // 3. Siapkan info filter untuk header PDF (tetap sama)
        $data['filter_info'] = [
            'bulan_tahun' => $bulan_tahun,
            'nama_pj'     => !empty($id_pj) ? $this->db->get_where('tbpj', ['kodeDaftar' => $id_pj])->row()->namaLengkap : 'Semua PJ'
        ];
        
        // --- PROSES PEMBUATAN PDF DIMULAI DI SINI ---

        // 4. Load view HTML yang berisi template PDF ke dalam sebuah variabel
        // Perhatikan nama file view-nya berbeda, kita akan buat di Langkah 2
        $html = $this->load->view('laporan/v_template_pdf_pendatang', $data, TRUE);

        // 5. Buat instance Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', TRUE); // Izinkan gambar dari URL luar jika ada
        $dompdf = new Dompdf($options);
        
        // 6. Muat HTML ke Dompdf
        $dompdf->loadHtml($html);

        // 7. Atur ukuran kertas dan orientasi (landscape lebih baik untuk tabel lebar)
        $dompdf->setPaper('A4', 'landscape');

        // 8. Render HTML menjadi PDF
        $dompdf->render();

        // 9. Buat nama file dan paksa browser untuk men-download
        $filename = 'Laporan_Pendatang_' . date('Y-m-d') . '.pdf';
        $dompdf->stream($filename, array('Attachment' => 1)); // Attachment => 1 untuk download
        exit();
    }
}