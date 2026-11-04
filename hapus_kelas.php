<?php
// hapus_kelas.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Kelas tidak ditemukan!'); window.location='kelola_kelas.php';</script>";
    exit;
}

$id_kelas = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil nama kelas — GANTI 'nama' SESUAI KOLOM DI TABEL KAMU
$ambil = mysqli_query($koneksi, "SELECT nama FROM t_kelas WHERE id = '$id_kelas'");
if (!$ambil || mysqli_num_rows($ambil) === 0) {
    echo "<script>alert('Data kelas tidak ditemukan!'); window.location='kelola_kelas.php';</script>";
    exit;
}

$kelas = mysqli_fetch_assoc($ambil);
$nama_kelas = $kelas['nama']; // ← sesuaikan dengan kolom di tabel

// Hapus data
$hapus = mysqli_query($koneksi, "DELETE FROM t_kelas WHERE id = '$id_kelas'");

if ($hapus) {
    // Catat log
    $cek_tabel = mysqli_query($koneksi, "SHOW TABLES LIKE 'tbl_log'");
    if (mysqli_num_rows($cek_tabel) > 0) {
        $id_user = $_SESSION['id_user'] ?? 0;
        $aktivitas = "Menghapus data kelas: $nama_kelas";
        mysqli_query($koneksi, "INSERT INTO tbl_log (id_user, aktivitas) VALUES ('$id_user', '$aktivitas')");
    }

    echo "<script>alert('Data berhasil dihapus!'); window.location='kelola_kelas.php';</script>";
} else {
    echo "<script>alert('Gagal menghapus: " . addslashes(mysqli_error($koneksi)) . "'); history.back();</script>";
}
?>
