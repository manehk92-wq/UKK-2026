<?php
include 'config/koneksi.php';

$password_baru = 'admin123';
$hash = password_hash($password_baru, PASSWORD_DEFAULT);

$sql = "UPDATE t_users SET password = ? WHERE id = 1";
$stmt = mysqli_prepare($koneksi, $sql);

mysqli_stmt_bind_param($stmt, "s", $hash);

if (mysqli_stmt_execute($stmt)) {
    echo "Password admin berhasil direset!";
    echo "<br>Email: user001@sekolah.test";
    echo "<br>Password: admin123";
} else {
    echo "Gagal: " . mysqli_error($koneksi);
}
?>