<?php
// Aktifkan tampilkan error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Mulai sesi (wajib untuk cek login guru)
session_start();

// Cek apakah guru sudah login
if (!isset($_SESSION['guru_id'])) {
    header("Location: login.php");
    exit;
}

// Koneksi Database — sesuaikan dengan milikmu!
$host = "localhost";
$user = "root";
$pass = ""; // kosongkan jika pakai XAMPP/WAMP standar
$db   = "ukk_2026"; // ganti dengan nama database kamu

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("<div style='color:red; padding:20px;'>Koneksi gagal: " . mysqli_connect_error() . "</div>");
}

// Ambil data guru dari sesi
$nama_guru = $_SESSION['nama_guru'] ?? 'Guru';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - UKK 2026</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { background: #f0f4f8; }
        .navbar { background: #2c3e50; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h2 { font-size: 20px; }
        .logout { background: #e74c3c; color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; text-decoration: none; }
        .logout:hover { background: #c0392b; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .selamat { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 25px; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: transform 0.2s; }
        .card:hover { transform: translateY(-5px); }
        .card h3 { color: #2c3e50; margin-bottom: 10px; }
        .card p { color: #666; font-size: 14px; }
        .angka { font-size: 32px; font-weight: bold; color: #27ae60; margin: 10px 0; }
    </style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <h2>Sistem Informasi Sekolah — Dashboard Guru</h2>
    <div>
        <span>Halo, <?php echo htmlspecialchars($nama_guru); ?></span>
        <a href="logout.php" class="logout" style="margin-left: 15px;">Keluar</a>
    </div>
</div>

<!-- Konten Utama -->
<div class="container">
    <div class="selamat">
        <h1>Selamat Datang di Dashboard Guru</h1>
        <p>Anda masuk sebagai guru. Gunakan menu di bawah untuk mengelola data.</p>
    </div>

    <div class="grid">
        <!-- Kartu 1: Data Siswa -->
        <div class="card">
            <h3>Data Siswa</h3>
            <div class="angka">
                <?php
                $q = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM siswa");
                $d = mysqli_fetch_assoc($q);
                echo $d['total'];
                ?>
            </div>
            <p>Jumlah siswa yang terdaftar</p>
            <a href="data_siswa.php" style="color:#3498db; text-decoration:none; margin-top:10px; display:inline-block;">Lihat &rarr;</a>
        </div>

        <!-- Kartu 2: Nilai -->
        <div class="card">
            <h3>Input Nilai</h3>
            <div class="angka">
                <?php
                $q2 = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM nilai");
                $d2 = mysqli_fetch_assoc($q2);
                echo $d2['total'];
                ?>
            </div>
            <p>Data nilai yang sudah dimasukkan</p>
            <a href="input_nilai.php" style="color:#3498db; text-decoration:none; margin-top:10px; display:inline-block;">Kelola &rarr;</a>
        </div>

        <!-- Kartu 3: Jadwal -->
        <div class="card">
            <h3>Jadwal Mengajar</h3>
            <p style="margin-top:15px;">Lihat jadwal pelajaran Anda</p>
            <a href="jadwal.php" style="color:#3498db; text-decoration:none; margin-top:10px; display:inline-block;">Buka &rarr;</a>
        </div>

        <!-- Kartu 4: Laporan -->
        <div class="card">
            <h3>Laporan</h3>
            <p style="margin-top:15px;">Cetak laporan nilai &rapor</p>
            <a href="laporan.php" style="color:#3498db; text-decoration:none; margin-top:10px; display:inline-block;">Buat &rarr;</a>
        </div>
    </div>
</div>

</body>
</html>

<?php
mysqli_close($koneksi);
?>
