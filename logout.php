<?php
// Mulai sesi di awal file sebelum ada proses atau output apa pun
session_start();

// Ubah "nama_pengguna", "kata_sandi", dan "nama_database" sesuai pengaturan MySQL Anda (XAMPP default: user "root", password "")
$conn = new mysqli("localhost", "root", "", "db_ukk_2026");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil waktu sekarang
$waktu = date("Y-m-d H:i:s");

// Isi variabel nilai sesuai kebutuhanmu
$nilai1 = "isi_nilai_sesuai_kegunaan";
$nilai2 = "isi_nilai_lain";

// Siapkan perintah INSERT — pastikan nama kolom SESUAI dengan tabel
$stmt = mysqli_prepare(
    $conn,
    "INSERT INTO tbl_log (id_user, kolom_lain2) VALUES (?, ?)"
);

if ($stmt) {
    // Ikat parameter — sesuaikan jumlah & tipe data dengan kolommu
    mysqli_stmt_bind_param($stmt, "ss", $nilai1, $nilai2);

    // Jalankan perintah
    if (mysqli_stmt_execute($stmt)) {
        // Data berhasil disimpan
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    
    mysqli_stmt_close($stmt);
}

// Hancurkan sesi login
$_SESSION = array();
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

mysqli_close($conn);

// Alihkan ke halaman login
header("Location: login.php");
exit;
?>