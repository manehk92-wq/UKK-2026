<?php

session_start();

if (!isset($_SESSION['user_id'])) {

    header("Location: auth/login.php");
    exit;

}

include "config/koneksi.php";


/*
|--------------------------------------------------------------------------
| HITUNG DATA
|--------------------------------------------------------------------------
*/

$queryGuru = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM guru"
);

$totalGuru = mysqli_fetch_assoc($queryGuru)['total'];


$querySiswa = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM siswa"
);

$totalSiswa = mysqli_fetch_assoc($querySiswa)['total'];


$queryKelas = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM kelas"
);

$totalKelas = mysqli_fetch_assoc($queryKelas)['total'];


$queryTahun = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM tahun_ajaran"
);

$totalTahun = mysqli_fetch_assoc($queryTahun)['total'];


$queryPelanggaran = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM pelanggaran_siswa"
);

$totalPelanggaran = mysqli_fetch_assoc($queryPelanggaran)['total'];

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <link rel="stylesheet"
          href="assets/style.css">

</head>


<body>


<!-- SIDEBAR -->

<aside class="sidebar">

    <div class="logo">

        <h2>SEKOLAH</h2>

        <small>
            Sistem Informasi
        </small>

    </div>


    <nav>

        <a href="dashboard.php"
           class="active">
            🏠 Dashboard
        </a>


        <a href="guru/index.php">
            👨‍🏫 Data Guru
        </a>


        <a href="siswa/index.php">
            👨‍🎓 Data Siswa
        </a>


        <a href="kelas/index.php">
            🏫 Data Kelas
        </a>


        <a href="tahun_ajaran/index.php">
            📅 Tahun Ajaran
        </a>


        <a href="wali_kelas/index.php">
            👤 Wali Kelas
        </a>


        <a href="pelanggaran/index.php">
            ⚠️ Pelanggaran
        </a>

    </nav>


    <div class="logout">

        <a href="auth/logout.php">
            🚪 Logout
        </a>

    </div>

</aside>



<!-- MAIN -->

<main class="main">

    <header class="topbar">

        <div>

            <h2>Dashboard</h2>

            <small>
                Sistem Informasi Sekolah
            </small>

        </div>


        <div class="profile">

            <strong>
                <?= htmlspecialchars($_SESSION['name']); ?>
            </strong>

            <small>
                <?= htmlspecialchars($_SESSION['role']); ?>
            </small>

        </div>

    </header>



    <section class="content">

        <h1>
            Selamat datang,
            <?= htmlspecialchars($_SESSION['name']); ?> 👋
        </h1>

        <p class="description">
            Berikut ringkasan data sistem sekolah.
        </p>


        <!-- CARDS -->

        <div class="cards">


            <div class="card">

                <div class="icon">
                    👨‍🏫
                </div>

                <div>

                    <h3>Guru</h3>

                    <strong>
                        <?= $totalGuru; ?>
                    </strong>

                </div>

            </div>



            <div class="card">

                <div class="icon">
                    👨‍🎓
                </div>

                <div>

                    <h3>Siswa</h3>

                    <strong>
                        <?= $totalSiswa; ?>
                    </strong>

                </div>

            </div>



            <div class="card">

                <div class="icon">
                    🏫
                </div>

                <div>

                    <h3>Kelas</h3>

                    <strong>
                        <?= $totalKelas; ?>
                    </strong>

                </div>

            </div>



            <div class="card">

                <div class="icon">
                    ⚠️
                </div>

                <div>

                    <h3>Pelanggaran</h3>

                    <strong>
                        <?= $totalPelanggaran; ?>
                    </strong>

                </div>

            </div>


        </div>


        <!-- INFORMASI -->

        <div class="panel">

            <h2>Informasi</h2>

            <p>
                Tahun ajaran yang tersedia:
                <strong>
                    <?= $totalTahun; ?>
                </strong>
            </p>

            <p>
                Login sebagai:
                <strong>
                    <?= htmlspecialchars($_SESSION['role']); ?>
                </strong>
            </p>

        </div>


    </section>

</main>


</body>

</html>