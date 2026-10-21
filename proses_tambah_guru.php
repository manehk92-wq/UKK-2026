
<?php
// proses_tambah_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Pastikan request berasal dari form POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Akses tidak valid. Silakan gunakan form tambah guru.");
    }

    // Ambil data form
    $user_id = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $status_aktif = (int) ($_POST['status_aktif'] ?? 1);

    // Validasi input
    if (!$user_id || $user_id <= 0) {
        throw new Exception("Pilih akun guru yang valid terlebih dahulu.");
    }

    if ($nip === '' || $nama === '' || $email === '') {
        throw new Exception("NIP, nama, dan email wajib diisi.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Format email tidak valid.");
    }

    if (!in_array($status_aktif, [0, 1], true)) {
        throw new Exception("Status aktif tidak valid.");
    }

    // Periksa apakah akun ada di tabel t_users
    $cek = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_users WHERE id = ? AND role = 'guru'"
    );

    mysqli_stmt_bind_param($cek, "i", $user_id);
    mysqli_stmt_execute($cek);

    $result = mysqli_stmt_get_result($cek);
    $akun = mysqli_fetch_assoc($result);

    mysqli_stmt_close($cek);

    if (!$akun) {
        throw new Exception(
            "Akun guru tidak ditemukan. Pilih akun guru yang tersedia di t_users."
        );
    }

    // Cek apakah akun sudah terhubung ke data guru
    $cek = mysqli_prepare(
        $koneksi,
        "SELECT user_id FROM t_guru WHERE user_id = ?"
    );

    mysqli_stmt_bind_param($cek, "i", $user_id);
    mysqli_stmt_execute($cek);

    $result = mysqli_stmt_get_result($cek);
    $sudah_ada = mysqli_num_rows($result) > 0;

    mysqli_stmt_close($cek);

    if ($sudah_ada) {
        throw new Exception(
            "Akun ini sudah memiliki data guru. Silakan pilih akun lain."
        );
    }

    // Simpan data guru
    $sql = "INSERT INTO t_guru
            (user_id, nip, nama, email, status_aktif)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($koneksi, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "isssi",
        $user_id,
        $nip,
        $nama,
        $email,
        $status_aktif
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Berhasil
    header("Location: kelola_guru.php?pesan=berhasil");
    exit;

} catch (Throwable $e) {
    http_response_code(400);

    echo '<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gagal Menambahkan Guru</title>
    </head>
    <body style="font-family:Arial,sans-serif;padding:30px;">
        <h2>Gagal Menambahkan Data Guru</h2>
        <p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>
        <button onclick="history.back()">Kembali ke Form</button>
    </body>
    </html>';
}
?>
