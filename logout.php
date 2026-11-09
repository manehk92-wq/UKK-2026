
<?php
// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil ID user sebelum session dihapus
$id_user = $_SESSION['id_user'] ?? null;

// Hapus seluruh data session
$_SESSION = array();

// Hapus cookie session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Hancurkan session
session_destroy();

// Kembali ke halaman login
header("Location: login.php");
exit;
?>
