
<?php
// proses_edit_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Akses tidak valid.");
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $status_aktif = $_POST['status_aktif'] ?? '';

    $password = $_POST['password'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

    // Validasi data
    if (!$id || $id <= 0) {
        throw new Exception("ID guru tidak valid.");
    }

    if ($nip === '' || $nama === '' || $email === '') {
        throw new Exception("NIP, nama, dan email wajib diisi.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Format email tidak valid.");
    }

    if (!in_array($status_aktif, ['Aktif', 'Tidak Aktif'], true)) {
        throw new Exception("Status aktif tidak valid.");
    }

    // Password boleh kosong, tetapi jika diisi harus cocok
    if ($password !== '' || $konfirmasi_password !== '') {

        if ($password === '' || $konfirmasi_password === '') {
            throw new Exception(
                "Password baru dan konfirmasi password harus diisi keduanya."
            );
        }

        if ($password !== $konfirmasi_password) {
            throw new Exception(
                "Password baru dan konfirmasi password tidak sama."
            );
        }

        if (strlen($password) < 6) {
            throw new Exception(
                "Password minimal harus 6 karakter."
            );
        }
    }

    // Ambil user_id dari data guru yang sedang diedit
    $stmt = mysqli_prepare(
        $koneksi,
        "SELECT user_id FROM t_guru WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $guru = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if (!$guru) {
        throw new Exception("Data guru tidak ditemukan.");
    }

    $user_id = (int) $guru['user_id'];

    mysqli_begin_transaction($koneksi);

    // Update data guru
    $sql = "UPDATE t_guru SET
                nip = ?,
                nama = ?,
                email = ?,
                status_aktif = ?
            WHERE id = ?";

    $stmt = mysqli_prepare($koneksi, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "ssssi",
        $nip,
        $nama,
        $email,
        $status_aktif,
        $id
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Jika password diisi, perbarui password akun guru
    if ($password !== '') {

        if ($user_id <= 0) {
            throw new Exception(
                "Akun pengguna guru belum terhubung."
            );
        }

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
                "Akun guru pada tabel t_users tidak ditemukan."
            );
        }

        // Enkripsi password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare(
            $koneksi,
            "UPDATE t_users SET password = ? WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $password_hash,
            $user_id
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    mysqli_commit($koneksi);

    header("Location: kelola_guru.php?pesan=berhasil");
    exit;

} catch (Throwable $e) {

    if (isset($koneksi) && $koneksi instanceof mysqli) {
        try {
            mysqli_rollback($koneksi);
        } catch (Throwable $rollback_error) {
            // Abaikan jika transaksi belum dimulai.
        }
    }

    http_response_code(400);

    echo '<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gagal Mengedit Guru</title>
    </head>
    <body style="font-family:Arial,sans-serif;padding:30px;">
        <h2>Gagal Mengubah Data Guru</h2>
        <p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>
        <button onclick="history.back()">Kembali ke Form</button>
    </body>
    </html>';
}
?>
