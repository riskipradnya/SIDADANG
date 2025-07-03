<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
  /* ... (CSS Anda yang sudah ada tetap di sini) ... */
  .card-header-minimalist {
    background-color: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
    color: #212529;
    font-weight: 500;
    padding: 0.75rem 1.25rem;
  }
  .kolom-teks-panjang {
    word-wrap: break-word;
    white-space: normal !important;
  }
  .label-cell {
    font-weight: 500; 
    vertical-align: top;
    padding-right: 0.5rem; 
  }
  .separator-cell {
    width: 1%; 
    vertical-align: top;
    text-align: left; 
    padding-right: 0.5rem; 
  }
  .value-cell {
    vertical-align: top;
  }
  .img-thumbnail {
    border: 1px solid #dee2e6; 
    padding: 0.25rem;
  }
  .table-borderless > tbody > tr > td,
  .table-borderless > tbody > tr > th {
      border: 0;
  }
  .table-hover > tbody > tr:hover > * {
    --bs-table-accent-bg: var(--bs-table-hover-bg); 
  }
  .badge { 
    font-size: 0.9em;
  }
</style>

<div class="container-fluid mt-4">
    <?php if (isset($pendatang) && !empty($pendatang)): ?>
    <div class="card shadow-sm mx-auto" style="max-width: 960px;"> 
        <div class="card-header card-header-minimalist d-flex justify-content-between align-items-center">
            <h5 class="mb-0"> 
                <i class="mdi mdi-account-details"></i> Detail Data Pendatang: <?= htmlspecialchars($pendatang->nama); ?>
            </h5>
            <!-- <?php if ($pendatang->statusAktivasi == 'Terverifikasi'): ?>
                <a href="<?= site_url('pendatang/cetak_surat_domisili/' . $pendatang->id); ?>" class="btn btn-outline-dark btn-sm" title="Cetak Surat Keterangan Domisili" target="_blank">
                    <i class="mdi mdi-printer"></i> Cetak Surat Domisili
                </a>
            <?php endif; ?> -->
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <p class="fw-bold mb-2">Foto Diri:</p>
                    <?php if (!empty($pendatang->foto_diri) && file_exists('./uploads/pendatang/' . $pendatang->foto_diri)): ?>
                        <img src="<?= base_url('uploads/pendatang/' . htmlspecialchars($pendatang->foto_diri)); ?>" alt="Foto Diri" class="img-fluid img-thumbnail rounded" style="max-height: 200px;">
                    <?php else: ?>
                        <div class="text-center text-muted p-3 border rounded" style="min-height: 150px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="mdi mdi-account-box-outline" style="font-size: 4rem;"></i>
                            <small class="mt-1"><em>Tidak ada foto</em></small>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <p class="fw-bold mb-2">Foto KTP:</p>
                    <?php if (!empty($pendatang->foto_ktp) && file_exists('./uploads/pendatang/' . $pendatang->foto_ktp)): ?>
                        <img src="<?= base_url('uploads/pendatang/' . htmlspecialchars($pendatang->foto_ktp)); ?>" alt="Foto KTP" class="img-fluid img-thumbnail rounded" style="max-height: 200px;">
                    <?php else: ?>
                         <div class="text-center text-muted p-3 border rounded" style="min-height: 150px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                            <i class="mdi mdi-card-account-details-outline" style="font-size: 4rem;"></i>
                            <small class="mt-1"><em>Tidak ada foto</em></small>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <h5 class="text-primary mb-3">Data Pribadi</h5>
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="label-cell" style="width: 35%;">ID Pendatang</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell"><?= htmlspecialchars($pendatang->id); ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">NIK</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell kolom-teks-panjang"><?= htmlspecialchars($pendatang->nik); ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Nama Lengkap</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell kolom-teks-panjang"><?= htmlspecialchars($pendatang->nama); ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">No. Handphone</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell"><?= htmlspecialchars($pendatang->no_hp); ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Tempat Lahir</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell kolom-teks-panjang"><?= htmlspecialchars($pendatang->tempat_lahir); ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Tanggal Lahir</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell"><?= !empty($pendatang->tgl_lahir) && $pendatang->tgl_lahir != '0000-00-00' ? date('d F Y', strtotime($pendatang->tgl_lahir)) : '-'; ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Jenis Kelamin</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell"><?= htmlspecialchars($pendatang->jenis_kelamin); ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Golongan Darah</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell"><?= htmlspecialchars($pendatang->golongan_darah); ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Agama</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell"><?= htmlspecialchars($pendatang->agama); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr class="my-4">
            <h5 class="text-primary mb-3">Alamat Asal</h5>
            <table class="table table-sm table-borderless">
                 <tr>
                    <td class="label-cell" style="width: 20%;">Provinsi</td>
                    <td class="separator-cell">:</td>
                    <td class="value-cell kolom-teks-panjang"><?= htmlspecialchars($pendatang->provinsi_asal); ?></td>
                </tr>
                <tr>
                    <td class="label-cell">Kabupaten/Kota</td>
                    <td class="separator-cell">:</td>
                    <td class="value-cell kolom-teks-panjang"><?= htmlspecialchars($pendatang->kabupaten_asal); ?></td>
                </tr>
                 <tr>
                    <td class="label-cell">Kecamatan</td>
                    <td class="separator-cell">:</td>
                    <td class="value-cell kolom-teks-panjang"><?= htmlspecialchars($pendatang->kecamatan_asal); ?></td>
                </tr>
                <tr>
                    <td class="label-cell">Kelurahan/Desa</td>
                    <td class="separator-cell">:</td>
                    <td class="value-cell kolom-teks-panjang"><?= htmlspecialchars($pendatang->kelurahan_asal); ?></td>
                </tr>
                <tr>
                    <td class="label-cell">RT/RW</td>
                    <td class="separator-cell">:</td>
                    <td class="value-cell"><?= htmlspecialchars($pendatang->rt); ?> / <?= htmlspecialchars($pendatang->rw); ?></td>
                </tr>
                <tr>
                    <td class="label-cell">Alamat Lengkap Asal</td>
                    <td class="separator-cell">:</td>
                    <td class="value-cell kolom-teks-panjang"><?= nl2br(htmlspecialchars($pendatang->alamat_asal)); ?></td>
                </tr>
            </table>

            <hr class="my-4">
            <h5 class="text-primary mb-3">Informasi Domisili & Kedatangan</h5>
             <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="label-cell" style="width: 40%;">Alamat Domisili Sekarang</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell kolom-teks-panjang"><?= nl2br(htmlspecialchars($pendatang->alamat_sekarang)); ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Latitude</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell kolom-teks-panjang"><?= htmlspecialchars($pendatang->latitude); ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Longitude</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell kolom-teks-panjang"><?= htmlspecialchars($pendatang->longitude); ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Tujuan Kedatangan</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell kolom-teks-panjang"><?= nl2br(htmlspecialchars($pendatang->tujuan)); ?></td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="label-cell" style="width: 40%;">Tanggal Masuk</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell"><?= !empty($pendatang->tgl_masuk) && $pendatang->tgl_masuk != '0000-00-00' ? date('d F Y', strtotime($pendatang->tgl_masuk)) : '-'; ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Tgl Keluar (Estimasi)</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell"><?= !empty($pendatang->tgl_keluar) && $pendatang->tgl_keluar != '0000-00-00' ? date('d F Y', strtotime($pendatang->tgl_keluar)) : '-'; ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell">Wilayah Tujuan</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell kolom-teks-panjang"><?= htmlspecialchars($pendatang->wilayah); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr class="my-4">
            <h5 class="text-primary mb-3">Pihak Terkait & Status</h5>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <td class="label-cell" style="width: 40%;">Penanggung Jawab (PJ)</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell kolom-teks-panjang">
                                <?php if(!empty($pendatang->id_penanggung_jawab) && !empty($pendatang->pj_namaLengkap)): ?>
                                    <?= htmlspecialchars($pendatang->pj_namaLengkap); ?> (NIK: <?= htmlspecialchars($pendatang->pj_NIK); ?>)
                                <?php else: ?>
                                    <span class="text-muted"><em>Tidak ada</em></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-cell">Kepala Lingkungan (Kaling)</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell kolom-teks-panjang">
                                <?php if(!empty($pendatang->id_kepala_lingkungan) && !empty($pendatang->kaling_namaLengkap)): ?>
                                    <?= htmlspecialchars($pendatang->kaling_namaLengkap); ?> (NIK: <?= htmlspecialchars($pendatang->kaling_NIK); ?>)
                                <?php else: ?>
                                     <span class="text-muted"><em>Tidak ada</em></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                     <table class="table table-sm table-borderless">
                        <tr>
                            <td class="label-cell" style="width: 40%;">Status Aktivasi Data</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell">
                                <?php
                                    $statusClass = 'secondary'; 
                                    $statusText = !empty($pendatang->statusAktivasi) ? htmlspecialchars($pendatang->statusAktivasi) : 'Tidak Diketahui';
                                    if ($pendatang->statusAktivasi == 'Terverifikasi') {
                                        $statusClass = 'success';
                                    } elseif ($pendatang->statusAktivasi == 'Belum Terverifikasi') {
                                        $statusClass = 'warning text-dark';
                                    } elseif ($pendatang->statusAktivasi == 'Verifikasi Ditolak') {
                                        $statusClass = 'danger';
                                    }
                                ?>
                                <span class="badge bg-<?= $statusClass; ?>"><?= $statusText; ?></span>
                            </td>
                        </tr>
                        <?php // PINDAHKAN BLOK ALASAN PENOLAKAN KE SINI ?>
                        <?php if ($pendatang->statusAktivasi == 'Verifikasi Ditolak' && !empty($pendatang->alasan_penolakan)): ?>
                        <tr class=""> 
                            <td class="label-cell" style="width: 40%;">Alasan Penolakan</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell kolom-teks-panjang">
                                <?= nl2br(htmlspecialchars($pendatang->alasan_penolakan)); ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td class="label-cell" style="width: 40%;">Tanggal Pendaftaran</td>
                            <td class="separator-cell">:</td>
                            <td class="value-cell"><?= !empty($pendatang->created_at) ? date('d F Y, H:i:s', strtotime($pendatang->created_at)) . ' WITA' : '-'; ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="mt-4 pt-3 text-center border-top">
                <a href="<?= site_url('pendatang/daftar'); ?>" class="btn btn-outline-secondary">
                    <i class="mdi mdi-arrow-left"></i> Kembali ke Daftar Pendatang
                </a>
            </div>

        </div> </div> <?php else: ?>
    <div class="alert alert-warning text-center" role="alert">
        <h4 class="alert-heading"><i class="mdi mdi-alert-circle-outline"></i> Data Tidak Ditemukan</h4>
        <p>Data pendatang yang Anda minta tidak dapat ditemukan dalam sistem.</p>
        <hr>
        <a href="<?= site_url('pendatang/daftar'); ?>" class="btn btn-secondary btn-sm">
            <i class="mdi mdi-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
    <?php endif; ?>
</div>