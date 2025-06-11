<!-- <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.0.0/dist/geosearch.css" />

<style>
    /* Styling untuk preview gambar agar konsisten */
    .preview-container {
        border: 2px dashed #ddd;
        border-radius: .25rem;
        padding: 1rem;
        text-align: center;
        margin-bottom: 1rem;
        background-color: #f8f9fa;
    }

    .preview-img {
        max-height: 150px;
        width: auto;
        display: block;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 1rem;
    }

    #map {
        width: 100%;
        height: 250px; /* Tinggi peta disesuaikan agar pas di layout */
        position: relative;
        z-index: 1; /* Disesuaikan agar tidak tumpang tindih dengan elemen lain jika ada */
        border-radius: .25rem;
    }
</style>

<div class="container mt-4">
    <h4 class="mb-4">Form Pendataan Pendatang</h4>
    
    <form method="post" enctype="multipart/form-data" action="<?= base_url('pendatang/simpan') ?>">
        <div class="row">

            <div class="col-md-6">

                <div class="preview-container">
                    <img id="previewDiri" src="<?= base_url('assets/img/selfie1.png') ?>" alt="Preview Foto Diri" class="preview-img">
                    <input type="file" name="foto_diri" id="foto_diri" class="form-control" onchange="previewImage(this, 'previewDiri')">
                </div>
                
                <?php
                // Array fields yang berada di kolom kiri - placeholder dikosongkan
                $fields_kiri = [
                    'nik' => ['label' => 'NIK (Isi Sesuai KTP)', 'type' => 'text', 'placeholder' => ''],
                    'nama' => ['label' => 'Nama Lengkap (Isi Sesuai KTP)', 'type' => 'text', 'placeholder' => ''],
                    'no_hp' => ['label' => 'No Handphone', 'type' => 'text', 'placeholder' => ''],
                    'tempat_lahir' => ['label' => 'Tempat Lahir (Isi Sesuai KTP)', 'type' => 'text', 'placeholder' => ''],
                    'tgl_lahir' => ['label' => 'Tanggal Lahir (Isi Sesuai KTP)', 'type' => 'date', 'placeholder' => ''],
                ];
                
                foreach ($fields_kiri as $name => $attr) {
                    echo '<div class="mb-3">';
                    echo "<label for='{$name}' class='form-label'>{$attr['label']}</label>";
                    echo "<input type='{$attr['type']}' class='form-control' name='{$name}' id='{$name}' placeholder='{$attr['placeholder']}' required>";
                    echo '</div>';
                }
                ?>
                
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin (Isi Sesuai KTP)</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="golongan_darah" class="form-label">Golongan Darah</label>
                    <select name="golongan_darah" id="golongan_darah" class="form-select">
                        <option value="">Pilih Golongan Darah</option>
                        <option>A</option><option>B</option><option>AB</option><option>O</option><option>Tidak Tahu</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="agama" class="form-label">Agama</label>
                    <select name="agama" id="agama" class="form-select">
                        <option value="">Pilih Agama</option>
                        <option>Islam</option><option>Kristen</option><option>Hindu</option><option>Budha</option><option>Katolik</option><option>Konghucu</option>
                    </select>
                </div>

                <?php
                // Array fields alamat asal - placeholder dikosongkan
                $fields_alamat_asal = [
                    'provinsi_asal' => ['label' => 'Provinsi Asal (Isi Sesuai KTP)', 'type' => 'text', 'placeholder' => ''],
                    'kabupaten_asal' => ['label' => 'Kabupaten/Kota Asal (Isi Sesuai KTP)', 'type' => 'text', 'placeholder' => ''],
                    'kecamatan_asal' => ['label' => 'Kecamatan Asal (Isi Sesuai KTP)', 'type' => 'text', 'placeholder' => ''],
                    'kelurahan_asal' => ['label' => 'Kelurahan/Desa Asal (Isi Sesuai KTP)', 'type' => 'text', 'placeholder' => ''],
                    'rt' => ['label' => 'RT (Isi Sesuai KTP)', 'type' => 'text', 'placeholder' => ''],
                    'rw' => ['label' => 'RW (Isi Sesuai KTP)', 'type' => 'text', 'placeholder' => ''],
                ];
                foreach ($fields_alamat_asal as $name => $attr) {
                    echo '<div class="mb-3">';
                    echo "<label for='{$name}' class='form-label'>{$attr['label']}</label>";
                    echo "<input type='{$attr['type']}' class='form-control' name='{$name}' id='{$name}' placeholder='{$attr['placeholder']}' required>";
                    echo '</div>';
                }
                ?>
                <div class="mb-3">
                    <label for="alamat_asal" class="form-label">Alamat Asal (Isi Sesuai KTP)</label>
                    <textarea class="form-control" name="alamat_asal" id="alamat_asal" rows="2"></textarea>
                </div>

            </div>

            <div class="col-md-6">

                <div class="preview-container">
                    <img id="previewKtp" src="<?= base_url('assets/img/default-ktp.png') ?>" alt="Preview KTP" class="preview-img">
                    <div class="input-group">
                        <input type="file" name="foto_ktp" id="foto_ktp" class="form-control" onchange="previewImage(this, 'previewKtp')">
                        <button class="btn btn-outline-secondary" type="button" id="scanBtn" disabled>Scan & Isi Otomatis</button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Lokasi (Klik pada peta)</label>
                    <div id="map"></div>
                </div>

                <div class="mb-3">
                    <label for="alamatSekarang" class="form-label">Alamat Sekarang</label>
                    <textarea class="form-control" name="alamatSekarang" id="alamatSekarang" rows="1" readonly></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="text" class="form-control" name="latitude" id="latitude" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="text" class="form-control" name="longitude" id="longitude" readonly>
                        </div>
                    </div>
                </div>
                
                 <div class="mb-3">
                    <label for="tujuan" class="form-label">Tujuan</label>
                    <textarea class="form-control" name="tujuan" id="tujuan" rows="2"></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tgl_masuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" name="tgl_masuk" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tgl_keluar" class="form-label">Tanggal Keluar (Tidak Wajib)</label>
                            <input type="date" class="form-control" name="tgl_keluar">
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="kepala_lingkungan" class="form-label">Kepala Lingkungan</label>
                    <select name="kepala_lingkungan" id="kepala_lingkungan" class="form-select">
                        <option value="">Pilih Kepala Lingkungan</option>
                        </select>
                </div>

                <div class="mb-3">
                    <label for="wilayah" class="form-label">Wilayah</label>
                    <select name="wilayah" id="wilayah" class="form-select">
                        <option value="">Pilih Wilayah</option>
                         </select>
                </div>

            </div>
        </div>

        <hr>

        <div class="text-start mt-3 mb-5">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= base_url('pendatang') ?>" class="btn btn-secondary">Kembali</a>
        </div>
    </form>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.umd.js"></script>

<script>
// Fungsi untuk menampilkan preview gambar (sudah ada di kode Anda)
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ========================================================================
    // FUNGSI BARU: Untuk mengubah tinggi textarea secara otomatis
    // ========================================================================
    function autoResizeTextarea(element) {
        // 1. Reset tinggi ke 'auto'. Ini penting agar textarea bisa "menyusut" 
        //    jika konten baru lebih pendek dari konten sebelumnya.
        element.style.height = 'auto';

        // 2. Atur tinggi elemen menjadi sama dengan scrollHeight (tinggi total konten di dalamnya).
        //    Kita tambahkan 2 piksel sebagai buffer kecil agar tidak ada scrollbar yang muncul.
        element.style.height = (element.scrollHeight) + 'px';
    }


    document.addEventListener("DOMContentLoaded", function () {
        // --- Inisialisasi Elemen ---
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');
        const alamatSekarangInput = document.getElementById('alamatSekarang');

        // --- Inisialisasi Peta Leaflet ---
        const initialLat = -8.8007277;
        const initialLng = 115.1626822;
        const initialZoom = 15;
        
        const map = L.map('map').setView([initialLat, initialLng], initialZoom);
        let marker = L.marker([initialLat, initialLng]).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        const geosearchProvider = new GeoSearch.OpenStreetMapProvider();

        // --- Event Listener Saat Peta di Klik ---
        map.on('click', async function(e) {
            const { lat, lng } = e.latlng;

            // Update posisi marker
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }

            // Set nilai input form
            if (latitudeInput) {
                latitudeInput.value = lat.toFixed(7);
            }
            if (longitudeInput) {
                longitudeInput.value = lng.toFixed(7);
            }
            if (alamatSekarangInput) {
                alamatSekarangInput.value = "Mencari alamat...";
                // Panggil fungsi resize bahkan untuk teks "Mencari alamat..."
                autoResizeTextarea(alamatSekarangInput);
            }

            // Geocoding untuk mendapatkan alamat dari lat/lng
            try {
                const results = await geosearchProvider.search({ query: `${lat}, ${lng}` });
                if (results && results.length > 0) {
                    if (alamatSekarangInput) {
                        alamatSekarangInput.value = results[0].label;
                        
                        // ==========================================
                        // PANGGIL FUNGSI RESIZE SETELAH ALAMAT DIDAPATKAN
                        // ==========================================
                        autoResizeTextarea(alamatSekarangInput); 
                    }
                } else {
                    if (alamatSekarangInput) {
                        alamatSekarangInput.value = "Alamat tidak ditemukan pada koordinat ini.";
                        autoResizeTextarea(alamatSekarangInput); // Panggil juga di sini
                    }
                }
            } catch (error) {
                console.error("Geocoding error:", error);
                if (alamatSekarangInput) {
                    alamatSekarangInput.value = "Gagal mendapatkan alamat dari koordinat.";
                    autoResizeTextarea(alamatSekarangInput); // Panggil juga saat error
                }
            }
        });
    });
</script> -->


<!-- benar1 -->

