
<?php
// includes/cek_session.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Periksa status login dan ID pengguna
if (
    !isset($_SESSION['login']) ||
    $_SESSION['login'] !== true ||
    !isset($_SESSION['id_user'])
) {
    header("Location: login.php");
    exit;
}
?>
