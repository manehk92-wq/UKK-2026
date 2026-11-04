<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'] ?? 0;

$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_pelanggaran WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$data) {
    header("Location: kelola_jenis_pelanggaran.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_pelanggaran'];
    $kategori = $_POST['kategori'];
    $poin = $_POST['poin'];
    $keterangan = $_POST['keterangan'];

    $update = mysqli_prepare($koneksi, "UPDATE t_pelanggaran SET nama=?, kategori=?, poin=?, keterangan=? WHERE id=?");
    mysqli_stmt_bind_param($update, 'ssisi', $nama, $kategori, $poin, $keterangan, $id);
    mysqli_stmt_execute($update);

    header("Location: kelola_jenis_pelanggaran.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Jenis Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Pelanggaran</h2>
    <a href="kelola_jenis_pelanggaran.php" class="btn btn-secondary btn-sm mb-3">&larr; Kembali</a>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">ID (tidak dapat diubah)</label>
                    <input type="number" class="form-control" value="<?= $data['id'] ?>" disabled>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Pelanggaran</label>
                    <input type="text" name="nama_pelanggaran" class="form-control" value="<?= htmlspecialchars($data['nama']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="<?= htmlspecialchars($data['kategori'] ?? '-') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Poin</label>
                    <input type="number" name="poin" class="form-control" value="<?= $data['poin'] ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control"><?= htmlspecialchars($data['keterangan'] ?? '-') ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
