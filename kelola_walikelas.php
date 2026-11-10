<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$sql = "SELECT
            w.id,
            ta.nama AS tahun_ajaran,
            k.nama AS nama_kelas,
            g.nama AS nama_guru,
            w.tanggal_mulai,
            w.tanggal_selesai,
            w.status_aktif,
            w.created_at,
            w.updated_at
        FROM t_wali_kelas w
        LEFT JOIN t_tahun_ajaran ta ON w.tahun_ajaran_id = ta.id
        LEFT JOIN t_kelas k ON w.kelas_id = k.id
        LEFT JOIN t_guru g ON w.guru_id = g.id
        ORDER BY w.id DESC";
$hasil = mysqli_query($koneksi, $sql);

if (!$hasil) {
    die('Gagal mengambil data wali kelas: ' . mysqli_error($koneksi));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Wali Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include 'layout/sidebar.php'; ?>

        <main class="menu-page-content">
            <div class="d-flex justify-content-between align-items-center gap-3 mb-4">
                <h1 class="h2 mb-0">Kelola Wali Kelas</h1>
                <a href="tambah_walikelas.php" class="btn btn-primary text-nowrap">
                    <i class="bi bi-plus-circle"></i> Tambah Wali Kelas
                </a>
            </div>

            <a href="dashboard.php" class="btn btn-secondary mb-3">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelWaliKelas" class="table table-striped table-bordered table-hover align-middle w-100">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Kelas</th>
                                    <th>Wali Kelas</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($hasil)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['tahun_ajaran'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['nama_guru'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['tanggal_mulai'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['tanggal_selesai'] ?? '-') ?></td>
                                        <td>
                                            <?php if ((int)$row['status_aktif'] === 1): ?>
                                                <span class="badge bg-success">Aktif</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Tidak Aktif</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-nowrap">
                                            <a href="edit_walikelas.php?id=<?= urlencode((string)$row['id']) ?>"
                                               class="btn btn-warning btn-sm">
                                                <i class="bi bi-pencil-square"></i> Edit
                                            </a>
                                            <a href="hapus_walikelas.php?id=<?= urlencode((string)$row['id']) ?>"
                                               class="btn btn-danger btn-sm"
                                               onclick="return confirm('Yakin ingin menghapus data wali kelas ini?');">
                                                <i class="bi bi-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
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
$(function () {
    $('#tabelWaliKelas').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50, 100],
        order: [[1, 'asc']],
        autoWidth: false,
        columnDefs: [{
            targets: [0, 7],
            orderable: false,
            searchable: false
        }],
        language: {
            search: 'Cari:',
            searchPlaceholder: 'Cari wali kelas...',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data wali kelas',
            infoEmpty: 'Tidak ada data wali kelas',
            infoFiltered: '(difilter dari _MAX_ total data)',
            zeroRecords: 'Data wali kelas tidak ditemukan',
            emptyTable: 'Belum ada data wali kelas',
            paginate: {
                first: 'Pertama',
                last: 'Terakhir',
                next: 'Berikutnya',
                previous: 'Sebelumnya'
            }
        }
    });
});
</script>
</body>
</html>
