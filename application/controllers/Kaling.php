<?php
class Kaling extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('validasi');
        $this->validasi->validasiakun(); // Validasi login

        // Tambahkan baris ini
        $this->load->helper(array('form', 'url', 'string'));
        $this->load->library('email');
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

    // File: application/controllers/Kaling.php

    public function verifikasidata($kodeDaftar)
    {
        // Langkah 1: Ambil data lengkap akun dari tbkaling berdasarkan kodeDaftar
        $akun = $this->db->get_where('tbkaling', ['kodeDaftar' => $kodeDaftar])->row();

        // Jika akun tidak ditemukan, hentikan proses
        if (!$akun) {
            $this->session->set_flashdata('pesan', 'Gagal! Data akun tidak ditemukan.');
            redirect('kaling', 'refresh'); // Sesuaikan dengan halaman admin Anda
            return;
        }

        // Langkah 2: Ubah status aktivasi di tbkaling menjadi 'Terverifikasi'
        $this->db->where('kodeDaftar', $kodeDaftar);
        $this->db->update('tbkaling', ['statusAktivasi' => 'Terverifikasi']);

        // Langkah 3: Siapkan data untuk dimasukkan ke tblogin
        // Pastikan nama kolom di tblogin (Level) sesuai dengan sumber data (jenisAkun)
        $data_login = array(
            'NIK'         => $akun->NIK,
            'Password'    => $akun->password,      // Password diambil dari data asli
            'NamaLengkap' => $akun->namaLengkap,
            'Level'       => $akun->jenisAkun    // 'jenisAkun' dari tbkaling menjadi 'Level' di tblogin
        );

        // Langkah 4 (PENTING): Cek dulu apakah NIK sudah ada di tblogin sebelum insert
        $cek_tblogin = $this->db->get_where('tblogin', ['NIK' => $akun->NIK])->num_rows();

        if ($cek_tblogin == 0) {
            // Jika NIK belum ada, masukkan data baru ke tblogin
            $this->db->insert('tblogin', $data_login);
        } else {
            // Jika NIK sudah ada (misal karena verifikasi ulang), cukup update datanya
            $this->db->where('NIK', $akun->NIK);
            $this->db->update('tblogin', $data_login);
        }

        // Langkah 5: Beri pesan sukses
        $this->session->set_flashdata('pesan', 'Akun berhasil diverifikasi dan siap untuk login!');
        redirect('kaling', 'refresh'); // Sesuaikan dengan halaman admin Anda
    }


    // Simpan data baru atau edit
    // Ganti dengan fungsi simpandata() yang baru ini
    public function simpandata()
    {
        $kodeDaftar = $this->input->post('kodeDaftar');
        $Email = $this->input->post('Email');

        $data = array(
            'NIK' => $this->input->post('NIK'),
            'NamaLengkap' => $this->input->post('Nama'),
            'Telp' => $this->input->post('Telp'),
            'Email' => $Email,
            'Alamat_Rumah' => $this->input->post('Alamat_Rumah'),
            'jenisAkun' => $this->input->post('jenisAkun'),
        );

        if (empty($kodeDaftar)) { // HANYA saat membuat data baru
            // 1. Buat password & token
            $temporary_password = random_string('alnum', 8);
            $reset_token = random_string('alnum', 32);

            // 2. Tambahkan ke data
            $data['password'] = $temporary_password;
            $data['reset_token'] = $reset_token;
            $data['statusAktivasi'] = 'Belum';

            // 3. Simpan data ke DB
            $this->db->insert('tbkaling', $data);

            // 4. Kirim email
            $this->_kirim_email_aktivasi($reset_token, $Email);

            $this->session->set_flashdata('pesan', 'Data Kaling baru berhasil disimpan. Email aktivasi telah dikirim.');
        } else { // Saat update data
            // Hapus password dari array data agar tidak ter-update kosong
            // jika kolom password tidak ada di form edit
            unset($data['password']); 

            $this->db->where('kodeDaftar', $kodeDaftar);
            $this->db->update('tbkaling', $data);
            $this->session->set_flashdata('pesan', 'Data Kaling sudah diedit.');
        }
        redirect('kaling', 'refresh');
    }

    // Tambahkan fungsi ini di dalam class Kaling
    private function _kirim_email_aktivasi($token, $email)
    {
        $this->email->from($this->config->item('smtp_user'), 'Admin SIDADANG');
        $this->email->to($email);
        $this->email->subject('Aktivasi Akun dan Pengaturan Password SIDADANG');

        $message  = "<h4>Akun Anda telah dibuat oleh Admin.</h4>";
        $message .= "<p>Silakan klik tautan di bawah ini untuk mengatur password Anda:</p>";
        $message .= "<h3><a href='" . site_url('auth/reset_password/' . $token) . "'>Atur Password Saya</a></h3>";
        $message .= "<br><br><p>Jika Anda tidak merasa didaftarkan, abaikan email ini.</p>";

        $this->email->message($message);
        $this->email->send();
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