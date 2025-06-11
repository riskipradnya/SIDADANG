<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container-fluid mt-4">
    <!-- <h4 class="mb-4">Ringkasan Data Sistem</h4> -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Pendatang Terverifikasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= htmlspecialchars($jumlah_pendatang_terverifikasi ?? 0); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="mdi mdi-account-group mdi-36px text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                PJ Terverifikasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= htmlspecialchars($jumlah_pj_terverifikasi ?? 0); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="mdi mdi-account-supervisor mdi-36px text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Kaling Terverifikasi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?= htmlspecialchars($jumlah_kaling_terverifikasi ?? 0); ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="mdi mdi-account-star mdi-36px text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <?php
        // Kumpulkan pesan untuk data yang belum terverifikasi dengan format baru
        $pesan_detail_belum_verifikasi = [];
        if (isset($jumlah_pendatang_belum_verif) && $jumlah_pendatang_belum_verif > 0) {
            $pesan_detail_belum_verifikasi[] = "Pada Pendatang ada " . htmlspecialchars($jumlah_pendatang_belum_verif) . " data";
        }
        if (isset($jumlah_pj_belum_verif) && $jumlah_pj_belum_verif > 0) {
            $pesan_detail_belum_verifikasi[] = "Pada PJ ada " . htmlspecialchars($jumlah_pj_belum_verif) . " data";
        }
        if (isset($jumlah_kaling_belum_verif) && $jumlah_kaling_belum_verif > 0) {
            $pesan_detail_belum_verifikasi[] = "Pada Kaling ada " . htmlspecialchars($jumlah_kaling_belum_verif) . " data";
        }
    ?>

    <?php if (!empty($pesan_detail_belum_verifikasi)): // Hanya tampilkan card jika ada data yang belum terverifikasi ?>
    <div class="row mt-2"> 
        <div class="col-md-12"> 
            <div class="card border-left-warning shadow"> 
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="col-auto pe-3">
                            <i class="mdi mdi-alert-outline text-warning" style="font-size: 2.8rem; line-height: 1;"></i> 
                        </div>
                        <div class="col">
                            <div class="h6 mb-0 text-gray-800 text-uppercase">
                                Perlu Verifikasi !!!
                            </div>
                            <div class="h6 mb-0 mt-1 text-gray-800 fw-normal"> 
                                Ada data yang belum terverifikasi: <?= implode(', ', $pesan_detail_belum_verifikasi); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php else: // Jika semua sudah terverifikasi ?>
    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card border-left-success shadow"> 
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="col-auto pe-3">
                            <i class="mdi mdi-check-all text-success" style="font-size: 2.8rem; line-height: 1;"></i>
                        </div>
                        <div class="col">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Status Verifikasi
                            </div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                Semua data penting telah terverifikasi.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- <div class="mt-4">
        <p class="text-muted"><em>Dashboard terakhir diperbarui pada: <?= date('d F Y, H:i:s'); ?> WITA</em></p>
    </div> -->
</div>