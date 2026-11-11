
<?php
// proses_tambah_guru.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    // Pastikan request berasal dari form POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Akses tidak valid.");
    }

    // Ambil data form
    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';
    $status_aktif = filter_input(
        INPUT_POST,
        'status_aktif',
        FILTER_VALIDATE_INT
    );

    // Validasi input
    if (
        $nip === '' ||
        $nama === '' ||
        $email === '' ||
        $password === '' ||
        $konfirmasi_password === ''
    ) {
        throw new Exception("Semua data wajib diisi.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Format email tidak valid.");
    }

    if (strlen($password) < 8) {
        throw new Exception("Password minimal 8 karakter.");
    }

    if ($password !== $konfirmasi_password) {
        throw new Exception("Konfirmasi password tidak sama.");
    }

    if (
        $status_aktif === false ||
        !in_array($status_aktif, [0, 1], true)
    ) {
        throw new Exception("Status aktif tidak valid.");
    }

    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    // Periksa email sudah digunakan atau belum
    $cek = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_users WHERE email = ? LIMIT 1"
    );

    mysqli_stmt_bind_param($cek, "s", $email);
    mysqli_stmt_execute($cek);

    $result = mysqli_stmt_get_result($cek);
    $email_ada = mysqli_num_rows($result) > 0;

    mysqli_stmt_close($cek);

    if ($email_ada) {
        throw new Exception("Email sudah digunakan oleh akun lain.");
    }

    // Periksa NIP sudah digunakan atau belum
    $cek = mysqli_prepare(
        $koneksi,
        "SELECT id FROM t_guru WHERE nip = ? LIMIT 1"
    );

    mysqli_stmt_bind_param($cek, "s", $nip);
    mysqli_stmt_execute($cek);

    $result = mysqli_stmt_get_result($cek);
    $nip_ada = mysqli_num_rows($result) > 0;

    mysqli_stmt_close($cek);

    if ($nip_ada) {
        throw new Exception("NIP guru sudah terdaftar.");
    }

    // Enkripsi password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    if ($password_hash === false) {
        throw new Exception("Gagal mengenkripsi password.");
    }

    // Buat akun guru di tabel t_users
    $sql_user = "INSERT INTO t_users
                 (name, email, password, role)
                 VALUES (?, ?, ?, 'guru')";

    $stmt_user = mysqli_prepare($koneksi, $sql_user);

    mysqli_stmt_bind_param(
        $stmt_user,
        "sss",
        $nama,
        $email,
        $password_hash
    );

    mysqli_stmt_execute($stmt_user);

    // Ambil ID akun yang baru dibuat
    $user_id = mysqli_insert_id($koneksi);

    mysqli_stmt_close($stmt_user);

    if (!$user_id) {
        throw new Exception("Gagal membuat akun guru.");
    }

    // Simpan data guru dan hubungkan dengan akun
    $sql_guru = "INSERT INTO t_guru
                 (user_id, nip, nama, email, status_aktif)
                 VALUES (?, ?, ?, ?, ?)";

    $stmt_guru = mysqli_prepare($koneksi, $sql_guru);

    mysqli_stmt_bind_param(
        $stmt_guru,
        "isssi",
        $user_id,
        $nip,
        $nama,
        $email,
        $status_aktif
    );

    mysqli_stmt_execute($stmt_guru);
    mysqli_stmt_close($stmt_guru);

    // Simpan transaksi
    mysqli_commit($koneksi);

    header("Location: kelola_guru.php?pesan=berhasil");
    exit;

} catch (Throwable $e) {

    // Batalkan seluruh perubahan jika gagal
    if (isset($koneksi)) {
        try {
            mysqli_rollback($koneksi);
        } catch (Throwable $rollback_error) {
            // Abaikan jika transaksi belum dimulai
        }
    }

    http_response_code(400);

    echo '<!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gagal Menambahkan Guru</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">
        <div class="container mt-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="text-danger">Gagal Menambahkan Data Guru</h4>
                    <p>' . htmlspecialchars(
                        $e->getMessage(),
                        ENT_QUOTES,
                        'UTF-8'
                    ) . '</p>
                    <a href="tambah_guru.php" class="btn btn-primary">
                        Kembali ke Form
                    </a>
                </div>
            </div>
        </div>
    </body>
    </html>';
}
?>
