
<?php
// ==========================================
// PENEMPATAN SISWA - SIPENSIS
// ==========================================

include 'includes/cek_session.php';
include 'config/koneksi.php';

// ==========================================
// AMBIL DAFTAR SISWA
// ==========================================

$siswa = mysqli_query($koneksi, "
    SELECT id, nis, nama
    FROM t_siswa
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

// ==========================================
// AMBIL DAFTAR TAHUN AJARAN
// ==========================================

$tahun = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    ORDER BY id DESC
");

// ==========================================
// AMBIL DAFTAR KELAS
// ==========================================

$kelas = mysqli_query($koneksi, "
    SELECT id, nama, tingkat, jurusan
    FROM t_kelas
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

// ==========================================
// AMBIL DATA PENEMPATAN SISWA
// ==========================================

$sql = "SELECT
            ks.id,
            s.nis,
            s.nama AS nama_siswa,
            ta.nama AS tahun_ajaran,
            k.nama AS nama_kelas,
            k.tingkat,
            k.jurusan,
            ks.tanggal_mulai,
            ks.tanggal_selesai,
            ks.status_aktif
        FROM t_kelas_siswa ks
        LEFT JOIN t_siswa s ON ks.siswa_id = s.id
        LEFT JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
        LEFT JOIN t_kelas k ON ks.kelas_id = k.id
        ORDER BY ks.id DESC";

$hasil = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Penempatan Siswa</title>

    <!-- BOOTSTRAP 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- BOOTSTRAP ICONS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
          rel="stylesheet">

    <!-- DATATABLES CSS -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <!-- SELECT2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
          rel="stylesheet">

    <!-- SELECT2 BOOTSTRAP 5 THEME -->
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
          rel="stylesheet">

    <!-- CSS SIDEBAR -->
    <link rel="stylesheet" href="layout/style.css">

    <style>

        body {
            background-color: #f4f6fb;
        }

        main {
            min-height: 100vh;
        }

        h1 {
            font-size: 30px;
            color: #172b4d;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        /* HEADER TABEL */
        #tabelPenempatan thead th {
            background-color: #212529 !important;
            color: white !important;
            text-align: center;
            white-space: nowrap;
            vertical-align: middle;
        }

        #tabelPenempatan tbody td {
            vertical-align: middle;
        }

        .btn-sm {
            font-size: 12px;
        }

        /* SELECT2 SISWA */
        .select2-container {
            width: 100% !important;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
            padding: 4px 10px;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .select2-container--bootstrap-5 .select2-selection {
            min-height: 38px;
        }

        .select2-container--bootstrap-5 .select2-selection__rendered {
            line-height: 28px !important;
        }

        .select2-container--bootstrap-5 .select2-selection__arrow {
            height: 36px !important;
        }

        .select2-dropdown {
            z-index: 9999;
        }

    </style>

</head>

<body>

<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="menu-page-content">

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">

                <h1>Penempatan Siswa</h1>

                <button class="btn btn-primary btn-sm"
                        data-bs-toggle="collapse"
                        data-bs-target="#formPenempatan">

                    <i class="bi bi-plus-circle"></i>
                    Tambah Penempatan

                </button>

            </div>

            <!-- KEMBALI -->
            <a href="dashboard.php" class="btn btn-secondary btn-sm mb-3">

                <i class="bi bi-arrow-left"></i>
                Kembali ke Dashboard

            </a>

            <!-- PESAN SUKSES -->
            <?php if (isset($_SESSION['pesan_sukses'])) { ?>

                <div class="alert alert-success alert-dismissible fade show">

                    <?= htmlspecialchars($_SESSION['pesan_sukses']) ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>

                </div>

                <?php unset($_SESSION['pesan_sukses']); ?>

            <?php } ?>

            <!-- PESAN ERROR -->
            <?php if (isset($_SESSION['pesan_error'])) { ?>

                <div class="alert alert-danger alert-dismissible fade show">

                    <?= htmlspecialchars($_SESSION['pesan_error']) ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"></button>

                </div>

                <?php unset($_SESSION['pesan_error']); ?>

            <?php } ?>

            <!-- ================================== -->
            <!-- FORM TAMBAH PENEMPATAN -->
            <!-- ================================== -->

            <div class="collapse mb-4" id="formPenempatan">

                <div class="card shadow-sm">

                    <div class="card-header bg-dark text-white">

                        <strong>
                            <i class="bi bi-person-plus"></i>
                            Form Penempatan Siswa
                        </strong>

                    </div>

                    <div class="card-body">

                        <form action="proses_simpan_penempatan.php"
                              method="POST">

                            <div class="row">

                                <!-- SISWA -->
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Siswa
                                    </label>

                                    <select name="siswa_id"
                                            id="siswa_id"
                                            class="form-select"
                                            required>

                                        <option value=""></option>

                                        <?php while ($s = mysqli_fetch_assoc($siswa)) { ?>

                                            <option value="<?= $s['id'] ?>">

                                                <?= htmlspecialchars(
                                                    $s['nis'] . ' - ' . $s['nama']
                                                ) ?>

                                            </option>

                                        <?php } ?>

                                    </select>

                                    <small class="text-muted">
                                        Ketik nama atau NIS untuk mencari siswa.
                                    </small>

                                </div>

                                <!-- TAHUN AJARAN -->
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Tahun Ajaran
                                    </label>

                                    <select name="tahun_ajaran_id"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            -- Pilih Tahun Ajaran --
                                        </option>

                                        <?php while ($ta = mysqli_fetch_assoc($tahun)) { ?>

                                            <option value="<?= $ta['id'] ?>">

                                                <?= htmlspecialchars($ta['nama']) ?>

                                            </option>

                                        <?php } ?>

                                    </select>

                                </div>

                                <!-- KELAS -->
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Kelas
                                    </label>

                                    <select name="kelas_id"
                                            class="form-select"
                                            required>

                                        <option value="">
                                            -- Pilih Kelas --
                                        </option>

                                        <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>

                                            <option value="<?= $k['id'] ?>">

                                                <?= htmlspecialchars(
                                                    $k['nama'] . ' - ' .
                                                    $k['tingkat'] . ' - ' .
                                                    $k['jurusan']
                                                ) ?>

                                            </option>

                                        <?php } ?>

                                    </select>

                                </div>

                                <!-- TANGGAL MULAI -->
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Tanggal Mulai
                                    </label>

                                    <input type="date"
                                           name="tanggal_mulai"
                                           class="form-control"
                                           required>

                                </div>

                                <!-- TANGGAL SELESAI -->
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Tanggal Selesai
                                    </label>

                                    <input type="date"
                                           name="tanggal_selesai"
                                           class="form-control"
                                           required>

                                </div>

                                <!-- STATUS -->
                                <div class="col-md-6 mb-3">

                                    <label class="form-label">
                                        Status
                                    </label>

                                    <select name="status_aktif"
                                            class="form-select"
                                            required>

                                        <option value="1">Aktif</option>
                                        <option value="0">Tidak Aktif</option>

                                    </select>

                                </div>

                            </div>

                            <!-- TOMBOL SIMPAN -->
                            <button type="submit"
                                    class="btn btn-primary btn-sm">

                                <i class="bi bi-save"></i>
                                Simpan Penempatan

                            </button>

                        </form>

                    </div>

                </div>

            </div>

            <!-- ================================== -->
            <!-- TABEL DATA PENEMPATAN -->
            <!-- ================================== -->

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="table-responsive">

                        <table id="tabelPenempatan"
                               class="table table-bordered table-striped table-hover align-middle w-100">

                            <thead class="table-dark">

                                <tr>
                                    <th>No.</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Kelas</th>
                                    <th>Tingkat</th>
                                    <th>Jurusan</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                </tr>

                            </thead>

                            <tbody>

                                <?php

                                $no = 1;

                                while ($row = mysqli_fetch_assoc($hasil)) {

                                ?>

                                <tr>

                                    <td><?= $no++ ?></td>

                                    <td>
                                        <?= htmlspecialchars($row['nis'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_siswa'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['tahun_ajaran'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_kelas'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['tingkat'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['jurusan'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['tanggal_mulai'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['tanggal_selesai'] ?? '-') ?>
                                    </td>

                                    <td>

                                        <?php if ($row['status_aktif'] == 1) { ?>

                                            <span class="badge bg-success">
                                                Aktif
                                            </span>

                                        <?php } else { ?>

                                            <span class="badge bg-secondary">
                                                Tidak Aktif
                                            </span>

                                        <?php } ?>

                                    </td>

                                </tr>

                                <?php } ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

<!-- ================================== -->
<!-- JAVASCRIPT -->
<!-- ================================== -->

<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- SELECT2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- DATATABLES JS -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<!-- DATATABLES BOOTSTRAP 5 JS -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>

$(document).ready(function () {

    // ==================================
    // PENCARIAN SISWA SELECT2
    // ==================================

    $('#siswa_id').select2({

        theme: 'bootstrap-5',

        placeholder: '-- Cari Nama atau NIS Siswa --',

        allowClear: true,

        width: '100%'

    });

    // ==================================
    // DATATABLES PENEMPATAN SISWA
    // ==================================

    $('#tabelPenempatan').DataTable({

        pageLength: 10,

        lengthMenu: [10, 25, 50, 100],

        order: [[0, 'asc']],

        scrollX: true,

        language: {

            search: "Cari:",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data penempatan siswa",

            infoEmpty: "Tidak ada data penempatan siswa",

            infoFiltered: "(difilter dari _MAX_ total data)",

            zeroRecords: "Data penempatan siswa tidak ditemukan",

            emptyTable: "Belum ada data penempatan siswa",

            paginate: {

                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"

            }

        }

    });

});

</script>

</body>
</html>
