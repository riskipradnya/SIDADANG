<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pendatang extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if (!isset($this->db)) {
            $this->load->database();
        }
        $this->load->library(['form_validation', 'session', 'upload']);
        $this->load->helper(['url', 'form', 'file']);
    }

    /**
     * Mengambil data PJ yang terverifikasi beserta detail Kaling yang terhubung.
     */
    private function _get_pj_with_kaling_details()
    {
        $this->db->select('
            tbpj.kodeDaftar as pj_kodeDaftar,
            tbpj.namaLengkap as pj_namaLengkap,
            tbpj.NIK as pj_NIK,
            tbpj.id_kepala_lingkungan as kaling_id_at_pj, 
            tbkaling.namaLengkap as kaling_namaLengkap,
            tbkaling.NIK as kaling_NIK,  
            tbkaling.statusAktivasi as kaling_statusAktivasi 
        ');
        $this->db->from('tbpj');
        $this->db->join('tbkaling', 'tbkaling.kodeDaftar = tbpj.id_kepala_lingkungan', 'left');
        $this->db->where('tbpj.statusAktivasi', 'Terverifikasi');
        $this->db->order_by('tbpj.namaLengkap', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Menampilkan form pendataan pendatang.
     */
    public function index()
    {
        $data_view = [];
        $data_view['responsible_persons_list'] = $this->_get_pj_with_kaling_details();
        $data_view['konten'] = $this->load->view('pendatang_view', $data_view, TRUE);
        $this->load->view('admin_view', $data_view);
    }

    /**
     * Menyimpan data pendatang baru dari form.
     */
    public function simpan()
    {
        $this->form_validation->set_rules('nik', 'NIK', 'required|numeric|exact_length[16]|is_unique[tbpendatang.nik]',
            ['is_unique' => 'NIK ini sudah terdaftar.']
        );
        $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required');
        $this->form_validation->set_rules('no_hp', 'No Handphone', 'required|numeric');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('golongan_darah', 'Golongan Darah', 'required');
        $this->form_validation->set_rules('agama', 'Agama', 'required');
        $this->form_validation->set_rules('provinsi_asal', 'Provinsi Asal', 'required');
        $this->form_validation->set_rules('kabupaten_asal', 'Kabupaten/Kota Asal', 'required');
        $this->form_validation->set_rules('kecamatan_asal', 'Kecamatan Asal', 'required');
        $this->form_validation->set_rules('kelurahan_asal', 'Kelurahan/Desa Asal', 'required');
        $this->form_validation->set_rules('rt', 'RT Asal', 'required|numeric|max_length[3]');
        $this->form_validation->set_rules('rw', 'RW Asal', 'required|numeric|max_length[3]');
        $this->form_validation->set_rules('alamat_asal', 'Alamat Asal', 'required');
        $this->form_validation->set_rules('alamatSekarang', 'Alamat Domisili Sekarang', 'required');
        $this->form_validation->set_rules('latitude', 'Latitude', 'required');
        $this->form_validation->set_rules('longitude', 'Longitude', 'required');
        $this->form_validation->set_rules('tujuan', 'Tujuan Kedatangan', 'required');
        $this->form_validation->set_rules('tgl_masuk', 'Tanggal Masuk', 'required');
        $this->form_validation->set_rules('id_penanggung_jawab', 'Penanggung Jawab / Kepala Lingkungan', 'required|numeric|callback_validate_pj_selection');
        $this->form_validation->set_rules('wilayah', 'Wilayah (Banjar/Lingkungan)', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            $data_view_error = [];
            $data_view_error['responsible_persons_list'] = $this->_get_pj_with_kaling_details();
            $data_view_error['konten'] = $this->load->view('pendatang_view', $data_view_error, TRUE);
            $this->load->view('admin_view', $data_view_error);
        } else {
            $config['upload_path']   = './uploads/pendatang/';
            $config['allowed_types'] = 'gif|jpg|jpeg|png';
            $config['encrypt_name']  = TRUE;
            $config['max_size']      = 2048;

            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }
            $this->upload->initialize($config);

            $nama_file_diri = NULL;
            $nama_file_ktp  = NULL;
            $upload_error = FALSE;
            $error_msg = '';

            if (!empty($_FILES['foto_diri']['name'])) {
                if (!$this->upload->do_upload('foto_diri')) {
                    $error_msg .= 'Gagal mengupload Foto Diri: ' . $this->upload->display_errors('', '<br/>');
                    $upload_error = TRUE;
                } else {
                    $foto_diri_data = $this->upload->data();
                    $nama_file_diri = $foto_diri_data['file_name'];
                }
            } else {
                $error_msg .= 'Foto Diri wajib diisi.<br/>';
                $upload_error = TRUE;
            }

            if (!$upload_error && !empty($_FILES['foto_ktp']['name'])) {
                if (!$this->upload->do_upload('foto_ktp')) {
                    $error_msg .= 'Gagal mengupload Foto KTP: ' . $this->upload->display_errors('', '<br/>');
                    $upload_error = TRUE;
                    if ($nama_file_diri && file_exists($config['upload_path'] . $nama_file_diri)) {
                        @unlink($config['upload_path'] . $nama_file_diri);
                    }
                } else {
                    $foto_ktp_data = $this->upload->data();
                    $nama_file_ktp = $foto_ktp_data['file_name'];
                }
            } elseif (!$upload_error && empty($_FILES['foto_ktp']['name'])) {
                $error_msg .= 'Foto KTP wajib diisi.<br/>';
                $upload_error = TRUE;
                if ($nama_file_diri && file_exists($config['upload_path'] . $nama_file_diri)) {
                    @unlink($config['upload_path'] . $nama_file_diri);
                }
            }

            if ($upload_error) {
                $this->session->set_flashdata('error', $error_msg);
                $data_view_upload_error = [];
                $data_view_upload_error['responsible_persons_list'] = $this->_get_pj_with_kaling_details();
                $data_view_upload_error['konten'] = $this->load->view('pendatang_view', $data_view_upload_error, TRUE);
                $this->load->view('admin_view', $data_view_upload_error);
                return;
            }

            $selected_pj_id = $this->input->post('id_penanggung_jawab');
            $kaling_id_for_pendatang = NULL;

            if (!empty($selected_pj_id)) {
                $pj_details = $this->db->get_where('tbpj', ['kodeDaftar' => $selected_pj_id, 'statusAktivasi' => 'Terverifikasi'])->row();
                if ($pj_details) {
                    $kaling_id_for_pendatang = $pj_details->id_kepala_lingkungan;
                }
            }

            $data_pendatang = [
                'nik'                   => $this->input->post('nik'),
                'nama'                  => $this->input->post('nama'),
                'no_hp'                 => $this->input->post('no_hp'),
                'tempat_lahir'          => $this->input->post('tempat_lahir'),
                'tgl_lahir'             => $this->input->post('tgl_lahir'),
                'jenis_kelamin'         => $this->input->post('jenis_kelamin'),
                'golongan_darah'        => $this->input->post('golongan_darah'),
                'agama'                 => $this->input->post('agama'),
                'provinsi_asal'         => $this->input->post('provinsi_asal'),
                'kabupaten_asal'        => $this->input->post('kabupaten_asal'),
                'kecamatan_asal'        => $this->input->post('kecamatan_asal'),
                'kelurahan_asal'        => $this->input->post('kelurahan_asal'),
                'rt'                    => $this->input->post('rt'),
                'rw'                    => $this->input->post('rw'),
                'alamat_asal'           => $this->input->post('alamat_asal'),
                'alamat_sekarang'       => $this->input->post('alamatSekarang'),
                'latitude'              => $this->input->post('latitude'),
                'longitude'             => $this->input->post('longitude'),
                'tujuan'                => $this->input->post('tujuan'),
                'tgl_masuk'             => $this->input->post('tgl_masuk'),
                'tgl_keluar'            => !empty($this->input->post('tgl_keluar')) ? $this->input->post('tgl_keluar') : NULL,
                'id_penanggung_jawab'   => $selected_pj_id,
                'id_kepala_lingkungan'  => $kaling_id_for_pendatang,
                'wilayah'               => $this->input->post('wilayah'),
                'foto_diri'             => $nama_file_diri,
                'foto_ktp'              => $nama_file_ktp,
                'statusAktivasi'        => 'Belum Terverifikasi'
            ];

            $sukses = $this->db->insert('tbpendatang', $data_pendatang);

            if ($sukses) {
                $this->session->set_flashdata('success', 'Data pendatang berhasil disimpan dan menunggu verifikasi.');
                redirect('pendatang');
            } else {
                if ($nama_file_diri && file_exists($config['upload_path'] . $nama_file_diri)) {
                    @unlink($config['upload_path'] . $nama_file_diri);
                }
                if ($nama_file_ktp && file_exists($config['upload_path'] . $nama_file_ktp)) {
                    @unlink($config['upload_path'] . $nama_file_ktp);
                }
                $this->session->set_flashdata('error', 'Gagal menyimpan data pendatang ke database. Silakan coba lagi.');
                $data_view_db_error = [];
                $data_view_db_error['responsible_persons_list'] = $this->_get_pj_with_kaling_details();
                $data_view_db_error['konten'] = $this->load->view('pendatang_view', $data_view_db_error, TRUE);
                $this->load->view('admin_view', $data_view_db_error);
            }
        }
    }

    /**
     * Callback function untuk validasi pilihan PJ di dropdown.
     */
    public function validate_pj_selection($pj_id)
    {
        if (empty($pj_id) || !is_numeric($pj_id) || (int)$pj_id <= 0) {
            $this->form_validation->set_message('validate_pj_selection', 'Field {field} harus dipilih dengan benar.');
            return FALSE;
        }
        
        $this->db->select('tbpj.kodeDaftar, tbpj.id_kepala_lingkungan, tbkaling.statusAktivasi as kaling_statusAktivasi');
        $this->db->from('tbpj');
        $this->db->join('tbkaling', 'tbkaling.kodeDaftar = tbpj.id_kepala_lingkungan', 'left');
        $this->db->where('tbpj.kodeDaftar', (int)$pj_id);
        $this->db->where('tbpj.statusAktivasi', 'Terverifikasi');
        $pj_data = $this->db->get()->row();

        if (!$pj_data) {
            $this->form_validation->set_message('validate_pj_selection', 'Penanggung Jawab yang dipilih tidak valid atau tidak terverifikasi.');
            return FALSE;
        }
        if (empty($pj_data->id_kepala_lingkungan)) {
            $this->form_validation->set_message('validate_pj_selection', 'Penanggung Jawab yang dipilih tidak memiliki Kepala Lingkungan yang terhubung.');
            return FALSE;
        }
        if ($pj_data->kaling_statusAktivasi !== 'Terverifikasi') {
            $this->form_validation->set_message('validate_pj_selection', 'Kepala Lingkungan yang terkait dengan Penanggung Jawab ini tidak terverifikasi.');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Menampilkan daftar semua pendatang.
     */
    public function daftar()
    {
        $this->db->select('
            tp.*, 
            pj.namaLengkap as pj_nama_lengkap, 
            pj.NIK as pj_nik,
            kl.namaLengkap as kaling_nama_lengkap,
            kl.NIK as kaling_nik
        ');
        $this->db->from('tbpendatang tp');
        $this->db->join('tbpj pj', 'pj.kodeDaftar = tp.id_penanggung_jawab', 'left');
        $this->db->join('tbkaling kl', 'kl.kodeDaftar = tp.id_kepala_lingkungan', 'left');
        $this->db->order_by('tp.id', 'DESC');
        
        $hasil_query = $this->db->get();
        $data['pendatang_data'] = $hasil_query->result();

        $data['konten'] = $this->load->view('pendatang_table', $data, TRUE);
        $this->load->view('admin_view', $data);
    }

    /**
     * Menampilkan detail data pendatang.
     */
    public function detail($id = NULL)
    {
        if ($id === NULL || !is_numeric($id) || (int)$id <= 0) {
            $this->session->set_flashdata('error', 'Permintaan tidak valid, ID pendatang tidak ditemukan atau formatnya salah.');
            redirect('pendatang/daftar');
            return;
        }

        $this->db->select('
            tp.*, 
            pj.namaLengkap as pj_namaLengkap, 
            pj.NIK as pj_NIK,
            kl.namaLengkap as kaling_namaLengkap,
            kl.NIK as kaling_NIK
        ');
        $this->db->from('tbpendatang tp');
        $this->db->join('tbpj pj', 'pj.kodeDaftar = tp.id_penanggung_jawab', 'left');
        $this->db->join('tbkaling kl', 'kl.kodeDaftar = tp.id_kepala_lingkungan', 'left');
        $this->db->where('tp.id', (int)$id);
        $pendatang_detail = $this->db->get()->row();

        if (!$pendatang_detail) {
            $this->session->set_flashdata('error', 'Data pendatang dengan ID tersebut tidak ditemukan.');
            redirect('pendatang/daftar');
            return;
        }

        $data_to_view['pendatang'] = $pendatang_detail;
        $data_layout['konten'] = $this->load->view('pendatang_detail_view', $data_to_view, TRUE);
        $this->load->view('admin_view', $data_layout);
    }

    /**
     * Menghasilkan dan menampilkan/mengunduh Surat Keterangan Domisili dalam format PDF.
     */
    public function cetak_surat_domisili($id_pendatang = NULL)
    {
        if ($id_pendatang === NULL || !is_numeric($id_pendatang) || (int)$id_pendatang <= 0) {
            $this->session->set_flashdata('error', 'Permintaan tidak valid untuk mencetak surat.');
            redirect('pendatang/daftar');
            return;
        }

        $this->db->select('
            tp.*, 
            pj.namaLengkap as pj_namaLengkap, 
            kl.namaLengkap as kaling_namaLengkap
        ');
        $this->db->from('tbpendatang tp');
        $this->db->join('tbpj pj', 'pj.kodeDaftar = tp.id_penanggung_jawab', 'left');
        $this->db->join('tbkaling kl', 'kl.kodeDaftar = tp.id_kepala_lingkungan', 'left');
        $this->db->where('tp.id', (int)$id_pendatang);
        $pendatang = $this->db->get()->row();

        if (!$pendatang) {
            $this->session->set_flashdata('error', 'Data pendatang tidak ditemukan untuk dicetak.');
            redirect('pendatang/daftar');
            return;
        }

        if ($pendatang->statusAktivasi !== 'Terverifikasi') {
            $this->session->set_flashdata('error', 'Surat Keterangan Domisili hanya dapat dicetak untuk pendatang yang sudah terverifikasi.');
            redirect('pendatang/detail/' . $id_pendatang);
            return;
        }

        $nomor_urut_surat = str_pad($pendatang->id, 3, '0', STR_PAD_LEFT);
        // $bulan_romawi = $this->_get_roman_month(date('n')); // Baris ini tidak diperlukan lagi
        $bulan_angka = date('m'); // 'm' untuk bulan dengan leading zero (01, 02, ..., 12)
                                  // atau gunakan date('n') untuk bulan tanpa leading zero (1, 2, ..., 12)
        $nomor_surat = $nomor_urut_surat . "/SKSD/JB/" . date('d') . "/" . $bulan_angka . "/" . date('y');
        
        $tgl_lahir_pendatang_formatted = !empty($pendatang->tgl_lahir) && $pendatang->tgl_lahir != '0000-00-00' 
                               ? $this->_tgl_indonesia($pendatang->tgl_lahir)
                               : '-';

        $data_surat = [
            'pendatang'                 => $pendatang,
            'tgl_lahir_pendatang'       => $tgl_lahir_pendatang_formatted,
            'nomor_surat'               => $nomor_surat,
            'tanggal_surat'             => $this->_tgl_indonesia(date('Y-m-d')),
            'pj_nama'                   => !empty($pendatang->pj_namaLengkap) ? $pendatang->pj_namaLengkap : '(Data PJ Tidak Ditemukan)',
            'nama_pejabat'              => '(Wayan Kardiyasa, S.Pd)', // GANTI DENGAN NAMA PERBEKEL
            'nip_pejabat'               => '(NIP PERBEKEL JIKA ADA)',      // GANTI DENGAN NIP ATAU KOSONGKAN
            'jabatan_pejabat'           => 'Perbekel Desa Jimbaran',
            'keperluan_surat'           => 'kelengkapan dokumen administrasi internal'
        ];

        $html = $this->load->view('template_surat_domisili_pdf', $data_surat, TRUE);

        require_once APPPATH . 'libraries/dompdf/autoload.inc.php';
        
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', TRUE);
        $options->setChroot(FCPATH); 
        
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $filename = "Surat_Domisili_" . str_replace(' ', '_', $pendatang->nama) . "_" . $pendatang->nik . ".pdf";
        $dompdf->stream($filename, array("Attachment" => 0)); // Attachment 0 = tampil inline
        exit();
    }

    /**
     * Helper untuk mengubah angka bulan menjadi Romawi.
     */
    private function _get_roman_month($month_number) {
        if ($month_number < 1 || $month_number > 12) {
            return date('m'); // Default ke angka jika diluar range
        }
        $romans = array("I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
        return $romans[$month_number - 1];
    }
    
    /**
     * Helper untuk format tanggal Indonesia.
     */
    private function _tgl_indonesia($tanggal_mysql){
        if(empty($tanggal_mysql) || $tanggal_mysql == '0000-00-00') return '-';
        $bulan_map = array (
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
            7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        );
        $parts = explode('-', $tanggal_mysql);
        if(count($parts) != 3) return $tanggal_mysql; // Kembalikan original jika format salah
        return $parts[2] . ' ' . $bulan_map[ (int)$parts[1] ] . ' ' . $parts[0];
    }

    /**
     * Menghapus data pendatang berdasarkan ID.
     */
    public function hapus($id = NULL)
    {
        if ($id === NULL || !is_numeric($id) || (int)$id <= 0) {
            $this->session->set_flashdata('error', 'Permintaan tidak valid, ID tidak ditemukan atau tidak valid.');
            redirect('pendatang/daftar');
            return;
        }
        $pendatang = $this->db->get_where('tbpendatang', ['id' => $id])->row();
        if (!$pendatang) {
            $this->session->set_flashdata('error', 'Data pendatang tidak ditemukan.');
            redirect('pendatang/daftar');
            return;
        }
        $upload_path = './uploads/pendatang/';
        $file_deleted_count = 0;
        if (!empty($pendatang->foto_diri) && file_exists($upload_path . $pendatang->foto_diri)) {
            if(@unlink($upload_path . $pendatang->foto_diri)) $file_deleted_count++;
        }
        if (!empty($pendatang->foto_ktp) && file_exists($upload_path . $pendatang->foto_ktp)) {
            if(@unlink($upload_path . $pendatang->foto_ktp)) $file_deleted_count++;
        }
        $this->db->where('id', $id);
        $sukses_hapus_db = $this->db->delete('tbpendatang');
        if ($sukses_hapus_db) {
            $this->session->set_flashdata('success', 'Data pendatang an. ' . htmlspecialchars($pendatang->nama) . ' berhasil dihapus (Termasuk '.$file_deleted_count.' file terkait).');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data pendatang dari database.');
        }
        redirect('pendatang/daftar');
    }

    /**
     * Mengubah statusAktivasi pendatang menjadi "Terverifikasi".
     */
    public function verifikasi($id = NULL)
    {
        if ($id === NULL || !is_numeric($id) || (int)$id <= 0) {
            $this->session->set_flashdata('error', 'Permintaan tidak valid, ID tidak ditemukan atau tidak valid untuk verifikasi.');
            redirect('pendatang/daftar');
            return;
        }
        $pendatang = $this->db->get_where('tbpendatang', ['id' => $id])->row();
        if (!$pendatang) {
            $this->session->set_flashdata('error', 'Data pendatang tidak ditemukan.');
            redirect('pendatang/daftar');
            return;
        }
        if ($pendatang->statusAktivasi == 'Belum Terverifikasi') {
            $data_update = ['statusAktivasi' => 'Terverifikasi'];
            $this->db->where('id', $id);
            $sukses_update = $this->db->update('tbpendatang', $data_update);
            if ($sukses_update) {
                $this->session->set_flashdata('success', 'Data pendatang an. ' . htmlspecialchars($pendatang->nama) . ' berhasil diverifikasi.');
            } else {
                $this->session->set_flashdata('error', 'Gagal memverifikasi data pendatang.');
            }
        } else {
            $this->session->set_flashdata('info', 'Data pendatang an. ' . htmlspecialchars($pendatang->nama) . ' sudah memiliki status "' . htmlspecialchars($pendatang->statusAktivasi) . '". Tidak ada tindakan dilakukan.');
        }
        redirect('pendatang/daftar');
    }

        public function tolak_verifikasi_dengan_alasan($id_pendatang_url = NULL)
    {
        // Pastikan ini adalah POST request
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            $this->session->set_flashdata('error', 'Akses tidak diizinkan.');
            redirect('pendatang/daftar');
            return;
        }

        $id_pendatang = $this->input->post('id_pendatang_tolak'); // Ambil ID dari hidden input
        $alasan_penolakan = trim($this->input->post('alasan_penolakan'));

        // Validasi ID dan alasan di sisi server
        if (empty($id_pendatang) || !is_numeric($id_pendatang) || (int)$id_pendatang <= 0) {
            $this->session->set_flashdata('error', 'ID Pendatang tidak valid.');
            redirect('pendatang/daftar');
            return;
        }
        if (empty($alasan_penolakan)) {
            $this->session->set_flashdata('error', 'Alasan penolakan wajib diisi.');
            // Redirect kembali ke daftar, atau bisa juga ke detail pendatang jika ingin pengguna mencoba lagi dari sana
            redirect('pendatang/daftar'); 
            return;
        }

        // Cek apakah data pendatang ada
        $pendatang = $this->db->get_where('tbpendatang', ['id' => (int)$id_pendatang])->row();
        if (!$pendatang) {
            $this->session->set_flashdata('error', 'Data pendatang tidak ditemukan.');
            redirect('pendatang/daftar');
            return;
        }

        // Hanya tolak jika statusnya masih "Belum Terverifikasi"
        if ($pendatang->statusAktivasi == 'Belum Terverifikasi') {
            $data_update = [
                'statusAktivasi' => 'Verifikasi Ditolak',
                'alasan_penolakan' => $alasan_penolakan // Simpan alasan ke database
            ];
            $this->db->where('id', (int)$id_pendatang);
            $sukses_update = $this->db->update('tbpendatang', $data_update);

            if ($sukses_update) {
                $this->session->set_flashdata('success', 'Verifikasi data pendatang an. ' . htmlspecialchars($pendatang->nama) . ' berhasil ditolak dengan alasan tersimpan.');
            } else {
                $this->session->set_flashdata('error', 'Gagal menolak verifikasi data pendatang.');
            }
        } else {
             $this->session->set_flashdata('info', 'Data pendatang an. ' . htmlspecialchars($pendatang->nama) . ' sudah memiliki status "' . htmlspecialchars($pendatang->statusAktivasi) . '". Tidak ada tindakan penolakan dilakukan.');
        }
        redirect('pendatang/daftar');
    }

    public function proses_pengajuan()
    {
        // Pastikan ini adalah POST request
        if ($this->input->server('REQUEST_METHOD') !== 'POST') {
            show_error('Akses tidak diizinkan.', 403);
            return;
        }

        // 1. Ambil data dari form
        $id_pendatang = $this->input->post('id_pendatang');
        $keperluan = $this->input->post('keperluan');
        
        // Jika keperluan adalah 'lainnya', ambil teksnya
        if ($keperluan === 'lainnya') {
            $keperluan_final = $this->input->post('keperluan_lainnya_text');
        } else {
            // Ubah value menjadi teks yang lebih deskriptif
            $map_keperluan = [
                'domisili' => 'Surat Keterangan Domisili',
                'kerja' => 'Surat Pengantar Kerja',
                'usaha' => 'Surat Keterangan Usaha'
            ];
            $keperluan_final = $map_keperluan[$keperluan] ?? 'Keperluan Tidak Valid';
        }


        // Validasi input
        if (empty($id_pendatang) || empty($keperluan_final)) {
            $this->session->set_flashdata('error', 'Data tidak lengkap. Silakan pilih pendatang dan keperluan surat.');
            redirect('surat_kedatangan');
            return;
        }


        // 2. Logika selanjutnya: Pilih template dan cetak PDF
        // Di sini Anda bisa menggunakan IF atau SWITCH untuk menentukan template mana yang akan digunakan
        
        switch ($keperluan) {
            case 'domisili':
                // Jika keperluan adalah domisili, panggil method cetak yang sudah ada
                // Ini me-redirect proses ke controller Pendatang, method cetak_surat_domisili
                redirect('pendatang/cetak_surat_domisili/' . $id_pendatang);
                break;
            
            case 'kerja':
                // TODO: Buat logic untuk mencetak surat pengantar kerja
                // Contoh: $this->cetak_surat_kerja($id_pendatang);
                echo "Fungsi untuk cetak 'Surat Pengantar Kerja' belum dibuat. ID Pendatang: " . $id_pendatang;
                break;

            case 'usaha':
                // TODO: Buat logic untuk mencetak surat keterangan usaha
                // Contoh: $this->cetak_surat_usaha($id_pendatang);
                echo "Fungsi untuk cetak 'Surat Keterangan Usaha' belum dibuat. ID Pendatang: " . $id_pendatang;
                break;
            
            case 'lainnya':
                // TODO: Buat logic untuk mencetak surat dengan keperluan custom
                // Contoh: $this->cetak_surat_lainnya($id_pendatang, $keperluan_final);
                echo "Fungsi untuk cetak surat '" . htmlspecialchars($keperluan_final) . "' belum dibuat. ID Pendatang: " . $id_pendatang;
                break;
                
            default:
                $this->session->set_flashdata('error', 'Jenis surat tidak valid.');
                redirect('surat_kedatangan');
                break;
        }
    }
}