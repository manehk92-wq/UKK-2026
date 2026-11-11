<?php
// dashboard.php
include "includes/cek_session.php";
include "config/koneksi.php";

// === PASTIKAN KODE INI BERADA DI ATAS SEMUA ===
// Hitung jumlah data siswa
$query_siswa = mysqli_query($koneksi, "SELECT * FROM t_siswa");
$jumlah_siswa = mysqli_num_rows($query_siswa);

// Hitung jumlah data guru
$query_guru = mysqli_query($koneksi, "SELECT * FROM t_guru");
$jumlah_guru = mysqli_num_rows($query_guru);

// Hitung jumlah kelas
$query_kelas = mysqli_query($koneksi, "SELECT * FROM t_kelas");
$jumlah_kelas = mysqli_num_rows($query_kelas);
?>
<!DOCTYPE html>
<html>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>Dashboard</title>
    <link rel="stylesheet" href="layout/style.css">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap/4.1.3/dist/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    <div class="container-fluid">
        <div class="row">
            <?php include "layout/sidebar.php"; ?>
            
            <!-- KONTEN -->
            <main class="menu-page-content">
                <h2>Dashboard</h2>
                <p>Selamat datang di halaman dashboard</p>
                <div class="row">
                    <!-- CARD DATA SISWA -->
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Data Siswa</h5>
                                <h2><?php echo $jumlah_siswa; ?></h2>
                            </div>
                        </div>
                    </div>
                    <!-- CARD DATA GURU -->
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Data Guru</h5>
                                <h2><?php echo $jumlah_guru; ?></h2>
                            </div>
                        </div>
                    </div>
                    <!-- CARD KELAS -->
                    <div class="col-md-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">Kelas</h5>
                                <h2><?php echo $jumlah_kelas; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>       
</body>
</html>
