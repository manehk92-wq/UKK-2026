<?php
// edit_pelanggaran.php

session_start();
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'] ?? '';

if (!ctype_digit((string)$id) || $id === '') {
    echo "<script>alert('ID tidak ditemukan!'); window.location='kelola_pelanggaran.php';</script>";
    exit;
}

// Ambil data pelanggaran berdasarkan ID
$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_pelanggaran WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);
mysqli_stmt_close($stmt);

if (!$data) {
    echo "<script>alert('Data pelanggaran tidak ditemukan!'); window.location='kelola_pelanggaran.php';</script>";
    exit;
}

// Proses simpan perubahan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pelanggaran = trim($_POST['nama_pelanggaran'] ?? '');
    $id_kategori = trim($_POST['id_kategori'] ?? '');
    $poin = trim($_POST['poin'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');

    if (empty($nama_pelanggaran) || empty($id_kategori) || empty($poin)) {
        $pesan_error = "Nama pelanggaran, kategori, dan poin wajib diisi!";
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_pelanggaran SET nama_pelanggaran = ?, id_kategori = ?, poin = ?, keterangan = ? WHERE id = ?"
        );
        mysqli_stmt_bind_param($stmt, "sisss", $nama_pelanggaran, $id_kategori, $poin, $keterangan, $id);
        
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            echo "<script>alert('Data berhasil diperbarui!'); window.location='kelola_pelanggaran.php';</script>";
            exit;
        } else {
            $pesan_error = "Gagal menyimpan perubahan!";
        }
        mysqli_stmt_close($stmt);
    }
}

// Ambil daftar kategori untuk dropdown
$kategori = mysqli_query($koneksi, "SELECT * FROM t_pelanggaran_kategori ORDER BY nama ASC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h1>Edit Pelanggaran</h1>
    <a href="kelola_pelanggaran.php" class="btn btn-secondary mb-3">Kembali</a>

    <?php if (!empty($pesan_error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($pesan_error) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Nama Pelanggaran</label>
            <input type="text" name="nama_pelanggaran" class="form-control" 
                   value="<?= htmlspecialchars($data['nama_pelanggaran'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="id_kategori" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while ($k = mysqli_fetch_assoc($kategori)): ?>
                <option value="<?= $k['id'] ?>" 
                    <?= ($data['id_kategori'] ?? '') == $k['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($k['nama']) ?>
                </option>
                <?php endwhile; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Poin</label>
            <input type="number" name="poin" class="form-control" 
                   value="<?= htmlspecialchars($data['poin'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3"><?= htmlspecialchars($data['keterangan'] ?? '') ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
