<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("location:../index.php");
    exit;
}

// Daftar pilihan tingkat dan jurusan
$daftar_tingkat = [10, 11, 12];
$daftar_jurusan = [
    "PPLG", "TKRO", "TKJ", "TSM", 
    "AKL", "MPLB", "BDP", "KL"
];

if (isset($_POST['simpan'])) {
    $tingkat = $_POST['tingkat'];
    $jurusan = $_POST['jurusan'];
    $nama_kelas = htmlspecialchars($_POST['nama_kelas']);

    $query = mysqli_query($koneksi, "INSERT INTO t_kelas (tingkat, jurusan, nama_kelas) 
        VALUES ('$tingkat', '$jurusan', '$nama_kelas')");

    if ($query) {
        echo "<script>alert('Data kelas berhasil ditambahkan!'); location.href='kelas.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Kelas</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h4>Tambah Data Kelas</h4>
    <hr>
    <div class="card col-md-6">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label>Tingkat</label>
                    <select name="tingkat" class="form-control" required>
                        <option value="">-- Pilih Tingkat --</option>
                        <?php foreach ($daftar_tingkat as $t): ?>
                            <option value="<?= $t ?>"><?= $t ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Jurusan</label>
                    <select name="jurusan" class="form-control" required>
                        <option value="">-- Pilih Jurusan --</option>
                        <?php foreach ($daftar_jurusan as $j): ?>
                            <option value="<?= $j ?>"><?= $j ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: PPLG 1" required>
                </div>

                <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                <a href="kelas.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
