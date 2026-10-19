
<?php
// proses_edit_walikelas.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan form dikirim menggunakan POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit('Akses tidak valid. Silakan gunakan form edit wali kelas.');
}

// Ambil dan periksa data dari form
$id = (int) ($_POST['id'] ?? 0);
$tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);
$kelas_id = (int) ($_POST['kelas_id'] ?? 0);
$guru_id = (int) ($_POST['guru_id'] ?? 0);
$tanggal_mulai = trim($_POST['tanggal_mulai'] ?? '');
$tanggal_selesai = trim($_POST['tanggal_selesai'] ?? '');
$status_aktif = (int) ($_POST['status_aktif'] ?? 0);

// Validasi data wajib
if (
    $id <= 0 ||
    $tahun_ajaran_id <= 0 ||
    $kelas_id <= 0 ||
    $guru_id <= 0 ||
    $tanggal_mulai === '' ||
    $tanggal_selesai === ''
) {
    exit('Data belum lengkap. Silakan kembali dan isi form edit dengan benar.');
}

// Validasi tanggal
if ($tanggal_selesai < $tanggal_mulai) {
    exit('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
}

// Pastikan status aktif bernilai 0 atau 1
if (!in_array($status_aktif, [0, 1], true)) {
    exit('Status aktif tidak valid.');
}

// Pastikan nama tabel sesuai dengan database kamu
$sql = "UPDATE t_walikelas SET
        tahun_ajaran_id = ?,
        kelas_id = ?,
        guru_id = ?,
        tanggal_mulai = ?,
        tanggal_selesai = ?,
        status_aktif = ?,
        updated_at = CURRENT_TIMESTAMP
        WHERE id = ?";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    exit('Gagal menyiapkan query: ' . mysqli_error($koneksi));
}

mysqli_stmt_bind_param(
    $stmt,
    "iiissii",
    $tahun_ajaran_id,
    $kelas_id,
    $guru_id,
    $tanggal_mulai,
    $tanggal_selesai,
    $status_aktif,
    $id
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    header("Location: kelola_walikelas.php");
    exit;
}

echo "Gagal mengubah data wali kelas: "
    . mysqli_stmt_error($stmt);

mysqli_stmt_close($stmt);
?>
