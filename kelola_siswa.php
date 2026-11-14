<?php
// kelola_siswa.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_siswa ORDER BY nama ASC";
$hasil = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Siswa</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables Bootstrap 5 CSS -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom Style -->
    <link rel="stylesheet" href="layout/style.css">
    <style>
        .student-page-title {
            margin: 0;
            color: #17233f;
            font-size: clamp(1.5rem, 2.5vw, 2rem);
            font-weight: 700;
        }

        .student-table-card {
            overflow: hidden;
            border: 1px solid #e8ecf4;
            border-radius: 14px;
            background: #fff;
        }

        .student-table-card .card-body {
            padding: 20px;
        }

        #tabelSiswa {
            min-width: 1120px;
            margin-bottom: 0;
            border-color: #e2e8f0;
            color: #263248;
            font-size: 0.9rem;
        }

        #tabelSiswa thead th {
            padding: 14px 12px;
            border-bottom: 0;
            background: #17233f;
            color: #fff;
            font-size: 0.82rem;
            font-weight: 600;
            vertical-align: middle;
        }

        #tabelSiswa tbody td {
            padding: 12px;
            vertical-align: middle;
        }

        #tabelSiswa tbody tr:hover > * {
            background-color: #f0f5ff;
        }

        #tabelSiswa th:nth-child(1),
        #tabelSiswa td:nth-child(1),
        #tabelSiswa th:nth-child(2),
        #tabelSiswa td:nth-child(2),
        #tabelSiswa th:nth-child(3),
        #tabelSiswa td:nth-child(3),
        #tabelSiswa th:nth-child(5),
        #tabelSiswa td:nth-child(5),
        #tabelSiswa th:nth-child(6),
        #tabelSiswa td:nth-child(6),
        #tabelSiswa th:nth-child(8),
        #tabelSiswa td:nth-child(8),
        #tabelSiswa th:nth-child(9),
        #tabelSiswa td:nth-child(9) {
            white-space: nowrap;
        }

        #tabelSiswa td:nth-child(7) {
            min-width: 200px;
            line-height: 1.5;
        }

        #tabelSiswa td .btn {
            margin: 2px 2px 2px 0;
        }

        .dt-container .dt-layout-row {
            align-items: center;
            gap: 12px;
        }

        .dt-container .dt-search input,
        .dt-container .dt-length select {
            min-height: 38px;
            border-color: #d7deea;
            border-radius: 8px;
        }

        .dt-container .dt-search input {
            margin-left: 8px;
        }

        .dt-container .dt-info {
            color: #667085;
            font-size: 0.875rem;
        }

        .dt-container .pagination {
            gap: 4px;
        }

        .dt-container .page-link {
            border-radius: 7px;
        }

        @media (max-width: 767.98px) {
            .student-table-card .card-body {
                padding: 14px;
            }

            .student-page-header {
                align-items: flex-start !important;
                flex-direction: column;
            }
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

            <!-- HEADER JUDUL & TOMBOL -->
            <div class="student-page-header d-flex justify-content-between align-items-center mb-4 gap-3">
                <h1 class="student-page-title">Kelola Siswa</h1>
                <a href="tambah_siswa.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Siswa
                </a>
            </div>

            <!-- TOMBOL KEMBALI -->
            <a href="dashboard.php" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>

            <!-- CARD TABEL -->
            <div class="card student-table-card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelSiswa" class="table table-striped table-bordered table-hover align-middle w-100">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>NIS</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                    <th>Status Aktif</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($row = mysqli_fetch_assoc($hasil)) {
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= htmlspecialchars($row['nis']) ?></td>
                                    <td><?= htmlspecialchars($row['nisn']) ?></td>
                                    <td><?= htmlspecialchars($row['nama']) ?></td>
                                    <td>
                                        <?php
                                        if ($row['jenis_kelamin'] == 'L') {
                                            echo 'Laki-laki';
                                        } elseif ($row['jenis_kelamin'] == 'P') {
                                            echo 'Perempuan';
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    <td><?= htmlspecialchars($row['tanggal_lahir'] ?? '-') ?></td>
                                    <td><?= htmlspecialchars($row['alamat'] ?? '-') ?></td>
                                    <td>
                                        <?php if ($row['status_aktif'] == 1) { ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php } else { ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php } ?>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="edit_siswa.php?id=<?= urlencode($row['id']) ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="hapus_siswa.php?id=<?= urlencode($row['id']) ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus data siswa ini?');">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
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

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<!-- DataTables Bootstrap 5 JS -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- KONFIGURASI DATATABLES -->
<script>
$(document).ready(function () {
    $('#tabelSiswa').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[3, 'asc']],
        scrollX: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data siswa",
            infoEmpty: "Tidak ada data siswa",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data siswa tidak ditemukan",
            emptyTable: "Belum ada data siswa",
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
