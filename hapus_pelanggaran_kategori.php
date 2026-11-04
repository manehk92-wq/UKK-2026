<?php
// hapus_pelanggaran_kategori.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Cek apakah ada ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('ID Kategori tidak ditemukan!'); window.location='kelola_pelanggaran_kategori.php';</script>";
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Ambil nama kategori sebelum dihapus
$ambil = mysqli_query($koneksi, "SELECT nama FROM t_pelanggaran_kategori WHERE id = '$id'");
if (!$ambil || mysqli_num_rows($ambil) === 0) {
    echo "<script>alert('Data kategori tidak ditemukan!'); window.location='kelola_pelanggaran_kategori.php';</script>";
    exit;
}

$data = mysqli_fetch_assoc($ambil);
$nama_kategori = $data['nama'];

// Hapus data
$hapus = mysqli_query($koneksi, "DELETE FROM t_pelanggaran_kategori WHERE id = '$id'");

if ($hapus) {
    // Catat ke log jika tabel ada
    $cek_log = mysqli_query($koneksi, "SHOW TABLES LIKE 'tbl_log'");
    if (mysqli_num_rows($cek_log) > 0) {
        $id_user = $_SESSION['id_user'] ?? 0;
        $aktivitas = "Menghapus kategori pelanggaran: $nama_kategori";
        mysqli_query($koneksi, "INSERT INTO tbl_log (id_user, aktivitas) VALUES ('$id_user', '$aktivitas')");
    }

    echo "<script>alert('✅ Kategori berhasil dihapus!'); window.location='kelola_pelanggaran_kategori.php';</script>";
} else {
    echo "<script>alert('❌ Gagal: " . addslashes(mysqli_error($koneksi)) . "'); history.back();</script>";
}
?>
