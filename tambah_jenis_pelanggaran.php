<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama_pelanggaran'];
    $kategori = $_POST['kategori'];
    $poin = $_POST['poin'];
    $keterangan = $_POST['keterangan'];

    $cek = mysqli_query($koneksi, "SELECT id FROM t_pelanggaran WHERE id = '$id'");
    if (mysqli_num_rows($cek) > 0) {
        $pesan = "<div class='alert alert-danger'>ID sudah digunakan! Gunakan ID lain.</div>";
    } else {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO t_pelanggaran (id, nama, kategori, poin, keterangan) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, 'issis', $id, $nama, $kategori, $poin, $keterangan);
        if (mysqli_stmt_execute($stmt)) {
            header("Location: kelola_jenis_pelanggaran.php");
            exit;
        } else {
            $pesan = "<div class='alert alert-danger'>Gagal menyimpan data.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Jenis Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="layout/style.css">
</head>
<body>
<div class="container mt-5">
    <h2>Tambah Pelanggaran Baru</h2>
    <a href="kelola_jenis_pelanggaran.php" class="btn btn-secondary btn-sm mb-3">&larr; Kembali</a>
    <?php if (isset($pesan)) echo $pesan; ?>
    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">ID</label>
                    <input type="number" name="id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Pelanggaran</label>
                    <input type="text" name="nama_pelanggaran" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="kategori" class="form-control" value="-">
                </div>
                <div class="mb-3">
                    <label class="form-label">Poin</label>
                    <input type="number" name="poin" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" class="form-control">-</textarea>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
