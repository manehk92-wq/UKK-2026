<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$sql = "SELECT * FROM t_pelanggaran ORDER BY id DESC";
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
    <title>Kelola Jenis Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include 'layout/sidebar.php'; ?>
        <main class="col-md-9 col-lg-10 p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Kelola Jenis Pelanggaran</h1>
                <a href="tambah_jenis_pelanggaran.php" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Pelanggaran
                </a>
            </div>
            <a href="dashboard.php" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelPelanggaran" class="table table-striped table-bordered table-hover align-middle w-100">
                            <thead class="table-dark">
                                <tr>
                                    <th>No.</th>
                                    <th>ID</th>
                                    <th>Nama Pelanggaran</th>
                                    <th>Kategori</th>
                                    <th>Poin</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                if (mysqli_num_rows($hasil) > 0):
                                    while ($row = mysqli_fetch_assoc($hasil)):
                                        $nama = $row['nama'] ?? '-';
                                        $kategori = $row['kategori'] ?? '-';
                                        $poin = $row['poin'] ?? 0;
                                        $keterangan = $row['keterangan'] ?? '-';
                                ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= htmlspecialchars($nama) ?></td>
                                    <td><?= htmlspecialchars($kategori) ?></td>
                                    <td><?= $poin ?></td>
                                    <td><?= htmlspecialchars($keterangan) ?></td>
                                    <td class="text-nowrap">
                                        <a href="edit_jenis_pelanggaran.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </a>
                                        <a href="hapus_jenis_pelanggaran.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus data ini?');">
                                            <i class="bi bi-trash"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php
                                    endwhile;
                                else:
                                ?>
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data pelanggaran.</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
    $('#tabelPelanggaran').DataTable({
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        order: [[1, 'desc']],
        scrollX: true,
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Data tidak ditemukan",
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
