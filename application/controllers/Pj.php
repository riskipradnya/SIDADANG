<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pj extends CI_Controller
{
    // Asumsikan ID Kaling default adalah 14. Ganti sesuai kebutuhan Anda.
    const DEFAULT_KALING_ID = 14; 

    public function __construct()
    {
        parent::__construct();
        // Memuat semua library & helper yang dibutuhkan di awal
        $this->load->model('validasi');
        $this->load->library(['form_validation', 'session', 'upload']);
        $this->load->helper(['url', 'form']);
        
        // Menjalankan validasi login untuk setiap fungsi di controller ini
        $this->validasi->validasiakun(); 
    }

    /**
     * Menampilkan halaman utama manajemen Penanggung Jawab (PJ)
     * yang berisi form input dan tabel data PJ.
     */
    public function index()
    {
        $datalist['hasil'] = $this->tampildata(); 
        $data['konten'] = $this->load->view('pj_view', '', TRUE); 
        $data['table'] = $this->load->view('pj_table', $datalist, TRUE); 
        $this->load->view('admin_view', $data);
    }

    /**
     * Mengubah status aktivasi PJ menjadi "Terverifikasi".
     */
    // File: application/controllers/Pj.php (atau yang sejenis)

    public function verifikasidata_pj($kodeDaftar)
    {
        // Ambil data dari 'tbpj'
        $akun = $this->db->get_where('tbpj', ['kodeDaftar' => $kodeDaftar])->row();

        if (!$akun) { /* ... handle error ... */ }

        // Update status di 'tbpj'
        $this->db->where('kodeDaftar', $kodeDaftar);
        $this->db->update('tbpj', ['statusAktivasi' => 'Terverifikasi']);

        // Siapkan data login (sama persis)
        $data_login = array(
            'NIK'         => $akun->NIK,
            'Password'    => $akun->password,
            'NamaLengkap' => $akun->namaLengkap,
            'Level'       => $akun->jenisAkun
        );

        // Cek dan Insert/Update ke tblogin (sama persis)
        $cek_tblogin = $this->db->get_where('tblogin', ['NIK' => $akun->NIK])->num_rows();
        if ($cek_tblogin == 0) {
            $this->db->insert('tblogin', $data_login);
        } else {
            $this->db->where('NIK', $akun->NIK);
            $this->db->update('tblogin', $data_login);
        }

        $this->session->set_flashdata('pesan', 'Akun PJ berhasil diverifikasi!');
        redirect('pj', 'refresh'); // Arahkan ke halaman admin PJ
    }

    /**
     * Memproses data dari form untuk menyimpan PJ baru atau mengupdate PJ yang ada.
     * Ini adalah fungsi utama yang menangani semua logika form.
     */
    public function simpandata()
    {
        // 1. Mengatur Aturan Validasi untuk semua field dari view
        $this->form_validation->set_rules('no_kk', 'No. Kartu Keluarga', 'required|numeric|exact_length[16]');
        $this->form_validation->set_rules('Nama', 'Nama Kepala Keluarga', 'required|trim');
        $this->form_validation->set_rules('Email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[5]');
        $this->form_validation->set_rules('Telp', 'No Handphone', 'required|numeric');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'required|trim');
        $this->form_validation->set_rules('tgl_lahir', 'Tanggal Lahir', 'required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'required');
        $this->form_validation->set_rules('agama', 'Agama', 'required');
        $this->form_validation->set_rules('wilayah', 'Wilayah', 'required');
        $this->form_validation->set_rules('alamat_detail', 'Alamat Detail', 'required|trim');
        $this->form_validation->set_rules('no_rumah', 'Nomor Rumah', 'required|trim');

        $kodeDaftar = $this->input->post('kodeDaftar');
        if (empty($kodeDaftar)) { // Jika buat data baru
            $this->form_validation->set_rules('Password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('NIK', 'NIK', 'required|numeric|exact_length[16]|is_unique[tbpj.NIK]');
            $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[5]|is_unique[tbpj.username]');
        } else { // Jika edit data
            $this->form_validation->set_rules('NIK', 'NIK', 'required|numeric|exact_length[16]|callback_edit_unique_nik[' . $kodeDaftar . ']');
            $this->form_validation->set_rules('username', 'Username', 'required|trim|min_length[5]|callback_edit_unique_username[' . $kodeDaftar . ']');
        }
        
        // Jika validasi form gagal, kembali ke halaman form dengan pesan error
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('pj', 'refresh');
            return;
        }

        // 2. Menyiapkan data teks dari form untuk disimpan ke database
        $data = [
            'no_kk'         => $this->input->post('no_kk'),
            'NIK'           => $this->input->post('NIK'),
            'namaLengkap'   => $this->input->post('Nama'),
            'email'         => $this->input->post('Email'),
            'username'      => $this->input->post('username'),
            'telp'          => $this->input->post('Telp'),
            'tempat_lahir'  => $this->input->post('tempat_lahir'),
            'tgl_lahir'     => $this->input->post('tgl_lahir'),
            'jenis_kelamin' => $this->input->post('jenis_kelamin'),
            'agama'         => $this->input->post('agama'),
            'wilayah'       => $this->input->post('wilayah'),
            'alamat_detail' => $this->input->post('alamat_detail'),
            'no_rumah'      => $this->input->post('no_rumah'),
            'alamat'        => $this->input->post('Alamat'), // Alamat lengkap dari peta
            'latitude'      => $this->input->post('latitude'),
            'longitude'     => $this->input->post('longitude'),
            'jenisAkun'     => 'PJ'
        ];

        // 3. Meng-hash password jika diisi (untuk keamanan)
        if (!empty($this->input->post('Password'))) {
            $data['password'] = password_hash($this->input->post('Password'), PASSWORD_DEFAULT);
        }
        
        // 4. Memproses upload file Kartu Keluarga (KK)
        if (isset($_FILES['foto_kk']) && $_FILES['foto_kk']['error'] != 4) { // 4 = UPLOAD_ERR_NO_FILE
            $config['upload_path']      = FCPATH . 'uploads/pj/';
            $config['allowed_types']    = 'jpg|png|jpeg|pdf';
            $config['max_size']         = 2048; // 2MB
            $config['encrypt_name']     = TRUE;

            // Logika Kunci: Otomatis buat folder jika tidak ada
            if (!is_dir($config['upload_path'])) {
                mkdir($config['upload_path'], 0777, TRUE);
            }

            $this->upload->initialize($config);

            if ($this->upload->do_upload('foto_kk')) {
                $upload_data = $this->upload->data();
                $data['foto_kk'] = $upload_data['file_name'];
            } else {
                // Jika upload gagal, hentikan proses dan tampilkan error
                $this->session->set_flashdata('error', 'Upload Foto KK Gagal: ' . $this->upload->display_errors());
                redirect('pj', 'refresh');
                return;
            }
        }
        
        // 5. Menyimpan data ke database (Insert untuk data baru, Update untuk data lama)
        if (empty($kodeDaftar)) {
            $data['statusAktivasi'] = 'Belum';
            $data['id_kepala_lingkungan'] = self::DEFAULT_KALING_ID;
            $this->db->insert('tbpj', $data);
            $this->session->set_flashdata('pesan', 'Data Penanggung Jawab baru sudah disimpan dan sudah terkait dengan Kaling.');
        } else {
            $this->db->where('kodeDaftar', $kodeDaftar);
            $this->db->update('tbpj', $data);
            $this->session->set_flashdata('pesan', 'Data Penanggung Jawab berhasil diperbarui.');
        }

        redirect('pj', 'refresh');
    }
    
    /**
     * Callback untuk validasi NIK unik saat edit.
     */
    public function edit_unique_nik($nik, $kodeDaftar) {
        $this->db->where('NIK', $nik);
        $this->db->where('kodeDaftar !=', $kodeDaftar);
        $query = $this->db->get('tbpj');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('edit_unique_nik', 'NIK ini sudah digunakan oleh Penanggung Jawab lain.');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Callback untuk validasi username unik saat edit.
     */
    public function edit_unique_username($username, $kodeDaftar) {
        $this->db->where('username', $username);
        $this->db->where('kodeDaftar !=', $kodeDaftar);
        $query = $this->db->get('tbpj');
        if ($query->num_rows() > 0) {
            $this->form_validation->set_message('edit_unique_username', 'Username ini sudah digunakan oleh akun lain.');
            return FALSE;
        }
        return TRUE;
    }

    /**
     * Mengambil seluruh data PJ untuk ditampilkan di tabel.
     */
    public function tampildata()
    {
        $this->db->select('tbpj.*, tbkaling.namaLengkap as nama_kaling');
        $this->db->from('tbpj');
        $this->db->join('tbkaling', 'tbkaling.kodeDaftar = tbpj.id_kepala_lingkungan', 'left');
        $this->db->order_by('tbpj.kodeDaftar', 'DESC');
        $query = $this->db->get();
        return ($query->num_rows() > 0) ? $query->result() : array();
    }

    /**
     * Menghapus data PJ dari database dan file fisiknya dari server.
     */
    public function hapusdata($kodeDaftar)
    {
        // Mencegah hapus jika PJ masih digunakan oleh pendatang
        $is_used = $this->db->where('id_penanggung_jawab', $kodeDaftar)->count_all_results('tbpendatang');
        if ($is_used > 0) {
            $this->session->set_flashdata('error', 'Data Penanggung Jawab tidak bisa dihapus karena masih digunakan di data pendatang.');
            redirect('pj', 'refresh');
            return;
        }

        // Mengambil nama file untuk dihapus dari server
        $pj = $this->db->get_where('tbpj', ['kodeDaftar' => $kodeDaftar])->row();
        if ($pj && !empty($pj->foto_kk)) {
            $file_path = FCPATH . 'uploads/pj/' . $pj->foto_kk; // Path yang sudah benar
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // Menghapus data dari database
        $this->db->where('kodeDaftar', $kodeDaftar);
        $this->db->delete('tbpj');
        $this->session->set_flashdata('pesan', 'Data berhasil dihapus!');
        redirect('pj', 'refresh');
    }

    /**
     * Mengambil satu baris data PJ untuk di-edit dan dikirim sebagai JSON ke JavaScript.
     */
    public function editdata($kodeDaftar)
    {
        $this->db->where('kodeDaftar', $kodeDaftar);
        $query = $this->db->get('tbpj'); 
        if ($query->num_rows() > 0) {
            echo json_encode($query->row());
        }
    }
}
?>