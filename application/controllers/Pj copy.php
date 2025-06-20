<?php
class Pj extends CI_Controller
{
    // Asumsikan ID Kaling default adalah 1. Ganti angka 1 dengan ID Kaling default Anda.
    const DEFAULT_KALING_ID = 14; // <<---- GANTI DENGAN ID KALING DEFAULT ANDA

    public function __construct()
    {
        parent::__construct();
        $this->load->model('validasi');
        $this->validasi->validasiakun(); // Validasi login
        // Pastikan database di-load jika belum
        if (!isset($this->db)) {
            $this->load->database();
        }
    }

    // Menampilkan form dan tabel
    public function index()
    {
        $datalist['hasil'] = $this->tampildata(); // Ambil data pj
        $data['konten'] = $this->load->view('pj_view', '', TRUE); // Form input
        $data['table'] = $this->load->view('pj_table', $datalist, TRUE); // Tabel data
        $this->load->view('admin_view', $data); // Layout utama
    }

    public function verifikasidata($kodeDaftar)
    {
        $this->db->where('kodeDaftar', $kodeDaftar);
        $this->db->update('tbpj', ['statusAktivasi' => 'Terverifikasi']);
        $this->session->set_flashdata('pesan', 'Akun berhasil diverifikasi!');
        redirect('pj', 'refresh');
    }
    // Simpan data baru atau edit
    public function simpandata()
    {
        $kodeDaftar = $this->input->post('kodeDaftar'); // Hidden input
        $NIK = $this->input->post('NIK');
        $Password = $this->input->post('Password');
        // substr(str_shuffle("ABCDEFGHIJKLMNPQRSTUVWXYZ123456789"), 0, 6);
        $NamaLengkap = $this->input->post('Nama');
        $Telp = $this->input->post('Telp');
        $Alamat = $this->input->post('Alamat');
        $Email = $this->input->post('Email');
        $jenisAkun = $this->input->post('jenisAkun'); // Seharusnya "PJ"

        // Validasi NIK unik saat tambah baru
        if (empty($kodeDaftar)) { // Hanya saat insert baru
            $this->form_validation->set_rules('NIK', 'NIK', 'required|is_unique[tbpj.NIK]');
            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                redirect('pj', 'refresh');
                return;
            }
        } else { // Saat update, NIK bisa sama dengan NIK lama, tapi beda dengan NIK PJ lain
             $this->form_validation->set_rules('NIK', 'NIK', 'required|callback_edit_unique_nik['.$kodeDaftar.']');
             if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error', validation_errors());
                // Jika ingin mempertahankan data di form saat error edit, perlu penanganan lebih lanjut
                // Untuk saat ini redirect saja
                redirect('pj', 'refresh');
                return;
            }
        }


        $data = array(
            'NIK' => $NIK,
            'NamaLengkap' => $NamaLengkap,
            'Telp' => $Telp,
            'Alamat' => $Alamat,
            'Email' => $Email,
            'jenisAkun' => $jenisAkun, // Pastikan ini diset 'PJ' dari form atau secara default
        );

        if (empty($kodeDaftar)) { // Insert baru
            $data['Password'] = $Password; // Password hanya dibuat saat insert baru
            $data['statusAktivasi'] = 'Belum'; // Default status
            // TAMBAHKAN ID KALING DEFAULT DI SINI
            $data['id_kepala_lingkungan'] = self::DEFAULT_KALING_ID;

            $this->db->insert('tbpj', $data);
            $this->session->set_flashdata('pesan', 'Data Penanggung Jawab baru sudah disimpan dan sudah terkait dengan Kaling default.');
        } else { // Update data
            // Saat update, kita tidak mengubah password, statusAktivasi, atau id_kepala_lingkungan melalui form ini secara default
            // Jika id_kepala_lingkungan boleh diubah saat edit PJ, tambahkan inputnya di form dan proses di sini.
            // Untuk saat ini, id_kepala_lingkungan hanya diset saat pembuatan.
            $this->db->where('kodeDaftar', $kodeDaftar);
            $this->db->update('tbpj', $data);
            $this->session->set_flashdata('pesan', 'Data Penanggung Jawab sudah diedit.');
        }
        redirect('pj', 'refresh');
    }
    
    // Callback untuk validasi NIK unik saat edit
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


    // Ambil semua data dari tbpj
    public function tampildata()
    {
        // Ambil juga data kaling terkait untuk ditampilkan di tabel PJ jika perlu
        $this->db->select('tbpj.*, tbkaling.namaLengkap as nama_kaling');
        $this->db->from('tbpj');
        $this->db->join('tbkaling', 'tbkaling.kodeDaftar = tbpj.id_kepala_lingkungan', 'left');
        $this->db->order_by('tbpj.kodeDaftar', 'DESC');
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }
    }

    public function hapusdata($kodeDaftar)
    {
        // Tambahan: Cek apakah PJ ini digunakan di tbpendatang sebelum menghapus
        $is_used = $this->db->where('id_penanggung_jawab', $kodeDaftar)->count_all_results('tbpendatang');
        if ($is_used > 0) {
            $this->session->set_flashdata('error', 'Data Penanggung Jawab tidak bisa dihapus karena masih digunakan di data pendatang.');
            redirect('pj', 'refresh');
            return;
        }

        $this->db->where('kodeDaftar', $kodeDaftar);
        $this->db->delete('tbpj');
        $this->session->set_flashdata('pesan', 'Data berhasil dihapus!');
        redirect('pj', 'refresh');
    }

    // Ambil data untuk edit (output berupa JavaScript inject)
    public function editdata($kodeDaftar)
    {
        $this->db->where('kodeDaftar', $kodeDaftar);
        $query = $this->db->get('tbpj'); // Hanya ambil dari tbpj, id_kepala_lingkungan akan ada di sini
        if ($query->num_rows() > 0) {
            echo json_encode($query->row()); // Kirim semua data PJ termasuk id_kepala_lingkungan
        }
    }
}
?>