<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Keterangan Domisili - <?= htmlspecialchars($pendatang->nama ?? 'Pendatang'); ?></title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 0.7in 0.5in 0.5in 0.7in; /* Margin: atas kanan bawah kiri */
        }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .text-underline { text-decoration: underline; }
        
        .kop-surat-container {
            width: 100%;
            border-bottom: 3px solid black;
            padding-bottom: 10px;
            margin-bottom: 20px;
            position: relative; /* Penting untuk positioning absolut logo */
            min-height: 80px; /* Sesuaikan dengan tinggi logo + sedikit padding agar border bawah pas */
        }
        .logo-pemkab {
            position: absolute;
            /* PENYESUAIAN: Atur 'top' untuk menurunkan posisi logo */
            top: 10px; /* Contoh: turunkan 5px dari atas. Sesuaikan! */
            left: 0px;  
            /* PENYESUAIAN: Ubah 'width' untuk memperbesar logo */
            width: 100px; /* Contoh: dari 65px menjadi 75px. Sesuaikan! */
            height: auto; /* Biarkan auto untuk menjaga rasio aspek */
        }
        .kop-teks {
            text-align: center; /* Teks kop surat tetap di tengah */
            /* Jika Anda ingin memastikan teks tidak pernah tertimpa logo (misalnya jika logo sangat lebar atau teks kop sangat panjang): */
            /* padding-left: 80px; */ /* Sesuaikan dengan lebar logo + margin yang diinginkan */
            /* padding-right: 80px; */ /* Jika ada logo di kanan juga */
        }
        .kop-surat-container h1, .kop-surat-container h2, .kop-surat-container h3, .kop-surat-container p {
            margin: 0;
            padding: 1px 0;
        }
        .kop-surat-container h1 { font-size: 18pt; letter-spacing: 1px;}
        .kop-surat-container h2 { font-size: 16pt; letter-spacing: 1px;}
        .kop-surat-container h3 { font-size: 14pt; letter-spacing: 0.5px;}
        .kop-surat-container p.alamat-kop { font-size: 10pt; }

        .judul-surat {
            font-size: 14pt;
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 25px;
            margin-bottom: 5px;
        }
        .nomor-surat {
            text-align: center;
            margin-bottom: 10px;
            font-size: 12pt;
        }
        .paragraf {
            text-align: justify;
            margin-bottom: 12px; 
            text-indent: 2.5em; 
        }
        .paragraf-no-indent { /* Jika ada paragraf tanpa indentasi */
             text-align: justify;
            margin-bottom: 12px;
        }
        .data-section {
            margin-left: 2.5em; 
            margin-bottom: 12px;
        }
        .data-section table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-section td {
            padding: 1.5px 0; 
            vertical-align: top;
        }
        .data-section td.label {
            width: 210px; /* Lebar kolom label, sesuaikan jika perlu */
        }
        .data-section td.separator {
            width: 15px; 
            text-align: left; /* Titik dua rata kiri setelah label */
        }
        .data-section td.value {}

        .ttd-section {
            margin-top: 10px; /* Jarak 1 baris enter (sekitar 1.5em atau 18-24px) dari paragraf terakhir */
            width: 45%; 
            margin-left: 55%; /* Posisi blok TTD di sisi kanan */
            text-align: center;
            line-height: 1.6; 
        }
        .ttd-section .jabatan {
            margin-bottom: 60px; /* Ruang untuk tanda tangan dan stempel */
        }
        .ttd-section .nama-pejabat {
            font-weight: bold;
            text-decoration: underline;
        }
        .ttd-section .nip-pejabat {
            /* Style untuk NIP jika ada, misal: font-size: 11pt; */
        }
    </style>
