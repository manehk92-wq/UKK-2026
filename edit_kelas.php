<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("location:../index.php");
    exit;
}

$daftar_tingkat = [10, 11, 12];
$daftar_jurusan = [
    "PPLG", "TKRO", "TKJ", "TSM", 
    "AKL", "MPLB", "BDP", "KL"
];

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM t_kelas WHERE id_kelas='$id'"));

if (isset($_POST['update'])) {
    $tingkat = $_POST['tingkat'];
    $jurusan = $_POST['jurusan'];
    $nama_kelas = htmlspecialchars($_POST['nama_kelas']);

    $query = mysqli_query($koneksi, "UPDATE t_kelas SET 
        tingkat='$tingkat', 
        jurusan='$jurusan', 
        nama_kelas='$nama_kelas' 
        WHERE id_kelas='$id'");

    if ($query) {
        echo "<script>alert('Data berhasil diperbarui!'); location.href='kelas.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui data!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Kelas</title>
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h4>Edit Data Kelas</h4>
    <hr>
    <div class="card col-md-6">
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label>Tingkat</label>
                    <select name="tingkat" class="form-control" required>
                        <option value="">-- Pilih Tingkat --</option>
                        <?php foreach ($daftar_tingkat as $t): ?>
                            <option value="<?= $t ?>" <?= $data['tingkat'] == $t ? 'selected' : '' ?>>
                                <?= $t ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Jurusan</label>
                    <select name="jurusan" class="form-control" required>
                        <option value="">-- Pilih Jurusan --</option>
                        <?php foreach ($daftar_jurusan as $j): ?>
                            <option value="<?= $j ?>" <?= $data['jurusan'] == $j ? 'selected' : '' ?>>
                                <?= $j ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Nama Kelas</label>
                    <input type="text" name="nama_kelas" value="<?= $data['nama_kelas'] ?>" class="form-control" required>
                </div>

                <button type="submit" name="update" class="btn btn-primary">Update</button>
                <a href="kelas.php" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
<script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
