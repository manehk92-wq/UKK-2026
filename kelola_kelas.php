
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT * FROM t_kelas ORDER BY nama ASC";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Kelas</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <!-- CSS Website untuk sidebar -->
    <link rel="stylesheet" href="layout/style.css">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h1 class="mb-3">Kelola Kelas</h1>

            <!-- TOMBOL NAVIGASI -->
            <div class="d-flex justify-content-between align-items-center mb-3">

                <a href="dashboard.php" class="btn btn-secondary btn-sm">
                    Kembali ke Dashboard
                </a>

                <a href="tambah_kelas.php" class="btn btn-primary btn-sm">
                    + Tambah Kelas
                </a>

            </div>

            <!-- TABEL DATA KELAS -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">

                        <table id="tabelKelas"
                               class="table table-bordered table-striped table-hover align-middle w-100">

                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Kelas</th>
                                    <th>Tingkat</th>
                                    <th>Jurusan</th>
                                    <th>Status Aktif</th>
                                    <th>Dibuat</th>
                                    <th>Diubah</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php while ($row = mysqli_fetch_assoc($hasil)) { ?>

                                <tr>
                                    <td>
                                        <?= htmlspecialchars((string)$row['id']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['nama']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['tingkat']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['jurusan']) ?>
                                    </td>

                                    <td>
                                        <?php if ($row['status_aktif'] == 1) { ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php } else { ?>
                                            <span class="badge bg-secondary">Tidak Aktif</span>
                                        <?php } ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['created_at']) ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars((string)$row['updated_at']) ?>
                                    </td>

                                    <td class="text-nowrap">
                                        <a href="edit_kelas.php?id=<?= urlencode((string)$row['id']) ?>"
                                           class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <a href="hapus_kelas.php?id=<?= urlencode((string)$row['id']) ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus data kelas ini?')">
                                            Hapus
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

<!-- DataTables -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Pengaturan DataTables -->
<script>
$(document).ready(function () {

    $('#tabelKelas').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50, 100],

        ordering: true,

        searching: true,

        info: true,

        autoWidth: false,

        columnDefs: [
            {
                targets: 7,
                orderable: false,
                searchable: false
            }
        ],

        language: {
            search: "Cari:",
            searchPlaceholder: "Cari data kelas...",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data kelas",
            infoEmpty: "Tidak ada data kelas",
            infoFiltered: "(difilter dari _MAX_ total data)",
            zeroRecords: "Data kelas tidak ditemukan",
            emptyTable: "Belum ada data kelas",

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