</head>
<body>
    <div class="kop-surat-container">
        <?php
        // Pastikan FCPATH menunjuk ke root instalasi CI Anda
        $logo_path = FCPATH . 'assets/img/Lambang_Kabupaten_Badung.png'; 
        if (file_exists($logo_path)):
        ?>
            <img src="<?= $logo_path; ?>" class="logo-pemkab" alt="Logo Kabupaten Badung">
        <?php endif; ?>
        <div class="kop-teks">
            <h3 class="text-bold">PEMERINTAH KABUPATEN BADUNG</h3>
            <h3 class="text-bold">KECAMATAN KUTA SELATAN</h3>
            <h2 class="text-bold">DESA JIMBARAN</h2>
            <p class="alamat-kop">Jl. Bukit Hijau No. 5, Jimbaran, Kuta Selatan 80361</p>
        </div>
    </div>

    <div class="judul-surat">SURAT KETERANGAN DOMISILI</div>
    <div class="nomor-surat">Nomer: <?= htmlspecialchars($nomor_surat); ?></div>

    <div class="isi-surat">
        <p class="paragraf">Yang bertandatangan dibawah ini, <?= htmlspecialchars($jabatan_pejabat); ?>, Desa Jimbaran, Kecamatan Kuta Selatan, Kabupaten Badung, Provinsi Bali, dengan ini menerangkan dengan sebenarnya bahwa:</p>
        
        <div class="data-section">
            <table>
                <tr>
                    <td class="label">Nama Lengkap</td>
                    <td class="separator">:</td>
                    <td class="value"><?= htmlspecialchars($pendatang->nama); ?></td>
                </tr>
                <tr>
                    <td class="label">NIK</td>
                    <td class="separator">:</td>
                    <td class="value"><?= htmlspecialchars($pendatang->nik); ?></td>
                </tr>
                <tr>
                    <td class="label">Tempat, Tanggal Lahir</td>
                    <td class="separator">:</td>
                    <td class="value"><?= htmlspecialchars($pendatang->tempat_lahir); ?>, <?= htmlspecialchars($tgl_lahir_pendatang); // Sudah diformat dari controller ?></td>
                </tr>
                <tr>
                    <td class="label">Jenis Kelamin</td>
                    <td class="separator">:</td>
                    <td class="value"><?= htmlspecialchars($pendatang->jenis_kelamin); ?></td>
                </tr>
                <tr>
                    <td class="label">Agama</td>
                    <td class="separator">:</td>
                    <td class="value"><?= htmlspecialchars($pendatang->agama); ?></td>
                </tr>
                <tr>
                    <td class="label">Alamat KTP</td>
                    <td class="separator">:</td>
                    <td class="value"><?= nl2br(htmlspecialchars($pendatang->alamat_asal)); ?>, RT/RW <?= htmlspecialchars($pendatang->rt); ?>/<?= htmlspecialchars($pendatang->rw); ?>, Kel/Desa <?= htmlspecialchars($pendatang->kelurahan_asal); ?>, Kec. <?= htmlspecialchars($pendatang->kecamatan_asal); ?>, <?= htmlspecialchars($pendatang->kabupaten_asal); ?>, Prov. <?= htmlspecialchars($pendatang->provinsi_asal); ?></td>
                </tr>
                 <tr>
                    <td class="label">No. Handphone</td>
                    <td class="separator">:</td>
                    <td class="value"><?= htmlspecialchars($pendatang->no_hp); ?></td>
                </tr>
            </table>
        </div>

        <p class="paragraf" style="text-indent: 2.5em;">Adalah warga yang berdomisili di wilayah kami, <?= !empty($pendatang->id_penanggung_jawab) && !empty($pj_nama) ? 'kontrak/kost tinggal di rumah Bapak/Ibu ' . htmlspecialchars($pj_nama) : 'dan saat ini berdomisili'; ?>, beralamat di <?= nl2br(htmlspecialchars($pendatang->alamat_sekarang)); ?> Lingkungan <?= htmlspecialchars($pendatang->wilayah); ?>, Desa Jimbaran, Kecamatan Kuta Selatan, Kabupaten Badung.</p>
        
        <p class="paragraf">Demikian surat keterangan domisili ini dibuat dengan sebenar-benarnya untuk dapat digunakan sebagaimana mestinya.</p>
    </div>

    <div class="ttd-section">
        Jimbaran, <?= htmlspecialchars($tanggal_surat); ?><br>
        <span class="jabatan"><?= htmlspecialchars($jabatan_pejabat); ?>,</span>
        <br><br><br><br> <span class="nama-pejabat"><?= htmlspecialchars($nama_pejabat); ?></span><br>
        <?php if (!empty($nip_pejabat) && $nip_pejabat != '(NIP PERBEKEL JIKA ADA)' && $nip_pejabat != ''): // Hanya tampilkan jika NIP diisi dan bukan placeholder kosong ?>
            <span class="nip-pejabat">NIP. <?= htmlspecialchars($nip_pejabat); ?></span>
        <?php endif; ?>
    </div>

</body>
</html>