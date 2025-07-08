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

    public function index()
    {
        $data = [
            'title'   => 'Pusat Laporan',
            'list_pj' => $this->db->order_by('namaLengkap', 'ASC')->get('tbpj')->result()
        ];
        // Pastikan nama view ini benar: 'laporan/v_halaman_laporan.php' atau 'laporan/laporan_pendatang_view.php'
        $template_data['konten'] = $this->load->view('laporan/laporan_pendatang_view', $data, TRUE);
        $this->load->view('admin_view', $template_data);
    }

    public function generate_laporan_pendatang()
    {
        // 1. Ambil data filter
        $bulan_tahun = $this->input->post('bulan_tahun');
        $id_pj = $this->input->post('id_pj');

        // 2. Panggil model
        $data['laporan_data'] = $this->laporan_model->get_laporan_pendatang($bulan_tahun, $id_pj);

        // 3. Siapkan info filter
        $data['filter_info'] = [
            'bulan_tahun' => $bulan_tahun,
            'nama_pj'     => !empty($id_pj) ? $this->db->get_where('tbpj', ['kodeDaftar' => $id_pj])->row()->namaLengkap : 'Semua PJ'
        ];
        
        // 4. Load view template PDF ke dalam variabel
        $html = $this->load->view('laporan/v_template_pdf_pendatang', $data, TRUE);

        // 5. Buat instance Dompdf
        $options = new Options();
        $options->set('isRemoteEnabled', TRUE);
        $dompdf = new Dompdf($options);
        
        // 6. Proses Rendering
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // 7. Tampilkan PDF sebagai Preview
        $filename = 'Laporan_Pendatang_' . date('Y-m-d') . '.pdf';
        $dompdf->stream($filename, array('Attachment' => 0)); // <-- PERUBAHAN DI SINI
        exit();
    }
}