<!-- <!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Data Pendatang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />

    <style>
        .preview-container {
            border: 2px dashed #ddd; border-radius: .25rem; padding: 1rem;
            text-align: center; margin-bottom: 1rem; background-color: #f8f9fa;
        }
        .preview-img {
            max-height: 150px; width: auto; display: block; margin-left: auto;
            margin-right: auto; margin-bottom: 1rem; border: 1px solid #eee;
        }
        #map {
            width: 100%; height: 250px; position: relative;
            z-index: 1; border-radius: .25rem; border: 1px solid #ccc; 
        }
        #scanKtpSpinner { display: none; margin-right: 5px; }
        #progressText { font-size: 0.9em; color: #666; min-height: 1.2em; }
        #alamatSekarang { overflow-y: hidden; min-height: calc(1.5em + .75rem + 2px); }
        .form-control-m[type="file"] { height: auto; padding-top: 0.25rem; padding-bottom: 0.25rem; }
        .input-group-m .form-control, .input-group-m .btn {
            padding-top: 0.4rem; padding-bottom: 0.4rem; font-size: 0.9rem; height: auto;
        }
        .input-group-m .form-control[type="file"] { padding-top: 0.4rem; padding-bottom: 0.4rem; }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h4 class="mb-4">Form Pendataan Pendatang</h4>
        
        <form method="post" enctype="multipart/form-data" action="<?= base_url('pendatang/simpan') ?>">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Data Diri & Alamat Asal</h5>
                    <div class="preview-container">
                        <label for="foto_diri" class="form-label fw-bold">Foto Diri</label>
                        <img id="previewDiri" src="<?= base_url('assets/img/selfie1.png') ?>" alt="Preview Foto Diri" class="preview-img">
                        <input type="file" name="foto_diri" id="foto_diri" class="form-control form-control-m" onchange="previewImage(this, 'previewDiri')" accept="image/*">
                    </div>
                    
                    <?php
                    $fields_kiri = [
                        'nik' => ['label' => 'NIK (Sesuai KTP)', 'type' => 'text', 'pattern' => '\d{16}', 'title' => 'NIK harus 16 digit angka', 'required' => true],
                        'nama' => ['label' => 'Nama Lengkap (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'no_hp' => ['label' => 'No Handphone Aktif', 'type' => 'tel', 'placeholder' => '08xxxxxxxxxx'],
                        'tempat_lahir' => ['label' => 'Tempat Lahir (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'tgl_lahir' => ['label' => 'Tanggal Lahir (Sesuai KTP)', 'type' => 'date', 'required' => true],
                    ];
                    foreach ($fields_kiri as $name => $attr) {
                        echo '<div class="mb-3">';
                        echo "<label for='{$name}' class='form-label'>{$attr['label']}" . (isset($attr['required']) && $attr['required'] ? ' <span class="text-danger">*</span>' : '') . "</label>";
                        echo "<input type='{$attr['type']}' class='form-control' name='{$name}' id='{$name}' " 
                             . (isset($attr['pattern']) ? "pattern='{$attr['pattern']}' " : "") 
                             . (isset($attr['title']) ? "title='{$attr['title']}' " : "") 
                             . (isset($attr['placeholder']) ? "placeholder='{$attr['placeholder']}' " : "Masukkan {$attr['label']}") 
                             . (isset($attr['required']) && $attr['required'] ? 'required' : '') . ">";
                        echo '</div>';
                    }
                    ?>
                    
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin (Sesuai KTP) <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="golongan_darah" class="form-label">Golongan Darah</label>
                        <select name="golongan_darah" id="golongan_darah" class="form-select">
                            <option value="">Pilih Golongan Darah</option>
                            <option value="A">A</option><option value="B">B</option><option value="AB">AB</option><option value="O">O</option>
                            <option value="A+">A+</option><option value="B+">B+</option><option value="AB+">AB+</option><option value="O+">O+</option>
                            <option value="A-">A-</option><option value="B-">B-</option><option value="AB-">AB-</option><option value="O-">O-</option>
                            <option value="TIDAK TAHU">TIDAK TAHU</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="agama" class="form-label">Agama <span class="text-danger">*</span></label>
                        <select name="agama" id="agama" class="form-select" required>
                            <option value="">Pilih Agama</option>
                            <option value="Islam">Islam</option><option value="Kristen Protestan">Kristen Protestan</option><option value="Kristen Katolik">Kristen Katolik</option>
                            <option value="Hindu">Hindu</option><option value="Buddha">Buddha</option><option value="Khonghucu">Khonghucu</option><option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <?php
                    $fields_alamat_asal = [
                        'provinsi_asal' => ['label' => 'Provinsi Asal (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'kabupaten_asal' => ['label' => 'Kabupaten/Kota Asal (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'kecamatan_asal' => ['label' => 'Kecamatan Asal (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'kelurahan_asal' => ['label' => 'Kelurahan/Desa Asal (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'rt' => ['label' => 'RT Asal (Sesuai KTP)', 'type' => 'text', 'pattern' => '\d{1,3}', 'title' => 'RT terdiri dari 1-3 digit angka'],
                        'rw' => ['label' => 'RW Asal (Sesuai KTP)', 'type' => 'text', 'pattern' => '\d{1,3}', 'title' => 'RW terdiri dari 1-3 digit angka'],
                    ];
                    foreach ($fields_alamat_asal as $name => $attr) {
                        echo '<div class="mb-3">';
                        echo "<label for='{$name}' class='form-label'>{$attr['label']}" . (isset($attr['required']) && $attr['required'] ? ' <span class="text-danger">*</span>' : '') . "</label>";
                        echo "<input type='{$attr['type']}' class='form-control' name='{$name}' id='{$name}' "
                             . (isset($attr['pattern']) ? "pattern='{$attr['pattern']}' " : "") 
                             . (isset($attr['title']) ? "title='{$attr['title']}' " : "") 
                             . (isset($attr['placeholder']) ? "placeholder='{$attr['placeholder']}' " : "Masukkan {$attr['label']}") 
                             . (isset($attr['required']) && $attr['required'] ? 'required' : '') . ">";
                        echo '</div>';
                    }
                    ?>
                    <div class="mb-3">
                        <label for="alamat_asal" class="form-label">Detail Alamat Asal (Jalan, No Rumah, dll) <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alamat_asal" id="alamat_asal" rows="2" required></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3">Data Domisili & Tambahan</h5>
                    <div class="preview-container">
                        <label for="foto_ktp" class="form-label fw-bold">Scan KTP</label>
                        <img id="previewKtp" src="<?= base_url('assets/img/default-ktp.png') ?>" alt="Preview KTP" class="preview-img">
                        <div class="input-group input-group-m">
                            <input type="file" name="foto_ktp" id="foto_ktp" class="form-control" onchange="previewImage(this, 'previewKtp')" accept="image/*" capture="environment">
                            <button class="btn btn-outline-success" type="button" id="scanBtn" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" id="scanKtpSpinner"></span>
                                Scan & Isi Otomatis
                            </button>
                        </div>
                        <div id="progressText" class="mt-1"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Lokasi Domisili (Klik pada peta)</label>
                        <div id="map"></div>
                    </div>

                    <div class="mb-3">
                        <label for="alamatSekarang" class="form-label">Alamat Domisili Sekarang (Hasil dari Peta)</label>
                        <textarea class="form-control" name="alamatSekarang" id="alamatSekarang" rows="1"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" name="latitude" id="latitude">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" name="longitude" id="longitude">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tujuan" class="form-label">Tujuan Kedatangan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="tujuan" id="tujuan" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tgl_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tgl_masuk" id="tgl_masuk" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tgl_keluar" class="form-label">Perkiraan Tanggal Keluar</label>
                                <input type="date" class="form-control" name="tgl_keluar" id="tgl_keluar">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="kepala_lingkungan" class="form-label">Penanggung Jawab / Kepala Lingkungan Tujuan <span class="text-danger">*</span></label>
                        <select name="kepala_lingkungan" id="kepala_lingkungan" class="form-select" required>
                            <option value="">Pilih Penanggung Jawab / Kaling</option>
                            <option value="I WAYAN SUDIRA (MTR)">I WAYAN SUDIRA, S.H - KALING MERTASARI</option>
                            <option value="I MADE SUBAGA (PSL)">I MADE SUBAGA - KALING PESALAKAN</option>
                            <option value="I KETUT SUMBA (MNG)">I KETUT SUMBA - KALING MENEGA</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="wilayah" class="form-label">Wilayah (Banjar/Lingkungan di Jimbaran) <span class="text-danger">*</span></label>
                        <select name="wilayah" id="wilayah" class="form-select" required>
                            <option value="">Pilih Wilayah (Banjar/Lingkungan)</option>
                             <?php
                                $banjar_list = ["Pesalakan", "Menega", "Pasek", "Tegal", "Perarudan", "Mumbul", "Mekar Sari", "Taman Griya", "Kori Nuansa", "Kalanganyar", "Angga Suara", "Mertha Sari", "Buana Gubug", "Telaga", "Lainnya"];
                                foreach ($banjar_list as $banjar) {
                                    echo "<option value=\"$banjar\">$banjar</option>";
                                }
                             ?>
                        </select>
                    </div>
                </div>
            </div>

            <hr>

            <div class="text-center mt-4 mb-5">
                <button type="submit" class="btn btn-primary btn-lg px-5">Simpan Data</button>
                <button type="reset" class="btn btn-outline-secondary btn-lg px-5 ms-2">Reset Form</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) { preview.src = e.target.result; }
                reader.readAsDataURL(file);
            } else {
                preview.src = (previewId === 'previewDiri') ? "<?= base_url('assets/img/selfie1.png') ?>" : "<?= base_url('assets/img/default-ktp.png') ?>";
            }
        }

        function autoResizeTextarea(element) {
            if (!element) return;
            element.style.height = 'auto';
            element.style.height = (element.scrollHeight) + 'px';
        }

        let tesseractWorker = null;

        async function initializeTesseract() {
            const progressTextElement = $('#progressText');
            const scanBtnElement = $('#scanBtn');
            try {
                if (progressTextElement.length) progressTextElement.text("Inisialisasi Tesseract OCR...");
                console.log("Memulai inisialisasi Tesseract worker...");
                tesseractWorker = await Tesseract.createWorker('ind', 0, {
                    logger: m => {
                        console.log('[Tesseract Logger]', m);
                        if (progressTextElement.length) {
                            let statusText = m.status;
                            if (m.progress && (m.status === 'recognizing text' || m.status === 'loading language model' || m.status === 'downloading')) {
                                statusText += ` (${(m.progress * 100).toFixed(0)}%)`;
                            }
                            progressTextElement.text(statusText.charAt(0).toUpperCase() + statusText.slice(1));
                        }
                    },
                });
                console.log("Worker dibuat, mengatur parameter...");
                await tesseractWorker.setParameters({
                    tessedit_pageseg_mode: Tesseract.PSM.AUTO_OSD,
                    tessedit_char_whitelist: '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz :/.-,\'',
                    preserve_interword_spaces: '1'
                });
                console.log("Tesseract berhasil diinisialisasi.");
                if (progressTextElement.length) progressTextElement.text("Tesseract OCR siap.");
                if ($('#foto_ktp').get(0).files.length > 0 && scanBtnElement.length) {
                    scanBtnElement.prop('disabled', false);
                }
            } catch (error) {
                console.error("Error initializing Tesseract:", error);
                if (progressTextElement.length) progressTextElement.text("Gagal memuat Tesseract OCR. Periksa koneksi & console (F12).");
                if (scanBtnElement.length) scanBtnElement.prop('disabled', true);
            }
        }

        function parseKTPData(text) {
            const data = {};
            const lines = text.split('\n').map(line => line.trim()).filter(line => line.length > 1);
            console.log("OCR RAW LINES (setiap baris):\n" + lines.join('\n'));

            // Regex disesuaikan berdasarkan format KTP contoh dan potensi error OCR
            const nikRegex = /(?:NIK|HIK|MK|HIN|NIK)\s*[:\s]*([\d\s]{16,})/;
            const namaRegex = /Nama\s*[:\s]*([A-Z\s.'’`]+)/i;
            // Disesuaikan: lebih toleran terhadap label Tempat/Tgl Lahir dan koma pemisah
            const tempatTglLahirRegex = /(?:Tempat(?:rrgl|\/Tgl) Lahir|TempatTgiLahir)\s*[:\s]*([A-Z\s.'()-]+?)\s*[,]?\s*(\d{2}[-\/.]\d{2}[-\/.]\d{4})/i; // Koma dibuat opsional ([,?])
            const jenisKelaminRegex = /Jenis Kelamin\s*[:\s]*([A-Z\s'-]+?)(?:\s|$)/i; // Ambil hanya jenis kelamin, berhenti di spasi atau akhir baris
            const golDarahRegex = /Gol\.\s*Darah\s*[:\s]*([A-Z0-9ABO+\-]+)/i;
            const alamatRegex = /Alamat\s*[:\s]*(.+)/i;
            // Disesuaikan: mencoba menangkap RT dan RW meski tanpa spasi di antaranya atau pemisah salah baca
            const rtRwRegex = /(?:RT\/RW|RTIRW)\s*[:\s]*(\d{1,3})\s*[\/|]?\s*(\d{1,3})/; // Pemisah / atau | dibuat opsional jika OCR salah baca
            const kelDesaRegex = /(?:Kel\/Desa|KeVDesa)\s*[:\s]*([A-Z\s.'()/-]+)/i; // Menambahkan KeVFDesa dari log
            const kecamatanRegex = /(?:Kecamatan|Kecamalcn)\s*[:\s]*([A-Z\s.'()/-]+)/i;
            const agamaRegex = /Agama\s*[:\s]*([A-Z\s.'-]+)/i;
            const provinsiCaptureRegex = /^PROVINSI\s+([A-Z\s]+)/i;
            const kabKotaCaptureRegex = /^(?:KABUPATEN|KOTA)\s+([A-Z\s]+)/i;

            let fullTextOriginal = text;

            let matchProv = fullTextOriginal.match(provinsiCaptureRegex);
            if (matchProv) data.provinsi_asal = matchProv[1].trim();
            let matchKab = fullTextOriginal.match(kabKotaCaptureRegex);
            if (matchKab) data.kabupaten_asal = matchKab[1].trim();

            let alamatLengkapBuffer = "";
            let isProcessingAlamatDetails = false;

            for (const line of lines) {
                let match;
                console.log("PARSE - Processing line: '", line, "'");

                if (isProcessingAlamatDetails) {
                    if (line.match(rtRwRegex) || 
                        line.toUpperCase().startsWith("KEL/DESA") || line.toUpperCase().startsWith("KEV/DESA") ||
                        line.toUpperCase().startsWith("KECAMATAN") ||
                        line.toUpperCase().startsWith("AGAMA") ||
                        line.toUpperCase().startsWith("STATUS PERKAWINAN") ||
                        line.toUpperCase().startsWith("PEKERJAAN") ||
                        line.toUpperCase().startsWith("KEWARGANEGARAAN") ||
                        line.toUpperCase().startsWith("BERLAKU HINGGA")) {
                        isProcessingAlamatDetails = false;
                        console.log("Alamat capturing stopped. Buffer: '", alamatLengkapBuffer, "'");
                        if (alamatLengkapBuffer && !data.alamat_asal) {
                            data.alamat_asal = alamatLengkapBuffer.trim();
                        }
                    }
                }

                if (!data.nik && (match = line.match(nikRegex))) {
                    data.nik = match[1].replace(/\D/g, '').substring(0, 16);
                    console.log("  -> NIK parsed:", data.nik);
                } else if (!data.nama && (match = line.match(namaRegex))) {
                    data.nama = match[1].replace(/\s\s+/g, ' ').replace(/^\.\s*/, '').trim(); // Hapus titik di awal nama jika ada
                    console.log("  -> Nama parsed:", data.nama);
                } else if (!data.tgl_lahir && (match = line.match(tempatTglLahirRegex))) {
                    data.tempat_lahir = match[1].replace(/,/g, '').trim(); // Hapus koma dari tempat lahir
                    data.tgl_lahir = convertDate(match[2].replace(/[.\/]/g, '-'));
                    console.log("  -> Tempat/Tgl Lahir parsed. Tempat:", data.tempat_lahir, "Tgl:", data.tgl_lahir);
                } else if (!data.jenis_kelamin && (match = line.match(jenisKelaminRegex))) {
                    const jkText = match[1].toUpperCase().replace(/[:\s-]/g, '');
                    if (jkText.startsWith('LAKI')) data.jenis_kelamin = 'Laki-laki';
                    else if (jkText.startsWith('PEREMPUAN')) data.jenis_kelamin = 'Perempuan';
                    console.log("  -> Jenis Kelamin parsed:", data.jenis_kelamin, "from raw:", match[1]);
                    
                    const sisaBarisJK = line.substring(match[0].indexOf(match[1]) + match[1].length); 
                    let matchGolDarahInline = sisaBarisJK.match(golDarahRegex);
                    if (matchGolDarahInline && !data.golongan_darah) {
                        data.golongan_darah = matchGolDarahInline[1].trim().toUpperCase();
                        console.log("  -> Gol. Darah (inline with JK) parsed:", data.golongan_darah);
                    }
                } else if (!data.golongan_darah && (match = line.match(golDarahRegex))) {
                    data.golongan_darah = match[1].trim().toUpperCase();
                    console.log("  -> Gol. Darah (separate line) parsed:", data.golongan_darah);
                } else if (!data.agama && (match = line.match(agamaRegex))) {
                    data.agama = match[1].trim();
                    console.log("  -> Agama parsed:", data.agama);
                } else if (line.toUpperCase().startsWith('ALAMAT') && !isProcessingAlamatDetails) { // Hanya mulai jika belum memproses alamat
                    isProcessingAlamatDetails = true; 
                    alamatLengkapBuffer = line.substring(line.toUpperCase().indexOf('ALAMAT') + 6).replace(/^[:\s]+/, '').trim();
                    console.log("  -> Alamat line started. Initial buffer:", alamatLengkapBuffer);
                } else if (isProcessingAlamatDetails) {
                    alamatLengkapBuffer += " " + line.trim();
                    console.log("  -> Alamat buffer appended:", alamatLengkapBuffer);
                } else if ((match = line.match(rtRwRegex))) { // Coba tangkap RT/RW jika bukan bagian dari alamat
                    if (!data.rt) data.rt = match[1].padStart(3, '0');
                    if (!data.rw) data.rw = match[2].padStart(3, '0');
                    console.log("  -> RT/RW (independent line) parsed:", data.rt, "/", data.rw);
                } else if (!data.kelurahan_asal && (match = line.match(kelDesaRegex))) {
                    data.kelurahan_asal = match[1].trim();
                    console.log("  -> Kel/Desa parsed:", data.kelurahan_asal);
                } else if (!data.kecamatan_asal && (match = line.match(kecamatanRegex))) {
                    data.kecamatan_asal = match[1].trim();
                    console.log("  -> Kecamatan parsed:", data.kecamatan_asal);
                }
            }

            if (isProcessingAlamatDetails && alamatLengkapBuffer && !data.alamat_asal) {
                data.alamat_asal = alamatLengkapBuffer.trim();
                console.log("  -> Final Alamat Asal from buffer (end of loop):", data.alamat_asal);
            }
            
            // Fallback untuk RT/RW jika masih tergabung di alamat_asal atau terpisah tapi regex awal gagal
            if (data.alamat_asal && (!data.rt || !data.rw)) {
                let rtRwInAlamatMatch = data.alamat_asal.match(/(.*?)(\s*RT\/RW\s*[:\s]*(\d{1,3})\s*[\/|]\s*(\d{1,3}))/i);
                if (rtRwInAlamatMatch) {
                    data.alamat_asal = rtRwInAlamatMatch[1].trim(); 
                    data.rt = rtRwInAlamatMatch[3].padStart(3, '0');
                    data.rw = rtRwInAlamatMatch[4].padStart(3, '0');
                    console.log("  -> RT/RW extracted from alamat_asal (fallback). New alamat_asal:", data.alamat_asal, "RT:", data.rt, "RW:", data.rw);
                }
            }
            // Jika RT/RW belum terambil, coba cari dari keseluruhan teks (misal jika format "0071008")
            if (!data.rt || !data.rw) {
                let plainRtRwMatch = fullTextOriginal.match(/(?:RT\/RW|RTIRW)\s*[:\s]*(\d{3})(\d{3})/i); // Mencari format seperti 007008 setelah label
                if (plainRtRwMatch) {
                    data.rt = plainRtRwMatch[1];
                    data.rw = plainRtRwMatch[2];
                    console.log("  -> RT/RW (plain digits) parsed:", data.rt, "/", data.rw);
                    // Hapus dari alamat jika RT/RW ini bagian dari alamat_asal yang sudah terisi
                    if(data.alamat_asal && data.alamat_asal.includes(plainRtRwMatch[0])){
                        data.alamat_asal = data.alamat_asal.replace(plainRtRwMatch[0], "").trim();
                    }
                }
            }


            console.log("Final Parsed KTP Data:", data);
            return data;
        }

        function fillFormWithKTPData(data) {
            console.log("Filling form with data:", data); // Log data yang akan diisikan
            if (data.nik) { $('#nik').val(data.nik); console.log("NIK filled:", data.nik); }
            if (data.nama) { $('#nama').val(data.nama); console.log("Nama filled:", data.nama); }
            if (data.tempat_lahir) { $('#tempat_lahir').val(data.tempat_lahir); console.log("Tempat Lahir filled:", data.tempat_lahir); }
            if (data.tgl_lahir) { $('#tgl_lahir').val(data.tgl_lahir); console.log("Tgl Lahir filled:", data.tgl_lahir); }
            
            if (data.jenis_kelamin) { $('#jenis_kelamin').val(data.jenis_kelamin); console.log("Jenis Kelamin filled:", data.jenis_kelamin); }
            
            if (data.golongan_darah) {
                let foundGolDarah = false;
                $('#golongan_darah option').each(function() {
                    if ($(this).text().toUpperCase() === data.golongan_darah.toUpperCase()) {
                        $(this).prop('selected', true);
                        foundGolDarah = true;
                        console.log("Gol. Darah selected:", data.golongan_darah);
                        return false;
                    }
                });
                 if (!foundGolDarah) console.log("Gol. Darah not found in select options:", data.golongan_darah);
            } else { console.log("Gol. Darah data empty or not found in parsed object"); }

            if (data.agama) {
                let agamaFound = false;
                $('#agama option').each(function() {
                    const optionText = $(this).text().toUpperCase();
                    const dataAgama = data.agama.toUpperCase();
                    if (optionText === dataAgama) {
                        $(this).prop('selected', true);
                        agamaFound = true;
                        console.log("Agama selected:", data.agama);
                        return false; 
                    }
                });
                if (!agamaFound) {
                     $('#agama option').each(function() {
                        const optionText = $(this).text().toUpperCase();
                        const dataAgama = data.agama.toUpperCase();
                         if (dataAgama.includes(optionText) && optionText.length > 3 && $(this).val() !== "") {
                             $(this).prop('selected', true);
                             agamaFound = true;
                             console.log("Agama partial match selected:", $(this).text());
                             return false;
                         }
                     });
                }
                if (!agamaFound) console.log("Agama not found in select options:", data.agama);
            } else { console.log("Agama data empty or not found in parsed object");}


            if (data.alamat_asal) { $('#alamat_asal').val(data.alamat_asal); console.log("Alamat Asal filled:", data.alamat_asal); }
            if (data.rt) { $('#rt').val(data.rt); console.log("RT filled:", data.rt); }
            if (data.rw) { $('#rw').val(data.rw); console.log("RW filled:", data.rw); }
            if (data.kelurahan_asal) { $('#kelurahan_asal').val(data.kelurahan_asal); console.log("Kelurahan Asal filled:", data.kelurahan_asal); }
            if (data.kecamatan_asal) { $('#kecamatan_asal').val(data.kecamatan_asal); console.log("Kecamatan Asal filled:", data.kecamatan_asal); }
            if (data.kabupaten_asal) { $('#kabupaten_asal').val(data.kabupaten_asal); console.log("Kabupaten Asal filled:", data.kabupaten_asal); }
            if (data.provinsi_asal) { $('#provinsi_asal').val(data.provinsi_asal); console.log("Provinsi Asal filled:", data.provinsi_asal); }

            const noHpField = $('#no_hp');
            if (noHpField.length && !noHpField.val()) {
                noHpField.focus();
            }
        }

        function convertDate(dateStr) {
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                if (parts[0].length === 2 && parts[1].length === 2 && parts[2].length === 4) { 
                    return `${parts[2]}-${parts[1]}-${parts[0]}`;
                } else if (parts[0].length === 4 && parts[1].length === 2 && parts[2].length === 2) { 
                    return dateStr;
                }
            }
            return dateStr;
        }

        $(document).ready(function() { 
            const latitudeInput = $('#latitude');
            const longitudeInput = $('#longitude');
            const alamatSekarangInput = $('#alamatSekarang');
            const initialLat = -8.790738; 
            const initialLng = 115.162806;
            const initialZoom = 14;
            
            const map = L.map('map').setView([initialLat, initialLng], initialZoom);
            let marker = L.marker([initialLat, initialLng]).addTo(map);
            
            latitudeInput.val(initialLat.toFixed(7)); 
            longitudeInput.val(initialLng.toFixed(7)); 

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            const geosearchProvider = new GeoSearch.OpenStreetMapProvider();
            
            map.on('click', async function(e) {
                const { lat, lng } = e.latlng;
                if (marker) { marker.setLatLng([lat, lng]); } else { marker = L.marker([lat, lng]).addTo(map); }
                if (latitudeInput.length) latitudeInput.val(lat.toFixed(7));
                if (longitudeInput.length) longitudeInput.val(lng.toFixed(7));
                
                if (alamatSekarangInput.length) {
                    alamatSekarangInput.val("Mencari alamat...");
                    autoResizeTextarea(alamatSekarangInput.get(0));
                }
                try {
                    const results = await geosearchProvider.search({ query: `${lat}, ${lng}` });
                    if (results && results.length > 0) {
                        if (alamatSekarangInput.length) alamatSekarangInput.val(results[0].label);
                    } else {
                        if (alamatSekarangInput.length) alamatSekarangInput.val("Alamat tidak ditemukan pada koordinat ini.");
                    }
                } catch (error) {
                    console.error("Geocoding error:", error);
                    if (alamatSekarangInput.length) alamatSekarangInput.val("Gagal mendapatkan alamat dari koordinat.");
                }
                if (alamatSekarangInput.length) autoResizeTextarea(alamatSekarangInput.get(0));
            });

            initializeTesseract();

            $('#foto_ktp').change(function(event) {
                const file = event.target.files[0];
                if (!file) {
                    $('#scanBtn').prop('disabled', true); return;
                }
                $('#scanBtn').prop('disabled', !(tesseractWorker && file));
            });

            $('#scanBtn').click(async function() {
                const fileInput = $('#foto_ktp').get(0);
                const progressTextElement = $('#progressText');
                const scanKtpSpinner = $('#scanKtpSpinner');
                
                if (!fileInput.files || fileInput.files.length === 0) {
                    alert('Silakan unggah foto KTP terlebih dahulu.'); return;
                }
                if (!tesseractWorker) {
                    alert("Tesseract belum siap. Mohon tunggu atau coba muat ulang halaman.");
                    await initializeTesseract(); 
                    if (!tesseractWorker) { 
                        progressTextElement.text("Tesseract gagal dimuat. Silakan muat ulang halaman."); return;
                    }
                }
                const file = fileInput.files[0];
                const btn = $(this);
                
                let textNode = null;
                btn.contents().each(function() {
                    if (this.nodeType === Node.TEXT_NODE && this.nodeValue.trim() !== "") {
                        textNode = this; return false; 
                    }
                });
                const originalButtonText = textNode ? textNode.nodeValue.trim() : "Scan & Isi Otomatis";
                if (textNode) textNode.nodeValue = " Memproses...";

                btn.prop('disabled', true);
                if(scanKtpSpinner.length) scanKtpSpinner.show();
                if (progressTextElement.length) progressTextElement.text("Memulai proses scan KTP...");

                try {
                    if (progressTextElement.length) progressTextElement.text("Membaca data dari gambar KTP...");
                    const { data: { text } } = await tesseractWorker.recognize(file);
                    const ktpData = parseKTPData(text);
                    fillFormWithKTPData(ktpData);
                    if (progressTextElement.length) progressTextElement.text("Data KTP berhasil diurai. Mohon periksa kembali!");
                    setTimeout(() => {
                        if (progressTextElement.length && progressTextElement.text() === "Data KTP berhasil diurai. Mohon periksa kembali!") {
                             progressTextElement.text("Tesseract OCR siap.");
                        }
                    }, 7000);
                } catch (err) {
                    console.error('Scan KTP error:', err);
                    if (progressTextElement.length) progressTextElement.text("Gagal membaca KTP: " + (err.message || "Coba foto ulang."));
                } finally {
                    btn.prop('disabled', false);
                    if(scanKtpSpinner.length) scanKtpSpinner.hide();
                    if (textNode) { textNode.nodeValue = " " + originalButtonText; } 
                    else { btn.append(" " + originalButtonText); }
                }
            });
        });
    </script>
</body>
</html> -->

<!-- benar2 -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Data Pendatang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />

    <style>
        .preview-container {
            border: 2px dashed #ddd; border-radius: .25rem; padding: 1rem;
            text-align: center; margin-bottom: 1rem; background-color: #f8f9fa;
        }
        .preview-img {
            max-height: 150px; width: auto; display: block; margin-left: auto;
            margin-right: auto; margin-bottom: 1rem; border: 1px solid #eee;
        }
        #map {
            width: 100%; height: 250px; position: relative;
            z-index: 1; border-radius: .25rem; border: 1px solid #ccc; 
        }
        #scanKtpSpinner { display: none; margin-right: 5px; }
        #progressText { font-size: 0.9em; color: #666; min-height: 1.2em; }
        #alamatSekarang { overflow-y: hidden; min-height: calc(1.5em + .75rem + 2px); }
        .form-control-m[type="file"] { height: auto; padding-top: 0.25rem; padding-bottom: 0.25rem; }
        .input-group-m .form-control, .input-group-m .btn {
            padding-top: 0.4rem; padding-bottom: 0.4rem; font-size: 0.9rem; height: auto;
        }
        .input-group-m .form-control[type="file"] { padding-top: 0.4rem; padding-bottom: 0.4rem; }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h4 class="mb-4">Form Pendataan Pendatang</h4>
        
        <form method="post" enctype="multipart/form-data" action="<?= base_url('pendatang/simpan') ?>">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Data Diri & Alamat Asal</h5>
                    <div class="preview-container">
                        <label for="foto_diri" class="form-label fw-bold">Foto Diri</label>
                        <img id="previewDiri" src="<?= base_url('assets/img/selfie1.png') ?>" alt="Preview Foto Diri" class="preview-img">
                        <input type="file" name="foto_diri" id="foto_diri" class="form-control form-control-m" onchange="previewImage(this, 'previewDiri')" accept="image/*">
                    </div>
                    
                    <?php
                    $fields_kiri = [
                        'nik' => ['label' => 'NIK (Sesuai KTP)', 'type' => 'text', 'pattern' => '\d{16}', 'title' => 'NIK harus 16 digit angka', 'required' => true],
                        'nama' => ['label' => 'Nama Lengkap (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'no_hp' => ['label' => 'No Handphone Aktif', 'type' => 'tel', 'placeholder' => '08xxxxxxxxxx'],
                        'tempat_lahir' => ['label' => 'Tempat Lahir (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'tgl_lahir' => ['label' => 'Tanggal Lahir (Sesuai KTP)', 'type' => 'date', 'required' => true],
                    ];
                    foreach ($fields_kiri as $name => $attr) {
                        echo '<div class="mb-3">';
                        echo "<label for='{$name}' class='form-label'>{$attr['label']}" . (isset($attr['required']) && $attr['required'] ? ' <span class="text-danger">*</span>' : '') . "</label>";
                        echo "<input type='{$attr['type']}' class='form-control' name='{$name}' id='{$name}' " 
                             . (isset($attr['pattern']) ? "pattern='{$attr['pattern']}' " : "") 
                             . (isset($attr['title']) ? "title='{$attr['title']}' " : "") 
                             . (isset($attr['placeholder']) ? "placeholder='{$attr['placeholder']}' " : "Masukkan {$attr['label']}") 
                             . (isset($attr['required']) && $attr['required'] ? 'required' : '') . ">";
                        echo '</div>';
                    }
                    ?>
                    
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin (Sesuai KTP) <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="golongan_darah" class="form-label">Golongan Darah</label>
                        <select name="golongan_darah" id="golongan_darah" class="form-select">
                            <option value="">Pilih Golongan Darah</option>
                            <option value="A">A</option><option value="B">B</option><option value="AB">AB</option><option value="O">O</option>
                            <option value="A+">A+</option><option value="B+">B+</option><option value="AB+">AB+</option><option value="O+">O+</option>
                            <option value="A-">A-</option><option value="B-">B-</option><option value="AB-">AB-</option><option value="O-">O-</option>
                            <option value="TIDAK TAHU">TIDAK TAHU</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="agama" class="form-label">Agama <span class="text-danger">*</span></label>
                        <select name="agama" id="agama" class="form-select" required>
                            <option value="">Pilih Agama</option>
                            <option value="Islam">Islam</option><option value="Kristen Protestan">Kristen Protestan</option><option value="Kristen Katolik">Kristen Katolik</option>
                            <option value="Hindu">Hindu</option><option value="Buddha">Buddha</option><option value="Khonghucu">Khonghucu</option><option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <?php
                    $fields_alamat_asal = [
                        'provinsi_asal' => ['label' => 'Provinsi Asal (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'kabupaten_asal' => ['label' => 'Kabupaten/Kota Asal (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'kecamatan_asal' => ['label' => 'Kecamatan Asal (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'kelurahan_asal' => ['label' => 'Kelurahan/Desa Asal (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'rt' => ['label' => 'RT Asal (Sesuai KTP)', 'type' => 'text', 'pattern' => '\d{1,3}', 'title' => 'RT terdiri dari 1-3 digit angka'],
                        'rw' => ['label' => 'RW Asal (Sesuai KTP)', 'type' => 'text', 'pattern' => '\d{1,3}', 'title' => 'RW terdiri dari 1-3 digit angka'],
                    ];
                    foreach ($fields_alamat_asal as $name => $attr) {
                        echo '<div class="mb-3">';
                        echo "<label for='{$name}' class='form-label'>{$attr['label']}" . (isset($attr['required']) && $attr['required'] ? ' <span class="text-danger">*</span>' : '') . "</label>";
                        echo "<input type='{$attr['type']}' class='form-control' name='{$name}' id='{$name}' "
                             . (isset($attr['pattern']) ? "pattern='{$attr['pattern']}' " : "") 
                             . (isset($attr['title']) ? "title='{$attr['title']}' " : "") 
                             . (isset($attr['placeholder']) ? "placeholder='{$attr['placeholder']}' " : "Masukkan {$attr['label']}") 
                             . (isset($attr['required']) && $attr['required'] ? 'required' : '') . ">";
                        echo '</div>';
                    }
                    ?>
                    <div class="mb-3">
                        <label for="alamat_asal" class="form-label">Detail Alamat Asal (Jalan, No Rumah, dll) <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alamat_asal" id="alamat_asal" rows="2" required></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3">Data Domisili & Tambahan</h5>
                    <div class="preview-container">
                        <label for="foto_ktp" class="form-label fw-bold">Scan KTP</label>
                        <img id="previewKtp" src="<?= base_url('assets/img/default-ktp.png') ?>" alt="Preview KTP" class="preview-img">
                        <div class="input-group input-group-m">
                            <input type="file" name="foto_ktp" id="foto_ktp" class="form-control" onchange="previewImage(this, 'previewKtp')" accept="image/*" capture="environment">
                            <button class="btn btn-outline-success" type="button" id="scanBtn" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" id="scanKtpSpinner"></span>
                                Scan & Isi Otomatis
                            </button>
                        </div>
                        <div id="progressText" class="mt-1"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Lokasi Domisili (Klik pada peta)</label>
                        <div id="map"></div>
                    </div>

                    <div class="mb-3">
                        <label for="alamatSekarang" class="form-label">Alamat Domisili Sekarang (Hasil dari Peta)</label>
                        <textarea class="form-control" name="alamatSekarang" id="alamatSekarang" rows="1"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" name="latitude" id="latitude">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" name="longitude" id="longitude">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tujuan" class="form-label">Tujuan Kedatangan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="tujuan" id="tujuan" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tgl_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tgl_masuk" id="tgl_masuk" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tgl_keluar" class="form-label">Perkiraan Tanggal Keluar</label>
                                <input type="date" class="form-control" name="tgl_keluar" id="tgl_keluar">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="kepala_lingkungan" class="form-label">Penanggung Jawab / Kepala Lingkungan Tujuan <span class="text-danger">*</span></label>
                        <select name="kepala_lingkungan" id="kepala_lingkungan" class="form-select" required>
                            <option value="">Pilih Penanggung Jawab / Kaling</option>
                            <option value="I WAYAN SUDIRA (MTR)">I WAYAN SUDIRA, S.H - KALING MERTASARI</option>
                            <option value="I MADE SUBAGA (PSL)">I MADE SUBAGA - KALING PESALAKAN</option>
                            <option value="I KETUT SUMBA (MNG)">I KETUT SUMBA - KALING MENEGA</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="wilayah" class="form-label">Wilayah (Banjar/Lingkungan di Jimbaran) <span class="text-danger">*</span></label>
                        <select name="wilayah" id="wilayah" class="form-select" required>
                            <option value="">Pilih Wilayah (Banjar/Lingkungan)</option>
                             <?php
                                $banjar_list = ["Pesalakan", "Menega", "Pasek", "Tegal", "Perarudan", "Mumbul", "Mekar Sari", "Taman Griya", "Kori Nuansa", "Kalanganyar", "Angga Suara", "Mertha Sari", "Buana Gubug", "Telaga", "Lainnya"];
                                foreach ($banjar_list as $banjar) {
                                    echo "<option value=\"$banjar\">$banjar</option>";
                                }
                             ?>
                        </select>
                    </div>
                </div>
            </div>

            <hr>

            <div class="text-center mt-4 mb-5">
                <button type="submit" class="btn btn-primary btn-lg px-5">Simpan Data</button>
                <button type="reset" class="btn btn-outline-secondary btn-lg px-5 ms-2">Reset Form</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) { preview.src = e.target.result; }
                reader.readAsDataURL(file);
            } else {
                preview.src = (previewId === 'previewDiri') ? "<?= base_url('assets/img/selfie1.png') ?>" : "<?= base_url('assets/img/default-ktp.png') ?>";
            }
        }

        function autoResizeTextarea(element) {
            if (!element) return;
            element.style.height = 'auto';
            element.style.height = (element.scrollHeight) + 'px';
        }

        let tesseractWorker = null;

        async function initializeTesseract() {
            const progressTextElement = $('#progressText');
            const scanBtnElement = $('#scanBtn');
            try {
                if (progressTextElement.length) progressTextElement.text("Inisialisasi Tesseract OCR...");
                console.log("Memulai inisialisasi Tesseract worker...");
                tesseractWorker = await Tesseract.createWorker('ind', 0, {
                    logger: m => {
                        console.log('[Tesseract Logger]', m);
                        if (progressTextElement.length) {
                            let statusText = m.status;
                            if (m.progress && (m.status === 'recognizing text' || m.status === 'loading language model' || m.status === 'downloading')) {
                                statusText += ` (${(m.progress * 100).toFixed(0)}%)`;
                            }
                            progressTextElement.text(statusText.charAt(0).toUpperCase() + statusText.slice(1));
                        }
                    },
                });
                console.log("Worker dibuat, mengatur parameter...");
                await tesseractWorker.setParameters({
                    tessedit_pageseg_mode: Tesseract.PSM.AUTO_OSD,
                    tessedit_char_whitelist: '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz :/.-,\'',
                    preserve_interword_spaces: '1'
                });
                console.log("Tesseract berhasil diinisialisasi.");
                if (progressTextElement.length) progressTextElement.text("Tesseract OCR siap.");
                if ($('#foto_ktp').get(0).files.length > 0 && scanBtnElement.length) {
                    scanBtnElement.prop('disabled', false);
                }
            } catch (error) {
                console.error("Error initializing Tesseract:", error);
                if (progressTextElement.length) progressTextElement.text("Gagal memuat Tesseract OCR. Periksa koneksi & console (F12).");
                if (scanBtnElement.length) scanBtnElement.prop('disabled', true);
            }
        }

        function parseKTPData(text) {
            const data = {};
            const lines = text.split('\n').map(line => line.trim()).filter(line => line.length > 1);
            console.log("OCR RAW LINES (setiap baris):\n" + lines.join('\n'));

            // Regex disesuaikan berdasarkan format KTP contoh dan potensi error OCR
            const nikRegex = /(?:NIK|HIK|MK|HIN|NIK)\s*[:\s]*([\d\s]{16,})/;
            const namaRegex = /Nama\s*[:\s]*([A-Z\s.'’`]+)/i;
            // Disesuaikan: lebih toleran terhadap label Tempat/Tgl Lahir dan koma pemisah
            const tempatTglLahirRegex = /(?:Tempat(?:rrgl|\/Tgl) Lahir|TempatTgiLahir)\s*[:\s]*([A-Z\s.'()-]+?)\s*[,]?\s*(\d{2}[-\/.]\d{2}[-\/.]\d{4})/i; // Koma dibuat opsional ([,?])
            const jenisKelaminRegex = /Jenis Kelamin\s*[:\s]*([A-Z\s'-]+?)(?:\s|$)/i; // Ambil hanya jenis kelamin, berhenti di spasi atau akhir baris
            const golDarahRegex = /Gol\.\s*Darah\s*[:\s]*([A-Z0-9ABO+\-]+)/i;
            const alamatRegex = /Alamat\s*[:\s]*(.+)/i;
            // Disesuaikan: mencoba menangkap RT dan RW meski tanpa spasi di antaranya atau pemisah salah baca
            const rtRwRegex = /(?:RT\/RW|RTIRW)\s*[:\s]*(\d{1,3})\s*[\/|]?\s*(\d{1,3})/; // Pemisah / atau | dibuat opsional jika OCR salah baca
            const kelDesaRegex = /(?:Kel\/Desa|KeVDesa)\s*[:\s]*([A-Z\s.'()/-]+)/i; // Menambahkan KeVFDesa dari log
            const kecamatanRegex = /(?:Kecamatan|Kecamalcn)\s*[:\s]*([A-Z\s.'()/-]+)/i;
            const agamaRegex = /Agama\s*[:\s]*([A-Z\s.'-]+)/i;
            const provinsiCaptureRegex = /^PROVINSI\s+([A-Z\s]+)/i;
            const kabKotaCaptureRegex = /^(?:KABUPATEN|KOTA)\s+([A-Z\s]+)/i;

            let fullTextOriginal = text;

            let matchProv = fullTextOriginal.match(provinsiCaptureRegex);
            if (matchProv) data.provinsi_asal = matchProv[1].trim();
            let matchKab = fullTextOriginal.match(kabKotaCaptureRegex);
            if (matchKab) data.kabupaten_asal = matchKab[1].trim();

            let alamatLengkapBuffer = "";
            let isProcessingAlamatDetails = false;

            for (const line of lines) {
                let match;
                console.log("PARSE - Processing line: '", line, "'");

                if (isProcessingAlamatDetails) {
                    if (line.match(rtRwRegex) || 
                        line.toUpperCase().startsWith("KEL/DESA") || line.toUpperCase().startsWith("KEV/DESA") ||
                        line.toUpperCase().startsWith("KECAMATAN") ||
                        line.toUpperCase().startsWith("AGAMA") ||
                        line.toUpperCase().startsWith("STATUS PERKAWINAN") ||
                        line.toUpperCase().startsWith("PEKERJAAN") ||
                        line.toUpperCase().startsWith("KEWARGANEGARAAN") ||
                        line.toUpperCase().startsWith("BERLAKU HINGGA")) {
                        isProcessingAlamatDetails = false;
                        console.log("Alamat capturing stopped. Buffer: '", alamatLengkapBuffer, "'");
                        if (alamatLengkapBuffer && !data.alamat_asal) {
                            data.alamat_asal = alamatLengkapBuffer.trim();
                        }
                    }
                }

                if (!data.nik && (match = line.match(nikRegex))) {
                    data.nik = match[1].replace(/\D/g, '').substring(0, 16);
                    console.log("  -> NIK parsed:", data.nik);
                } else if (!data.nama && (match = line.match(namaRegex))) {
                    data.nama = match[1].replace(/\s\s+/g, ' ').replace(/^\.\s*/, '').trim(); // Hapus titik di awal nama jika ada
                    console.log("  -> Nama parsed:", data.nama);
                } else if (!data.tgl_lahir && (match = line.match(tempatTglLahirRegex))) {
                    data.tempat_lahir = match[1].replace(/,/g, '').trim(); // Hapus koma dari tempat lahir
                    data.tgl_lahir = convertDate(match[2].replace(/[.\/]/g, '-'));
                    console.log("  -> Tempat/Tgl Lahir parsed. Tempat:", data.tempat_lahir, "Tgl:", data.tgl_lahir);
                } else if (!data.jenis_kelamin && (match = line.match(jenisKelaminRegex))) {
                    const jkText = match[1].toUpperCase().replace(/[:\s-]/g, '');
                    if (jkText.startsWith('LAKI')) data.jenis_kelamin = 'Laki-laki';
                    else if (jkText.startsWith('PEREMPUAN')) data.jenis_kelamin = 'Perempuan';
                    console.log("  -> Jenis Kelamin parsed:", data.jenis_kelamin, "from raw:", match[1]);
                    
                    const sisaBarisJK = line.substring(match[0].indexOf(match[1]) + match[1].length); 
                    let matchGolDarahInline = sisaBarisJK.match(golDarahRegex);
                    if (matchGolDarahInline && !data.golongan_darah) {
                        data.golongan_darah = matchGolDarahInline[1].trim().toUpperCase();
                        console.log("  -> Gol. Darah (inline with JK) parsed:", data.golongan_darah);
                    }
                } else if (!data.golongan_darah && (match = line.match(golDarahRegex))) {
                    data.golongan_darah = match[1].trim().toUpperCase();
                    console.log("  -> Gol. Darah (separate line) parsed:", data.golongan_darah);
                } else if (!data.agama && (match = line.match(agamaRegex))) {
                    data.agama = match[1].trim();
                    console.log("  -> Agama parsed:", data.agama);
                } else if (line.toUpperCase().startsWith('ALAMAT') && !isProcessingAlamatDetails) { // Hanya mulai jika belum memproses alamat
                    isProcessingAlamatDetails = true; 
                    alamatLengkapBuffer = line.substring(line.toUpperCase().indexOf('ALAMAT') + 6).replace(/^[:\s]+/, '').trim();
                    console.log("  -> Alamat line started. Initial buffer:", alamatLengkapBuffer);
                } else if (isProcessingAlamatDetails) {
                    alamatLengkapBuffer += " " + line.trim();
                    console.log("  -> Alamat buffer appended:", alamatLengkapBuffer);
                } else if ((match = line.match(rtRwRegex))) { // Coba tangkap RT/RW jika bukan bagian dari alamat
                    if (!data.rt) data.rt = match[1].padStart(3, '0');
                    if (!data.rw) data.rw = match[2].padStart(3, '0');
                    console.log("  -> RT/RW (independent line) parsed:", data.rt, "/", data.rw);
                } else if (!data.kelurahan_asal && (match = line.match(kelDesaRegex))) {
                    data.kelurahan_asal = match[1].trim();
                    console.log("  -> Kel/Desa parsed:", data.kelurahan_asal);
                } else if (!data.kecamatan_asal && (match = line.match(kecamatanRegex))) {
                    data.kecamatan_asal = match[1].trim();
                    console.log("  -> Kecamatan parsed:", data.kecamatan_asal);
                }
            }

            if (isProcessingAlamatDetails && alamatLengkapBuffer && !data.alamat_asal) {
                data.alamat_asal = alamatLengkapBuffer.trim();
                console.log("  -> Final Alamat Asal from buffer (end of loop):", data.alamat_asal);
            }
            
            // Fallback untuk RT/RW jika masih tergabung di alamat_asal atau terpisah tapi regex awal gagal
            if (data.alamat_asal && (!data.rt || !data.rw)) {
                let rtRwInAlamatMatch = data.alamat_asal.match(/(.*?)(\s*RT\/RW\s*[:\s]*(\d{1,3})\s*[\/|]\s*(\d{1,3}))/i);
                if (rtRwInAlamatMatch) {
                    data.alamat_asal = rtRwInAlamatMatch[1].trim(); 
                    data.rt = rtRwInAlamatMatch[3].padStart(3, '0');
                    data.rw = rtRwInAlamatMatch[4].padStart(3, '0');
                    console.log("  -> RT/RW extracted from alamat_asal (fallback). New alamat_asal:", data.alamat_asal, "RT:", data.rt, "RW:", data.rw);
                }
            }
            // Jika RT/RW belum terambil, coba cari dari keseluruhan teks (misal jika format "0071008")
            if (!data.rt || !data.rw) {
                let plainRtRwMatch = fullTextOriginal.match(/(?:RT\/RW|RTIRW)\s*[:\s]*(\d{3})(\d{3})/i); // Mencari format seperti 007008 setelah label
                if (plainRtRwMatch) {
                    data.rt = plainRtRwMatch[1];
                    data.rw = plainRtRwMatch[2];
                    console.log("  -> RT/RW (plain digits) parsed:", data.rt, "/", data.rw);
                    // Hapus dari alamat jika RT/RW ini bagian dari alamat_asal yang sudah terisi
                    if(data.alamat_asal && data.alamat_asal.includes(plainRtRwMatch[0])){
                        data.alamat_asal = data.alamat_asal.replace(plainRtRwMatch[0], "").trim();
                    }
                }
            }


            console.log("Final Parsed KTP Data:", data);
            return data;
        }

        function fillFormWithKTPData(data) {
            console.log("Filling form with data:", data); // Log data yang akan diisikan
            if (data.nik) { $('#nik').val(data.nik); console.log("NIK filled:", data.nik); }
            if (data.nama) { $('#nama').val(data.nama); console.log("Nama filled:", data.nama); }
            if (data.tempat_lahir) { $('#tempat_lahir').val(data.tempat_lahir); console.log("Tempat Lahir filled:", data.tempat_lahir); }
            if (data.tgl_lahir) { $('#tgl_lahir').val(data.tgl_lahir); console.log("Tgl Lahir filled:", data.tgl_lahir); }
            
            if (data.jenis_kelamin) { $('#jenis_kelamin').val(data.jenis_kelamin); console.log("Jenis Kelamin filled:", data.jenis_kelamin); }
            
            if (data.golongan_darah) {
                let foundGolDarah = false;
                $('#golongan_darah option').each(function() {
                    if ($(this).text().toUpperCase() === data.golongan_darah.toUpperCase()) {
                        $(this).prop('selected', true);
                        foundGolDarah = true;
                        console.log("Gol. Darah selected:", data.golongan_darah);
                        return false;
                    }
                });
                 if (!foundGolDarah) console.log("Gol. Darah not found in select options:", data.golongan_darah);
            } else { console.log("Gol. Darah data empty or not found in parsed object"); }

            if (data.agama) {
                let agamaFound = false;
                $('#agama option').each(function() {
                    const optionText = $(this).text().toUpperCase();
                    const dataAgama = data.agama.toUpperCase();
                    if (optionText === dataAgama) {
                        $(this).prop('selected', true);
                        agamaFound = true;
                        console.log("Agama selected:", data.agama);
                        return false; 
                    }
                });
                if (!agamaFound) {
                     $('#agama option').each(function() {
                        const optionText = $(this).text().toUpperCase();
                        const dataAgama = data.agama.toUpperCase();
                         if (dataAgama.includes(optionText) && optionText.length > 3 && $(this).val() !== "") {
                             $(this).prop('selected', true);
                             agamaFound = true;
                             console.log("Agama partial match selected:", $(this).text());
                             return false;
                         }
                     });
                }
                if (!agamaFound) console.log("Agama not found in select options:", data.agama);
            } else { console.log("Agama data empty or not found in parsed object");}


            if (data.alamat_asal) { $('#alamat_asal').val(data.alamat_asal); console.log("Alamat Asal filled:", data.alamat_asal); }
            if (data.rt) { $('#rt').val(data.rt); console.log("RT filled:", data.rt); }
            if (data.rw) { $('#rw').val(data.rw); console.log("RW filled:", data.rw); }
            if (data.kelurahan_asal) { $('#kelurahan_asal').val(data.kelurahan_asal); console.log("Kelurahan Asal filled:", data.kelurahan_asal); }
            if (data.kecamatan_asal) { $('#kecamatan_asal').val(data.kecamatan_asal); console.log("Kecamatan Asal filled:", data.kecamatan_asal); }
            if (data.kabupaten_asal) { $('#kabupaten_asal').val(data.kabupaten_asal); console.log("Kabupaten Asal filled:", data.kabupaten_asal); }
            if (data.provinsi_asal) { $('#provinsi_asal').val(data.provinsi_asal); console.log("Provinsi Asal filled:", data.provinsi_asal); }

            const noHpField = $('#no_hp');
            if (noHpField.length && !noHpField.val()) {
                noHpField.focus();
            }
        }

        function convertDate(dateStr) {
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                if (parts[0].length === 2 && parts[1].length === 2 && parts[2].length === 4) { 
                    return `${parts[2]}-${parts[1]}-${parts[0]}`;
                } else if (parts[0].length === 4 && parts[1].length === 2 && parts[2].length === 2) { 
                    return dateStr;
                }
            }
            return dateStr;
        }

        $(document).ready(function() { 
            const latitudeInput = $('#latitude');
            const longitudeInput = $('#longitude');
            const alamatSekarangInput = $('#alamatSekarang');
            const initialLat = -8.790738; 
            const initialLng = 115.162806;
            const initialZoom = 14;
            
            const map = L.map('map').setView([initialLat, initialLng], initialZoom);
            let marker = L.marker([initialLat, initialLng]).addTo(map);
            
            latitudeInput.val(initialLat.toFixed(7)); 
            longitudeInput.val(initialLng.toFixed(7)); 

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            const geosearchProvider = new GeoSearch.OpenStreetMapProvider();
            
            map.on('click', async function(e) {
                const { lat, lng } = e.latlng;
                if (marker) { marker.setLatLng([lat, lng]); } else { marker = L.marker([lat, lng]).addTo(map); }
                if (latitudeInput.length) latitudeInput.val(lat.toFixed(7));
                if (longitudeInput.length) longitudeInput.val(lng.toFixed(7));
                
                if (alamatSekarangInput.length) {
                    alamatSekarangInput.val("Mencari alamat...");
                    autoResizeTextarea(alamatSekarangInput.get(0));
                }
                try {
                    const results = await geosearchProvider.search({ query: `${lat}, ${lng}` });
                    if (results && results.length > 0) {
                        if (alamatSekarangInput.length) alamatSekarangInput.val(results[0].label);
                    } else {
                        if (alamatSekarangInput.length) alamatSekarangInput.val("Alamat tidak ditemukan pada koordinat ini.");
                    }
                } catch (error) {
                    console.error("Geocoding error:", error);
                    if (alamatSekarangInput.length) alamatSekarangInput.val("Gagal mendapatkan alamat dari koordinat.");
                }
                if (alamatSekarangInput.length) autoResizeTextarea(alamatSekarangInput.get(0));
            });

            initializeTesseract();

            $('#foto_ktp').change(function(event) {
                const file = event.target.files[0];
                if (!file) {
                    $('#scanBtn').prop('disabled', true); return;
                }
                $('#scanBtn').prop('disabled', !(tesseractWorker && file));
            });

            $('#scanBtn').click(async function() {
                const fileInput = $('#foto_ktp').get(0);
                const progressTextElement = $('#progressText');
                const scanKtpSpinner = $('#scanKtpSpinner');
                
                if (!fileInput.files || fileInput.files.length === 0) {
                    alert('Silakan unggah foto KTP terlebih dahulu.'); return;
                }
                if (!tesseractWorker) {
                    alert("Tesseract belum siap. Mohon tunggu atau coba muat ulang halaman.");
                    await initializeTesseract(); 
                    if (!tesseractWorker) { 
                        progressTextElement.text("Tesseract gagal dimuat. Silakan muat ulang halaman."); return;
                    }
                }
                const file = fileInput.files[0];
                const btn = $(this);
                
                let textNode = null;
                btn.contents().each(function() {
                    if (this.nodeType === Node.TEXT_NODE && this.nodeValue.trim() !== "") {
                        textNode = this; return false; 
                    }
                });
                const originalButtonText = textNode ? textNode.nodeValue.trim() : "Scan & Isi Otomatis";
                if (textNode) textNode.nodeValue = " Memproses...";

                btn.prop('disabled', true);
                if(scanKtpSpinner.length) scanKtpSpinner.show();
                if (progressTextElement.length) progressTextElement.text("Memulai proses scan KTP...");

                try {
                    if (progressTextElement.length) progressTextElement.text("Membaca data dari gambar KTP...");
                    const { data: { text } } = await tesseractWorker.recognize(file);
                    const ktpData = parseKTPData(text);
                    fillFormWithKTPData(ktpData);
                    if (progressTextElement.length) progressTextElement.text("Data KTP berhasil diurai. Mohon periksa kembali!");
                    setTimeout(() => {
                        if (progressTextElement.length && progressTextElement.text() === "Data KTP berhasil diurai. Mohon periksa kembali!") {
                             progressTextElement.text("Tesseract OCR siap.");
                        }
                    }, 7000);
                } catch (err) {
                    console.error('Scan KTP error:', err);
                    if (progressTextElement.length) progressTextElement.text("Gagal membaca KTP: " + (err.message || "Coba foto ulang."));
                } finally {
                    btn.prop('disabled', false);
                    if(scanKtpSpinner.length) scanKtpSpinner.hide();
                    if (textNode) { textNode.nodeValue = " " + originalButtonText; } 
                    else { btn.append(" " + originalButtonText); }
                }
            });
        });
    </script>
</body>
</html>

<!-- benar3 -->
 <!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Data Pendatang</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />

    <style>
        .preview-container {
            border: 2px dashed #ddd; border-radius: .25rem; padding: 1rem;
            text-align: center; margin-bottom: 1rem; background-color: #f8f9fa;
        }
        .preview-img {
            max-height: 150px; width: auto; display: block; margin-left: auto;
            margin-right: auto; margin-bottom: 1rem; border: 1px solid #eee;
        }
        #map {
            width: 100%; height: 250px; position: relative;
            z-index: 1; border-radius: .25rem; border: 1px solid #ccc; 
        }
        #scanKtpSpinner { display: none; margin-right: 5px; }
        #progressText { font-size: 0.9em; color: #666; min-height: 1.2em; }
        #alamatSekarang { overflow-y: hidden; min-height: calc(1.5em + .75rem + 2px); }
        .form-control-m[type="file"] { height: auto; padding-top: 0.25rem; padding-bottom: 0.25rem; }
        .input-group-m .form-control, .input-group-m .btn {
            padding-top: 0.4rem; padding-bottom: 0.4rem; font-size: 0.9rem; height: auto;
        }
        .input-group-m .form-control[type="file"] { padding-top: 0.4rem; padding-bottom: 0.4rem; }
        .form-select:disabled { background-color: #e9ecef; }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h4 class="mb-4">Form Pendataan Pendatang</h4>
        
        <form method="post" enctype="multipart/form-data" action="<?= base_url('pendatang/simpan') ?>">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-3">Data Diri & Alamat Asal</h5>
                    <div class="preview-container">
                        <label for="foto_diri" class="form-label fw-bold">Foto Diri</label>
                        <img id="previewDiri" src="<?= base_url('assets/img/selfie1.png') ?>" alt="Preview Foto Diri" class="preview-img">
                        <input type="file" name="foto_diri" id="foto_diri" class="form-control form-control-m" onchange="previewImage(this, 'previewDiri')" accept="image/*">
                    </div>
                    
                    <?php
                    $fields_kiri = [
                        'nik' => ['label' => 'NIK (Sesuai KTP)', 'type' => 'text', 'pattern' => '\d{16}', 'title' => 'NIK harus 16 digit angka', 'required' => true],
                        'nama' => ['label' => 'Nama Lengkap (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'no_hp' => ['label' => 'No Handphone Aktif', 'type' => 'tel', 'placeholder' => '08xxxxxxxxxx'],
                        'tempat_lahir' => ['label' => 'Tempat Lahir (Sesuai KTP)', 'type' => 'text', 'required' => true],
                        'tgl_lahir' => ['label' => 'Tanggal Lahir (Sesuai KTP)', 'type' => 'date', 'required' => true],
                    ];
                    foreach ($fields_kiri as $name => $attr) {
                        echo '<div class="mb-3">';
                        echo "<label for='{$name}' class='form-label'>{$attr['label']}" . (isset($attr['required']) && $attr['required'] ? ' <span class="text-danger">*</span>' : '') . "</label>";
                        echo "<input type='{$attr['type']}' class='form-control' name='{$name}' id='{$name}' " 
                                . (isset($attr['pattern']) ? "pattern='{$attr['pattern']}' " : "") 
                                . (isset($attr['title']) ? "title='{$attr['title']}' " : "") 
                                . (isset($attr['placeholder']) ? "placeholder='{$attr['placeholder']}' " : "Masukkan {$attr['label']}") 
                                . (isset($attr['required']) && $attr['required'] ? 'required' : '') . ">";
                        echo '</div>';
                    }
                    ?>
                    
                    <div class="mb-3">
                        <label for="jenis_kelamin" class="form-label">Jenis Kelamin (Sesuai KTP) <span class="text-danger">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="golongan_darah" class="form-label">Golongan Darah</label>
                        <select name="golongan_darah" id="golongan_darah" class="form-select">
                            <option value="">Pilih Golongan Darah</option>
                            <option value="A">A</option><option value="B">B</option><option value="AB">AB</option><option value="O">O</option>
                            <option value="A+">A+</option><option value="B+">B+</option><option value="AB+">AB+</option><option value="O+">O+</option>
                            <option value="A-">A-</option><option value="B-">B-</option><option value="AB-">AB-</option><option value="O-">O-</option>
                            <option value="TIDAK TAHU">TIDAK TAHU</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="agama" class="form-label">Agama <span class="text-danger">*</span></label>
                        <select name="agama" id="agama" class="form-select" required>
                            <option value="">Pilih Agama</option>
                            <option value="Islam">Islam</option><option value="Kristen Protestan">Kristen Protestan</option><option value="Kristen Katolik">Kristen Katolik</option>
                            <option value="Hindu">Hindu</option><option value="Buddha">Buddha</option><option value="Khonghucu">Khonghucu</option><option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="provinsi_asal" class="form-label">Provinsi Asal (Sesuai KTP) <span class="text-danger">*</span></label>
                        <select name="provinsi_asal_id" id="provinsi_asal" class="form-select" required>
                            <option value="">Memuat provinsi...</option>
                        </select>
                        <input type="hidden" name="provinsi_asal" id="provinsi_asal_nama">
                    </div>

                    <div class="mb-3">
                        <label for="kabupaten_asal" class="form-label">Kabupaten/Kota Asal (Sesuai KTP) <span class="text-danger">*</span></label>
                        <select name="kabupaten_asal_id" id="kabupaten_asal" class="form-select" required disabled>
                            <option value="">Pilih Provinsi Dahulu</option>
                        </select>
                        <input type="hidden" name="kabupaten_asal" id="kabupaten_asal_nama">
                    </div>

                    <div class="mb-3">
                        <label for="kecamatan_asal" class="form-label">Kecamatan Asal (Sesuai KTP) <span class="text-danger">*</span></label>
                        <select name="kecamatan_asal_id" id="kecamatan_asal" class="form-select" required disabled>
                            <option value="">Pilih Kabupaten/Kota Dahulu</option>
                        </select>
                        <input type="hidden" name="kecamatan_asal" id="kecamatan_asal_nama">
                    </div>

                    <div class="mb-3">
                        <label for="kelurahan_asal" class="form-label">Kelurahan/Desa Asal (Sesuai KTP) <span class="text-danger">*</span></label>
                        <select name="kelurahan_asal_id" id="kelurahan_asal" class="form-select" required disabled>
                            <option value="">Pilih Kecamatan Dahulu</option>
                        </select>
                        <input type="hidden" name="kelurahan_asal" id="kelurahan_asal_nama">
                    </div>

                    <?php
                    $fields_rt_rw = [
                        'rt' => ['label' => 'RT Asal (Sesuai KTP)', 'type' => 'text', 'pattern' => '\d{1,3}', 'title' => 'RT terdiri dari 1-3 digit angka'],
                        'rw' => ['label' => 'RW Asal (Sesuai KTP)', 'type' => 'text', 'pattern' => '\d{1,3}', 'title' => 'RW terdiri dari 1-3 digit angka'],
                    ];
                    foreach ($fields_rt_rw as $name => $attr) {
                        echo '<div class="mb-3">';
                        echo "<label for='{$name}' class='form-label'>{$attr['label']}" . (isset($attr['required']) && $attr['required'] ? ' <span class="text-danger">*</span>' : '') . "</label>";
                        echo "<input type='{$attr['type']}' class='form-control' name='{$name}' id='{$name}' " 
                            . (isset($attr['pattern']) ? "pattern='{$attr['pattern']}' " : "") 
                            . (isset($attr['title']) ? "title='{$attr['title']}' " : "") 
                            . (isset($attr['placeholder']) ? "placeholder='{$attr['placeholder']}' " : "Masukkan {$attr['label']}") 
                            . (isset($attr['required']) && $attr['required'] ? 'required' : '') . ">";
                        echo '</div>';
                    }
                    ?>
                    
                    <div class="mb-3">
                        <label for="alamat_asal" class="form-label">Detail Alamat Asal (Jalan, No Rumah, dll) <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="alamat_asal" id="alamat_asal" rows="2" required></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3">Data Domisili & Tambahan</h5>
                    <div class="preview-container">
                        <label for="foto_ktp" class="form-label fw-bold">Scan KTP</label>
                        <img id="previewKtp" src="<?= base_url('assets/img/default-ktp.png') ?>" alt="Preview KTP" class="preview-img">
                        <div class="input-group input-group-m">
                            <input type="file" name="foto_ktp" id="foto_ktp" class="form-control" onchange="previewImage(this, 'previewKtp')" accept="image/*" capture="environment">
                            <button class="btn btn-outline-success" type="button" id="scanBtn" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" id="scanKtpSpinner"></span>
                                Scan & Isi Otomatis
                            </button>
                        </div>
                        <div id="progressText" class="mt-1"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Lokasi Domisili (Klik pada peta)</label>
                        <div id="map"></div>
                    </div>

                    <div class="mb-3">
                        <label for="alamatSekarang" class="form-label">Alamat Domisili Sekarang (Hasil dari Peta)</label>
                        <textarea class="form-control" name="alamatSekarang" id="alamatSekarang" rows="1"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitude" class="form-label">Latitude</label>
                                <input type="text" class="form-control" name="latitude" id="latitude">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitude" class="form-label">Longitude</label>
                                <input type="text" class="form-control" name="longitude" id="longitude">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tujuan" class="form-label">Tujuan Kedatangan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="tujuan" id="tujuan" rows="2" required></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tgl_masuk" class="form-label">Tanggal Masuk <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tgl_masuk" id="tgl_masuk" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tgl_keluar" class="form-label">Perkiraan Tanggal Keluar</label>
                                <input type="date" class="form-control" name="tgl_keluar" id="tgl_keluar">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="kepala_lingkungan" class="form-label">Penanggung Jawab / Kepala Lingkungan Tujuan <span class="text-danger">*</span></label>
                        <select name="kepala_lingkungan" id="kepala_lingkungan" class="form-select" required>
                            <option value="">Pilih Penanggung Jawab / Kaling</option>
                            <option value="I WAYAN SUDIRA (MTR)">I WAYAN SUDIRA, S.H - KALING MERTASARI</option>
                            <option value="I MADE SUBAGA (PSL)">I MADE SUBAGA - KALING PESALAKAN</option>
                            <option value="I KETUT SUMBA (MNG)">I KETUT SUMBA - KALING MENEGA</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="wilayah" class="form-label">Wilayah (Banjar/Lingkungan di Jimbaran) <span class="text-danger">*</span></label>
                        <select name="wilayah" id="wilayah" class="form-select" required>
                            <option value="">Pilih Wilayah (Banjar/Lingkungan)</option>
                            <?php
                                $banjar_list = ["Pesalakan", "Menega", "Pasek", "Tegal", "Perarudan", "Mumbul", "Mekar Sari", "Taman Griya", "Kori Nuansa", "Kalanganyar", "Angga Suara", "Mertha Sari", "Buana Gubug", "Telaga", "Lainnya"];
                                foreach ($banjar_list as $banjar) {
                                    echo "<option value=\"$banjar\">$banjar</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>

            <hr>

            <div class="text-center mt-4 mb-5">
                <button type="submit" class="btn btn-primary btn-lg px-5">Simpan Data</button>
                <button type="reset" class="btn btn-outline-secondary btn-lg px-5 ms-2">Reset Form</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.umd.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@5/dist/tesseract.min.js"></script>

    <script>
        // FUNGSI UTILITAS DASAR
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) { preview.src = e.target.result; }
                reader.readAsDataURL(file);
            } else {
                preview.src = (previewId === 'previewDiri') ? "<?= base_url('assets/img/selfie1.png') ?>" : "<?= base_url('assets/img/default-ktp.png') ?>";
            }
        }

        function convertDate(dateStr) {
            const parts = dateStr.split('-');
            if (parts.length === 3) {
                if (parts[0].length === 2 && parts[1].length === 2 && parts[2].length === 4) {
                    return `${parts[2]}-${parts[1]}-${parts[0]}`;
                } else if (parts[0].length === 4 && parts[1].length === 2 && parts[2].length === 2) {
                    return dateStr;
                }
            }
            return dateStr;
        }

        // --- BAGIAN LOGIKA TESSERACT OCR (Tidak diubah dari kode Anda sebelumnya) ---
        let tesseractWorker = null;
        async function initializeTesseract() {
            const progressTextElement = $('#progressText');
            const scanBtnElement = $('#scanBtn');
            try {
                if (progressTextElement.length) progressTextElement.text("Inisialisasi Tesseract OCR...");
                console.log("Memulai inisialisasi Tesseract worker...");
                tesseractWorker = await Tesseract.createWorker('ind', 0, {
                    logger: m => {
                        console.log('[Tesseract Logger]', m);
                        if (progressTextElement.length) {
                            let statusText = m.status;
                            if (m.progress && (m.status === 'recognizing text' || m.status === 'loading language model' || m.status === 'downloading')) {
                                statusText += ` (${(m.progress * 100).toFixed(0)}%)`;
                            }
                            progressTextElement.text(statusText.charAt(0).toUpperCase() + statusText.slice(1));
                        }
                    },
                });
                console.log("Worker dibuat, mengatur parameter...");
                await tesseractWorker.setParameters({
                    tessedit_pageseg_mode: Tesseract.PSM.AUTO_OSD,
                    tessedit_char_whitelist: '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz :/.-,\'',
                    preserve_interword_spaces: '1'
                });
                console.log("Tesseract berhasil diinisialisasi.");
                if (progressTextElement.length) progressTextElement.text("Tesseract OCR siap.");
                if ($('#foto_ktp').get(0).files.length > 0 && scanBtnElement.length) {
                    scanBtnElement.prop('disabled', false);
                }
            } catch (error) {
                console.error("Error initializing Tesseract:", error);
                if (progressTextElement.length) progressTextElement.text("Gagal memuat Tesseract OCR. Periksa koneksi & console (F12).");
                if (scanBtnElement.length) scanBtnElement.prop('disabled', true);
            }
        }

        function parseKTPData(text) {
            const data = {};
            const lines = text.split('\n').map(line => line.trim()).filter(line => line.length > 1);
            console.log("OCR RAW LINES (setiap baris):\n" + lines.join('\n'));
            const nikRegex = /(?:NIK|HIK|MK|HIN|NIK)\s*[:\s]*([\d\s]{16,})/;
            const namaRegex = /Nama\s*[:\s]*([A-Z\s.'’`]+)/i;
            const tempatTglLahirRegex = /(?:Tempat(?:rrgl|\/Tgl) Lahir|TempatTgiLahir)\s*[:\s]*([A-Z\s.'()-]+?)\s*[,]?\s*(\d{2}[-\/.]\d{2}[-\/.]\d{4})/i;
            const jenisKelaminRegex = /Jenis Kelamin\s*[:\s]*([A-Z\s'-]+?)(?:\s|$)/i;
            const golDarahRegex = /Gol\.\s*Darah\s*[:\s]*([A-Z0-9ABO+\-]+)/i;
            const rtRwRegex = /(?:RT\/RW|RTIRW)\s*[:\s]*(\d{1,3})\s*[\/|]?\s*(\d{1,3})/;
            const kelDesaRegex = /(?:Kel\/Desa|KeVDesa|Kelurahan|Desa)\s*[:\s]*([A-Z\s.'()/-]+)/i;
            const kecamatanRegex = /(?:Kecamatan|Kecamalcn)\s*[:\s]*([A-Z\s.'()/-]+)/i;
            const agamaRegex = /Agama\s*[:\s]*([A-Z\s.'-]+)/i;
            const provinsiCaptureRegex = /^PROVINSI\s+([A-Z\s]+)/i;
            const kabKotaCaptureRegex = /^(?:KABUPATEN|KOTA)\s+([A-Z\s]+)/i;

            let fullTextOriginal = text;
            let matchProv = fullTextOriginal.match(provinsiCaptureRegex);
            if (matchProv) data.provinsi_asal = matchProv[1].trim();
            let matchKab = fullTextOriginal.match(kabKotaCaptureRegex);
            if (matchKab) data.kabupaten_asal = matchKab[1].trim();

            let alamatLengkapBuffer = "";
            let isProcessingAlamatDetails = false;

            for (const line of lines) {
                let match;
                if (isProcessingAlamatDetails) {
                    if (line.match(rtRwRegex) || line.toUpperCase().startsWith("KEL/DESA") || line.toUpperCase().startsWith("KEVDESA") || line.toUpperCase().startsWith("KELURAHAN") || line.toUpperCase().startsWith("DESA") || line.toUpperCase().startsWith("KECAMATAN") || line.toUpperCase().startsWith("AGAMA") || line.toUpperCase().startsWith("STATUS PERKAWINAN") || line.toUpperCase().startsWith("PEKERJAAN") || line.toUpperCase().startsWith("KEWARGANEGARAAN") || line.toUpperCase().startsWith("BERLAKU HINGGA")) {
                        isProcessingAlamatDetails = false;
                        if (alamatLengkapBuffer && !data.alamat_asal) data.alamat_asal = alamatLengkapBuffer.trim();
                    }
                }
                if (!data.nik && (match = line.match(nikRegex))) { data.nik = match[1].replace(/\D/g, '').substring(0, 16); }
                else if (!data.nama && (match = line.match(namaRegex))) { data.nama = match[1].replace(/\s\s+/g, ' ').replace(/^\.\s*/, '').trim(); }
                else if (!data.tgl_lahir && (match = line.match(tempatTglLahirRegex))) {
                    data.tempat_lahir = match[1].replace(/,/g, '').trim();
                    data.tgl_lahir = convertDate(match[2].replace(/[.\/]/g, '-'));
                } else if (!data.jenis_kelamin && (match = line.match(jenisKelaminRegex))) {
                    const jkText = match[1].toUpperCase().replace(/[:\s-]/g, '');
                    if (jkText.startsWith('LAKI')) data.jenis_kelamin = 'Laki-laki';
                    else if (jkText.startsWith('PEREMPUAN')) data.jenis_kelamin = 'Perempuan';
                    let matchGolDarahInline = line.substring(match[0].indexOf(match[1]) + match[1].length).match(golDarahRegex);
                    if (matchGolDarahInline && !data.golongan_darah) data.golongan_darah = matchGolDarahInline[1].trim().toUpperCase();
                } else if (!data.golongan_darah && (match = line.match(golDarahRegex))) { data.golongan_darah = match[1].trim().toUpperCase(); }
                else if (!data.agama && (match = line.match(agamaRegex))) { data.agama = match[1].trim(); }
                else if (line.toUpperCase().startsWith('ALAMAT') && !isProcessingAlamatDetails) {
                    isProcessingAlamatDetails = true;
                    alamatLengkapBuffer = line.substring(line.toUpperCase().indexOf('ALAMAT') + 6).replace(/^[:\s]+/, '').trim();
                } else if (isProcessingAlamatDetails) { alamatLengkapBuffer += " " + line.trim(); }
                else if ((match = line.match(rtRwRegex))) {
                    if (!data.rt) data.rt = match[1].padStart(3, '0');
                    if (!data.rw) data.rw = match[2].padStart(3, '0');
                } else if (!data.kelurahan_asal && (match = line.match(kelDesaRegex))) { data.kelurahan_asal = match[1].trim(); }
                else if (!data.kecamatan_asal && (match = line.match(kecamatanRegex))) { data.kecamatan_asal = match[1].trim(); }
            }
            if (isProcessingAlamatDetails && alamatLengkapBuffer && !data.alamat_asal) data.alamat_asal = alamatLengkapBuffer.trim();
            console.log("Final Parsed KTP Data:", data);
            return data;
        }
        
        // =========================================================================
        // ============ AWAL KODE JAVASCRIPT YANG DIPERBAIKI TOTAL =================
        // =========================================================================
        const apiBaseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api/';

        /**
         * Fungsi inti untuk memuat data dari API dan mengisi dropdown.
         * Mengembalikan Promise yang resolve dengan data yang diambil, atau null jika gagal.
         */
        async function fetchAndPopulateDropdown(url, dropdownSelector, placeholder) {
            const select = $(dropdownSelector);
            select.empty().append(`<option value="">Memuat...</option>`).prop('disabled', true);
            console.log(`Workspaceing: ${url} for ${dropdownSelector}`);

            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status} for ${url}`);
                }
                const data = await response.json();
                select.empty().append(`<option value="">${placeholder}</option>`);
                $.each(data, function(index, item) {
                    select.append($('<option>', { value: item.id, text: item.name }));
                });
                select.prop('disabled', false);
                console.log(`Populated ${dropdownSelector} with ${data.length} items.`);
                return data; // Mengembalikan data agar bisa digunakan lebih lanjut
            } catch (error) {
                console.error(`Error fetching data for ${dropdownSelector}:`, error);
                select.empty().append(`<option value="">Gagal memuat</option>`).prop('disabled', true);
                return null; // Kembalikan null jika gagal
            }
        }
        
        /**
         * Fungsi untuk mencari nama di dalam dropdown dan memilihnya.
         * Mengembalikan ID dari opsi yang terpilih, atau null jika tidak ditemukan.
         */
        function findAndSelectOption(dropdownSelector, textToFind, hiddenInputSelector) {
            let selectedId = null;
            let selectedText = '';
            if (!textToFind) return null;

            // Normalisasi teks pencarian dari OCR
            const searchNormalized = textToFind.toUpperCase().replace("KABUPATEN", "KAB").replace("KOTA", "").trim();
            
            $(dropdownSelector + ' option').each(function() {
                // Normalisasi teks opsi dropdown
                const optionNormalized = $(this).text().toUpperCase().replace("KABUPATEN", "KAB").replace("KOTA", "").trim();
                
                // Coba pencocokan yang lebih fleksibel
                if (optionNormalized.includes(searchNormalized) || searchNormalized.includes(optionNormalized)) {
                    selectedId = $(this).val();
                    selectedText = $(this).text(); // Ambil teks asli untuk hidden input
                    return false; // Hentikan loop jika cocok
                }
            });
            
            if (selectedId) {
                $(dropdownSelector).val(selectedId);
                $(hiddenInputSelector).val(selectedText); // Simpan nama asli ke hidden input
                console.log(`Selected in ${dropdownSelector}: ID=${selectedId}, Name=${selectedText}`);
            } else {
                console.log(`No match found in ${dropdownSelector} for "${textToFind}" (normalized: "${searchNormalized}")`);
                $(hiddenInputSelector).val(''); // Kosongkan hidden input jika tidak ada yang cocok
            }
            return selectedId;
        }

        /**
         * Fungsi pengisi form yang sudah di-upgrade.
         */
        async function fillFormWithKTPData(data) {
            console.log("Memulai pengisian form dengan data (Versi diperbarui):", data);

            // 1. Isi semua field sederhana
            if (data.nik) $('#nik').val(data.nik);
            if (data.nama) $('#nama').val(data.nama);
            if (data.tempat_lahir) $('#tempat_lahir').val(data.tempat_lahir);
            if (data.tgl_lahir) $('#tgl_lahir').val(data.tgl_lahir);
            if (data.jenis_kelamin) $('#jenis_kelamin').val(data.jenis_kelamin);
            if (data.alamat_asal) $('#alamat_asal').val(data.alamat_asal);
            if (data.rt) $('#rt').val(data.rt);
            if (data.rw) $('#rw').val(data.rw);
            
            // Untuk agama dan golongan darah, kita bisa gunakan findAndSelectOption juga jika opsinya statis
            if (data.agama) findAndSelectOption('#agama', data.agama, null); // Tidak ada hidden input nama untuk ini
            if (data.golongan_darah) findAndSelectOption('#golongan_darah', data.golongan_darah, null);

            // 2. Proses dropdown wilayah secara berurutan
            if (data.provinsi_asal) {
                const provId = findAndSelectOption('#provinsi_asal', data.provinsi_asal, '#provinsi_asal_nama');
                if (provId) {
                    if (data.kabupaten_asal) {
                        await fetchAndPopulateDropdown(apiBaseUrl + `regencies/${provId}.json`, '#kabupaten_asal', 'Pilih Kabupaten/Kota');
                        const kabId = findAndSelectOption('#kabupaten_asal', data.kabupaten_asal, '#kabupaten_asal_nama');
                        if (kabId) {
                            if (data.kecamatan_asal) {
                                await fetchAndPopulateDropdown(apiBaseUrl + `districts/${kabId}.json`, '#kecamatan_asal', 'Pilih Kecamatan');
                                const kecId = findAndSelectOption('#kecamatan_asal', data.kecamatan_asal, '#kecamatan_asal_nama');
                                if (kecId) {
                                    if (data.kelurahan_asal) {
                                        await fetchAndPopulateDropdown(apiBaseUrl + `villages/${kecId}.json`, '#kelurahan_asal', 'Pilih Kelurahan/Desa');
                                        findAndSelectOption('#kelurahan_asal', data.kelurahan_asal, '#kelurahan_asal_nama');
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            console.log("Pengisian form selesai.");
            $('#no_hp').focus();
        }

        $(document).ready(function() {
            // Muat data provinsi saat halaman pertama kali dibuka (untuk interaksi manual)
            fetchAndPopulateDropdown(apiBaseUrl + 'provinces.json', '#provinsi_asal', 'Pilih Provinsi');

            // Event listener untuk interaksi manual pengguna (dropdown terikat)
            $('#provinsi_asal').change(function() {
                const provinceId = $(this).val();
                const provinceName = $(this).find('option:selected').text();
                $('#provinsi_asal_nama').val(provinceId ? provinceName : '');
                
                // Reset dan nonaktifkan semua turunan
                $('#kabupaten_asal').empty().append('<option value="">Pilih Provinsi Dahulu</option>').prop('disabled', true);
                $('#kecamatan_asal').empty().append('<option value="">Pilih Kabupaten/Kota Dahulu</option>').prop('disabled', true);
                $('#kelurahan_asal').empty().append('<option value="">Pilih Kecamatan Dahulu</option>').prop('disabled', true);
                // Kosongkan juga hidden input nama
                $('#kabupaten_asal_nama').val('');
                $('#kecamatan_asal_nama').val('');
                $('#kelurahan_asal_nama').val('');
                
                if (provinceId) {
                    fetchAndPopulateDropdown(apiBaseUrl + `regencies/${provinceId}.json`, '#kabupaten_asal', 'Pilih Kabupaten/Kota');
                }
            });

            $('#kabupaten_asal').change(function() {
                const regencyId = $(this).val();
                const regencyName = $(this).find('option:selected').text();
                $('#kabupaten_asal_nama').val(regencyId ? regencyName : '');

                $('#kecamatan_asal').empty().append('<option value="">Pilih Kabupaten/Kota Dahulu</option>').prop('disabled', true);
                $('#kelurahan_asal').empty().append('<option value="">Pilih Kecamatan Dahulu</option>').prop('disabled', true);
                $('#kecamatan_asal_nama').val('');
                $('#kelurahan_asal_nama').val('');

                if (regencyId) {
                    fetchAndPopulateDropdown(apiBaseUrl + `districts/${regencyId}.json`, '#kecamatan_asal', 'Pilih Kecamatan');
                }
            });

            $('#kecamatan_asal').change(function() {
                const districtId = $(this).val();
                const districtName = $(this).find('option:selected').text();
                $('#kecamatan_asal_nama').val(districtId ? districtName : '');

                $('#kelurahan_asal').empty().append('<option value="">Pilih Kecamatan Dahulu</option>').prop('disabled', true);
                $('#kelurahan_asal_nama').val('');

                if (districtId) {
                    fetchAndPopulateDropdown(apiBaseUrl + `villages/${districtId}.json`, '#kelurahan_asal', 'Pilih Kelurahan/Desa');
                }
            });

             $('#kelurahan_asal').change(function() {
                const villageId = $(this).val();
                const villageName = $(this).find('option:selected').text();
                $('#kelurahan_asal_nama').val(villageId ? villageName : '');
            });
            
            // --- Logika Peta Leaflet (Tidak diubah) ---
            const latitudeInput = $('#latitude');
            const longitudeInput = $('#longitude');
            const alamatSekarangInput = $('#alamatSekarang');
            const initialLat = -8.790738, initialLng = 115.162806, initialZoom = 14;
            const map = L.map('map').setView([initialLat, initialLng], initialZoom);
            let marker = L.marker([initialLat, initialLng]).addTo(map);
            latitudeInput.val(initialLat.toFixed(7));
            longitudeInput.val(initialLng.toFixed(7));
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            const geosearchProvider = new GeoSearch.OpenStreetMapProvider();
            map.on('click', async function(e) {
                const { lat, lng } = e.latlng;
                marker.setLatLng([lat, lng]);
                latitudeInput.val(lat.toFixed(7));
                longitudeInput.val(lng.toFixed(7));
                alamatSekarangInput.val("Mencari alamat...");
                try {
                    const results = await geosearchProvider.search({ query: `${lat}, ${lng}` });
                    alamatSekarangInput.val(results && results.length > 0 ? results[0].label : "Alamat tidak ditemukan.");
                } catch (error) {
                    alamatSekarangInput.val("Gagal mendapatkan alamat.");
                }
                // autoResizeTextarea(alamatSekarangInput.get(0)); // Fungsi ini tidak ada, bisa diaktifkan jika dibutuhkan
            });

            // --- Event Listeners untuk Tombol Scan (Tidak diubah) ---
            initializeTesseract();
            $('#foto_ktp').change(function(event) {
                $('#scanBtn').prop('disabled', !(tesseractWorker && event.target.files[0]));
            });
            $('#scanBtn').click(async function() {
                if (!$('#foto_ktp').get(0).files[0]) { alert('Silakan unggah foto KTP terlebih dahulu.'); return; }
                const btn = $(this);
                const progressTextElement = $('#progressText');
                const scanKtpSpinner = $('#scanKtpSpinner');
                const originalButtonText = "Scan & Isi Otomatis";
                
                btn.prop('disabled', true);
                scanKtpSpinner.show();
                btn.contents().filter(function(){ return this.nodeType == 3; }).first().replaceWith(" Memproses...");
                progressTextElement.text("Memulai proses scan KTP...");

                try {
                    const { data: { text } } = await tesseractWorker.recognize($('#foto_ktp').get(0).files[0]);
                    const ktpData = parseKTPData(text);
                    await fillFormWithKTPData(ktpData); // Memanggil versi async yang diperbarui
                    progressTextElement.text("Data KTP berhasil diurai. Mohon periksa kembali!");
                     setTimeout(() => {
                        if (progressTextElement.length && progressTextElement.text() === "Data KTP berhasil diurai. Mohon periksa kembali!") {
                            progressTextElement.text("Tesseract OCR siap.");
                        }
                    }, 7000);
                } catch (err) {
                    console.error('Scan KTP error:', err);
                    progressTextElement.text("Gagal membaca KTP: " + (err.message || "Coba foto ulang."));
                } finally {
                    btn.prop('disabled', false);
                    scanKtpSpinner.hide();
                    btn.contents().filter(function(){ return this.nodeType == 3; }).first().replaceWith(` ${originalButtonText}`);
                }
            });
        });
        
        // =========================================================================
        // ============= AKHIR KODE JAVASCRIPT YANG DIPERBAIKI TOTAL ================
        // =========================================================================
    </script>
</body>
</html>