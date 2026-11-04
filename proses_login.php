<?php
// proses_login.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['pesan_error'] = 'Email dan kata sandi wajib diisi!';
    header("Location: login.php");
    exit;
}

$sql = "SELECT * FROM t_users WHERE email = ?";
$stmt = mysqli_prepare($koneksi, $sql);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$hasil = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($hasil) === 1) {
    $data = mysqli_fetch_assoc($hasil);

    // ✅ PASTIKAN PAKAI password_verify()
    if (password_verify($password, $data['password'])) {
        session_regenerate_id(true);
        $_SESSION['login'] = true;
        $_SESSION['id_user'] = $data['id'];
        $_SESSION['name'] = $data['name'];
        $_SESSION['role'] = $data['role'];

        header("Location: dashboard.php");
        exit;
    } else {
        $_SESSION['pesan_error'] = 'Kata sandi salah!';
        header("Location: login.php");
        exit;
    }
} else {
    $_SESSION['pesan_error'] = 'Email tidak ditemukan!';
    header("Location: login.php");
    exit;
}
?>
