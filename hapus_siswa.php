<?php
// hapus_siswa.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Siswa tidak ditemukan!'); window.location='kelola_siswa.php';</script>";
    exit;
}

$id_siswa = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil nama siswa
$ambil = mysqli_query($koneksi, "SELECT nama FROM t_siswa WHERE id = '$id_siswa'");
if (!$ambil || mysqli_num_rows($ambil) === 0) {
    echo "<script>alert('Data siswa tidak ditemukan!'); window.location='kelola_siswa.php';</script>";
    exit;
}

$siswa = mysqli_fetch_assoc($ambil);
$nama_siswa = $siswa['nama'];

// Hapus data
$hapus = mysqli_query($koneksi, "DELETE FROM t_siswa WHERE id = '$id_siswa'");

if ($hapus) {
    // Catat log — cek dulu apakah tabel ada
    $cek_tabel = mysqli_query($koneksi, "SHOW TABLES LIKE 'tbl_log'");
    if (mysqli_num_rows($cek_tabel) > 0) {
        $id_user = $_SESSION['id_user'] ?? 0;
        $aktivitas = "Menghapus data siswa: $nama_siswa";
        mysqli_query($koneksi, "INSERT INTO tbl_log (id_user, aktivitas) VALUES ('$id_user', '$aktivitas')");
    }

    echo "<script>alert('Data berhasil dihapus!'); window.location='kelola_siswa.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus: " . addslashes(mysqli_error($koneksi)) . "'); history.back();</script>";
}
?>
