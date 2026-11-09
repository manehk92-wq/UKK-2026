
<?php
session_start();

require_once 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['pesan_error'] = 'Email dan password wajib diisi!';
    header("Location: login.php");
    exit;
}

// Cari akun berdasarkan email
$sql = "SELECT id, name, email, password, role
        FROM t_users
        WHERE email = ?
        LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    error_log(mysqli_error($koneksi));
    $_SESSION['pesan_error'] = 'Terjadi kesalahan database!';
    header("Location: login.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result(
    $stmt,
    $id,
    $name,
    $email_db,
    $password_db,
    $role
);

$ada_user = mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);

if (!$ada_user) {
    $_SESSION['pesan_error'] = 'Email tidak ditemukan!';
    header("Location: login.php");
    exit;
}

// Cek password hash atau teks biasa
$password_valid = false;

if (password_verify($password, $password_db)) {
    $password_valid = true;
} elseif (hash_equals((string)$password_db, $password)) {

    // Migrasi password teks biasa menjadi hash
    $hash_baru = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $update = mysqli_prepare(
        $koneksi,
        "UPDATE t_users SET password = ? WHERE id = ?"
    );

    if ($update) {
        mysqli_stmt_bind_param(
            $update,
            "si",
            $hash_baru,
            $id
        );

        mysqli_stmt_execute($update);
        mysqli_stmt_close($update);

        $password_valid = true;
    }
}

if (!$password_valid) {
    $_SESSION['pesan_error'] = 'Kata sandi salah!';
    header("Location: login.php");
    exit;
}

// LOGIN BERHASIL
session_regenerate_id(true);

$_SESSION['login'] = true;
$_SESSION['id_user'] = $id;
$_SESSION['id'] = $id;
$_SESSION['name'] = $name;
$_SESSION['role'] = $role;

// Masuk dashboard
header("Location: dashboard.php");
exit;
?>
