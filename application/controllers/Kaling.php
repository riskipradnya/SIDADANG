<?php
class Kaling extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('validasi');
        $this->validasi->validasiakun(); // Validasi login
    }

    // Menampilkan form dan tabel
    public function index()
    {
        $datalist['hasil'] = $this->tampildata(); // Ambil data kaling
        // Pastikan data 'Alamat_Rumah' juga terambil oleh tampildata() jika sudah ada di DB

        $data['konten'] = $this->load->view('kaling_view', '', TRUE); // Form input
        $data['table'] = $this->load->view('kaling_table', $datalist, TRUE); // Tabel data
        $this->load->view('admin_view', $data); // Layout utama
    }

    public function verifikasidata($kodeDaftar)
    {
        $this->db->where('kodeDaftar', $kodeDaftar);
        $this->db->update('tbkaling', ['statusAktivasi' => 'Terverifikasi']);
        $this->session->set_flashdata('pesan', 'Akun berhasil diverifikasi!');
        redirect('kaling', 'refresh');
    }


    // Simpan data baru atau edit
    public function simpandata()
    {
        $kodeDaftar = $this->input->post('kodeDaftar'); // Hidden input
        $NIK = $this->input->post('NIK');
        $Password = $this->input->post('Password');
        $NamaLengkap = $this->input->post('Nama');
        $Telp = $this->input->post('Telp');
        $Email = $this->input->post('Email');
        $jenisAkun = $this->input->post('jenisAkun');
        // Ambil data Alamat Rumah dari form
        // Nama 'Alamat_Rumah' harus sama dengan atribut 'name' pada input/textarea di kaling_view.php
        $AlamatRumah = $this->input->post('Alamat_Rumah');

        $data = array(
            'NIK' => $NIK,
            'Password' => $Password,
            'NamaLengkap' => $NamaLengkap,
            'Telp' => $Telp,
            'Email' => $Email,
            'Alamat_Rumah' => $AlamatRumah,
            'jenisAkun' => $jenisAkun,
            'statusAktivasi' => 'Belum' // Default status
        );

        if ($kodeDaftar == "") {
            // Cek duplikasi NIK sebelum insert (opsional tapi direkomendasikan)
            // $cek_nik = $this->db->get_where('tbkaling', array('NIK' => $NIK))->num_rows();
            // if ($cek_nik > 0) {
            //     $this->session->set_flashdata('error', 'NIK sudah terdaftar!');
            //     redirect('kaling', 'refresh');
            //     return;
            // }

            $this->db->insert('tbkaling', $data);
            $this->session->set_flashdata('pesan', 'Data sudah disimpan ...');
        } else {
            // Cek duplikasi NIK saat edit (opsional, pastikan NIK unik selain untuk dirinya sendiri)
            // $cek_nik = $this->db->get_where('tbkaling', array('NIK' => $NIK, 'kodeDaftar !=' => $kodeDaftar))->num_rows();
            // if ($cek_nik > 0) {
            //     $this->session->set_flashdata('error', 'NIK sudah terdaftar untuk pengguna lain!');
            //     redirect('kaling', 'refresh');
            //     return;
            // }
            $this->db->where('kodeDaftar', $kodeDaftar);
            $this->db->update('tbkaling', $data);
            $this->session->set_flashdata('pesan', 'Data sudah diedit ...');
        }
        redirect('kaling', 'refresh');
    }


    // Ambil semua data dari tbkaling
    public function tampildata()
    {
        // Fungsi ini akan otomatis mengambil semua kolom, termasuk 'Alamat_Rumah' jika sudah ada di tabel tbkaling
        $query = $this->db->get('tbkaling');
        if ($query->num_rows() > 0) {
            return $query->result(); // Hasilnya akan menjadi array of objects
                                     // Setiap object akan memiliki properti sesuai nama kolom, misal $row->Alamat_Rumah
        } else {
            return array();
        }
    }

    public function hapusdata($kodeDaftar)
    {
        $this->db->where('kodeDaftar', $kodeDaftar);
        $this->db->delete('tbkaling');
        $this->session->set_flashdata('pesan', 'Data berhasil dihapus!');
        redirect('kaling', 'refresh');
    }


    // Ambil data untuk edit (output berupa JavaScript inject)
    public function editdata($kodeDaftar)
    {
        $this->db->where('kodeDaftar', $kodeDaftar);
        $query = $this->db->get('tbkaling');
        if ($query->num_rows() > 0) {
            // $query->row() akan menghasilkan object dengan properti sesuai nama kolom di DB.
            // Jika nama kolom di DB adalah 'Alamat_Rumah', maka JSON akan berisi {"Alamat_Rumah": "nilai alamat"}
            // Pastikan JavaScript di view (fungsi editData) menggunakan key yang benar untuk mengambilnya,
            // misalnya data.Alamat_Rumah jika nama kolomnya 'Alamat_Rumah'.
            // Dalam respons sebelumnya saya menyarankan data.alamat_rumah, jadi pastikan konsisten.
            // Jika nama kolom DB Anda 'Alamat_Rumah', maka di JS bisa pakai data.Alamat_Rumah
            echo json_encode($query->row());
        }
        // Sebaiknya tambahkan else untuk menangani kasus data tidak ditemukan
        // else {
        //     echo json_encode(['error' => 'Data tidak ditemukan']);
        // }
    }
}
?>