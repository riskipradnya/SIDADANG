<?php
// Bagian ini memuat semua tag <link> dan <style> yang diperlukan.
?>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />

<style>
    /* Mengadopsi semua style dari pendatang_view untuk konsistensi UI */
    .preview-container {
        border: 2px dashed #ddd; border-radius: .25rem; padding: 1rem;
        text-align: center; margin-bottom: 1rem; background-color: #f8f9fa;
    }
    .preview-img-full {
        max-height: 250px; width: auto; display: block;
        margin-left: auto; margin-right: auto; margin-bottom: 1rem;
        border: 1px solid #eee;
    }
    #map {
        width: 100%; height: 250px; position: relative;
        z-index: 1; border-radius: .25rem; border: 1px solid #ccc; 
    }
    #alamatSekarang { overflow-y: hidden; min-height: calc(1.5em + .75rem + 2px); }
    .form-control-m[type="file"] { height: auto; padding-top: 0.25rem; padding-bottom: 0.25rem; }
    .form-select:disabled { background-color: #e9ecef; }
    .btn-form-custom { border-radius: 3px !important; }
    .btn-form-custom + .btn-form-custom { margin-left: 0.5rem; }
    .btn-outline-primary.btn-form-custom:hover { background-color: #0d6efd; color: white; }
    select.form-select option[value=""] { color: #6c757d; }
    select.form-select option:not([value=""]) { color: #212529; }
</style>

<div class="container mt-4">

    <?php 
    // Notifikasi flashdata
    $pesan = $this->session->flashdata('pesan');
    $error = $this->session->flashdata('error');
    if ($pesan) {
        echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                ' . $pesan . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
    if ($error) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                ' . $error . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
    }
    ?>
    <h4 class="mb-4">Form Pendaftaran Penanggung Jawab</h4>
    
    <form name="formpj" id="formpj" method="post" enctype="multipart/form-data" action="<?= base_url('pj/simpandata') ?>">
        <input type="hidden" name="kodeDaftar" id="kodeDaftar"/>
        <input type="hidden" name="jenisAkun" id="jenisAkun" value="PJ"/>

        <div class="row mb-4">
            <div class="col-12">
                <div class="preview-container">
                    <label for="foto_kk" class="form-label fw-bold">Scan Kartu Keluarga (KK) <span class="text-danger">*</span></label>
                    <img id="previewKk" src="<?= base_url('assets/img/default-kk.png') ?>" alt="Preview Kartu Keluarga" class="preview-img-full">
                    <input type="file" name="foto_kk" id="foto_kk" class="form-control form-control-m" onchange="previewImage(this, 'previewKk')" accept="image/*,application/pdf" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                 <h5 class="mb-3">Data Diri & Akun</h5>
                
                <?php
                // PERUBAHAN 2: Array PHP diperbaiki dan field baru ditambahkan
                $fields_kiri = [
                    'no_kk'         => ['label' => 'No. Kartu Keluarga', 'type' => 'text', 'name' => 'no_kk', 'required' => true, 'pattern' => '\d{16}', 'title' => 'No. KK harus 16 digit angka'],
                    'nik'           => ['label' => 'NIK Kepala Keluarga', 'type' => 'text', 'name' => 'NIK', 'required' => true, 'pattern' => '\d{16}', 'title' => 'NIK harus 16 digit angka'],
                    'nama'          => ['label' => 'Nama Kepala Keluarga', 'type' => 'text', 'name' => 'Nama', 'required' => true],
                    'username'      => ['label' => 'Username', 'type' => 'text', 'name' => 'username', 'required' => true],
                    'password'      => ['label' => 'Password', 'type' => 'password', 'name' => 'Password', 'required' => true],
                    'no_hp'         => ['label' => 'No Handphone Aktif', 'type' => 'tel', 'name' => 'Telp', 'required' => true, 'placeholder' => '08xxxxxxxxxx'],
                    'tempat_lahir'  => ['label' => 'Tempat Lahir', 'type' => 'text', 'name' => 'tempat_lahir', 'required' => true],
                    'tgl_lahir'     => ['label' => 'Tanggal Lahir', 'type' => 'date', 'name' => 'tgl_lahir', 'required' => true],
                ];

                foreach ($fields_kiri as $id => $attr) {
                    echo '<div class="mb-3">';
                    echo "<label for='{$id}' class='form-label'>{$attr['label']}" . ($attr['required'] ? ' <span class="text-danger">*</span>' : '') . "</label>";
                    echo "<input type='{$attr['type']}' class='form-control' name='{$attr['name']}' id='{$id}' " 
                         . (isset($attr['pattern']) ? "pattern='{$attr['pattern']}' " : "") 
                         . (isset($attr['title']) ? "title='{$attr['title']}' " : "") 
                         . (isset($attr['placeholder']) ? "placeholder='{$attr['placeholder']}' " : "Masukkan {$attr['label']}") 
                         . ($attr['required'] ? 'required' : '') . ">";
                    echo '</div>';
                }
                ?>
                
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-select" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
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
                    <label for="wilayah" class="form-label">Wilayah (Banjar/Lingkungan) <span class="text-danger">*</span></label>
                    <select name="wilayah" id="wilayah" class="form-select" required>
                        <option value="">Pilih Wilayah</option>
                        <?php
                            $banjar_list = ["Pesalakan", "Menega", "Pasek", "Tegal", "Perarudan", "Mumbul", "Mekar Sari", "Taman Griya", "Kori Nuansa", "Kalanganyar", "Angga Suara", "Mertha Sari", "Buana Gubug", "Telaga", "Lainnya"];
                            foreach ($banjar_list as $banjar) {
                                echo "<option value=\"$banjar\">$banjar</option>";
                            }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="alamat_detail" class="form-label">Alamat Detail (Nama Jalan, Gang, dll) <span class="text-danger">*</span></label>
                    <textarea name="alamat_detail" id="alamat_detail" class="form-control" rows="2" required></textarea>
                </div>

                 <div class="mb-3">
                    <label for="no_rumah" class="form-label">Nomor Rumah <span class="text-danger">*</span></label>
                    <input type="text" name="no_rumah" id="no_rumah" class="form-control" required>
                </div>
            </div>

            <div class="col-md-6">
                <h5 class="mb-3">Lokasi Domisili (Peta)</h5>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tandai Lokasi Rumah pada Peta <span class="text-danger">*</span></label>
                    <div id="map"></div>
                </div>

                <div class="mb-3">
                    <label for="alamatSekarang" class="form-label">Alamat Domisili (Hasil dari Peta)</label>
                    <textarea class="form-control" name="Alamat" id="alamatSekarang" rows="2" readonly></textarea> 
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
            </div>
        </div>

        <hr>

        <div class="text-center mt-4 mb-5">
            <button type="submit" class="btn btn-outline-primary px-5 btn-form-custom">
                <i class=""></i> Simpan Data
            </button>
            <button type="reset" class="btn btn-outline-secondary px-5 ms-2 btn-form-custom">
                <i class=""></i> Reset Form
            </button>
        </div>
    </form>
</div>

<?php
// Tidak ada perubahan pada JavaScript
?>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.umd.js"></script>

<script>
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            if (file.type.startsWith('image/')) {
                reader.onload = function(e) { preview.src = e.target.result; }
                reader.readAsDataURL(file);
            } else {
                preview.src = "<?= base_url('assets/img/default-file.png') ?>"; 
            }
        } else {
            preview.src = "<?= base_url('assets/img/default-kk.png') ?>";
        }
    }

    $(document).ready(function() {
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
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const geosearchProvider = new GeoSearch.OpenStreetMapProvider();

        async function updateAddressFromMap(lat, lng) {
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
        }
        
        updateAddressFromMap(initialLat, initialLng);
        
        map.on('click', async function(e) {
            updateAddressFromMap(e.latlng.lat, e.latlng.lng);
        });

        $('button[type="reset"]').click(function() {
            setTimeout(() => {
                map.setView([initialLat, initialLng], initialZoom);
                updateAddressFromMap(initialLat, initialLng);
                $('#previewKk').attr('src', "<?= base_url('assets/img/default-kk.png') ?>");
            }, 100);
        });
    });
</script>