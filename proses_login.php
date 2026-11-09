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

// Ambil data dari t_users + t_guru
$sql = "SELECT u.id, u.name, u.email, u.password, u.role,
               g.nip, g.nama AS nama_guru
        FROM t_users u
        LEFT JOIN t_guru g ON u.id = g.user_id
        WHERE u.email = ?
        LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    $_SESSION['pesan_error'] = 'Kesalahan database: ' . mysqli_error($koneksi);
    header("Location: login.php");
    exit;
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$user) {
    $_SESSION['pesan_error'] = 'Email tidak ditemukan!';
    header("Location: login.php");
    exit;
}

// Cek password
$password_valid = false;

if (password_verify($password, $user['password'])) {
    $password_valid = true;
} elseif ($user['password'] === $password) {
    // Ubah password teks biasa jadi hash
    $hash_baru = password_hash($password, PASSWORD_DEFAULT);
    $update = mysqli_prepare($koneksi, "UPDATE t_users SET password = ? WHERE id = ?");
    if ($update) {
        mysqli_stmt_bind_param($update, "si", $hash_baru, $user['id']);
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

// Login berhasil — hapus sesi lama & buat baru
session_regenerate_id(true);

$_SESSION['login'] = true;
$_SESSION['id_user'] = $user['id'];
$_SESSION['id'] = $user['id'];
$_SESSION['name'] = $user['name'];
$_SESSION['role'] = $user['role'];

if ($user['role'] === 'guru') {
    $_SESSION['nip'] = $user['nip'];
    $_SESSION['nama_guru'] = $user['nama_guru'];
}

// Pengalihan sesuai peran
if ($user['role'] === 'admin') {
    header("Location: dashboard.php");
    exit;
} elseif ($user['role'] === 'guru') {
    header("Location: dashboard_guru.php");
    exit;
} else {
    $_SESSION['pesan_error'] = 'Peran tidak dikenali: [' . $user['role'] . ']';
    header("Location: login.php");
    exit;
}
