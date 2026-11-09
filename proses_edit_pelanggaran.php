
<?php
// proses_edit_pelanggaran_kategori.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    // Pastikan data dikirim melalui POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Akses tidak valid!");
    }

    // Ambil data dari form
    $id = (int) ($_POST['id'] ?? 0);
    $nama = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status_aktif = $_POST['status_aktif'] ?? '';

    // Validasi data
    if ($id <= 0) {
        throw new Exception("ID kategori tidak valid!");
    }

    if ($nama === '') {
        throw new Exception("Nama kategori wajib diisi!");
    }

    if ($status_aktif === '') {
        throw new Exception("Status aktif wajib dipilih!");
    }

    // Query update menggunakan prepared statement
    $sql = "UPDATE t_pelanggaran_kategori SET
                nama = ?,
                deskripsi = ?,
                status_aktif = ?,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?";

    $stmt = mysqli_prepare($koneksi, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "sssi",
        $nama,
        $deskripsi,
        $status_aktif,
        $id
    );

    // Jalankan query
    mysqli_stmt_execute($stmt);

    // Tutup statement
    mysqli_stmt_close($stmt);

    // Kembali ke halaman kelola kategori
    header("Location: kelola_pelanggaran_kategori.php");
    exit;

} catch (Exception $e) {

    echo "<script>
        alert(" . json_encode(
            "Gagal mengubah data kategori pelanggaran: " . $e->getMessage()
        ) . ");
        window.history.back();
    </script>";

} catch (mysqli_sql_exception $e) {

    echo "<script>
        alert(" . json_encode(
            "Terjadi kesalahan database: " . $e->getMessage()
        ) . ");
        window.history.back();
    </script>";
}
