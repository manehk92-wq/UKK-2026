
<?php
// Pastikan session sudah dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? '';

function menuAktif($halaman, $current_page) {
    return $current_page === $halaman
        ? 'active'
        : 'text-white';
}
?>

<style>
    .sidebar {
        background: #212529;
        min-height: 100vh;
        padding: 20px 15px;
    }

    .sidebar h4 {
        color: white;
        font-weight: bold;
        margin-bottom: 30px;
    }

    .sidebar .nav-link {
        color: #ddd;
        padding: 11px 15px;
        border-radius: 8px;
        margin-bottom: 5px;
        transition: 0.2s;
    }

    .sidebar .nav-link:hover {
        background: #343a40;
        color: white;
    }

    .sidebar .nav-link.active {
        background: #0d6efd;
        color: white !important;
        font-weight: bold;
    }

    .sidebar .menu-title {
        color: #adb5bd;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        margin: 20px 10px 10px;
    }
</style>

<div class="col-md-3 col-lg-2 sidebar">

    <h4>📚 Pelanggaran Siswa</h4>

    <ul class="nav nav-pills flex-column">

        <?php if ($role === 'admin'): ?>

            <li class="menu-title">Menu Administrator</li>

            <li class="nav-item">
                <a href="dashboard.php"
                   class="nav-link <?= menuAktif('dashboard.php', $current_page) ?>">
                    🏠 Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_siswa.php"
                   class="nav-link <?= menuAktif('kelola_siswa.php', $current_page) ?>">
                    👨‍🎓 Data Siswa
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_guru.php"
                   class="nav-link <?= menuAktif('kelola_guru.php', $current_page) ?>">
                    👨‍🏫 Data Guru
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_kelas.php"
                   class="nav-link <?= menuAktif('kelola_kelas.php', $current_page) ?>">
                    🏫 Data Kelas
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_tahun_ajaran.php"
                   class="nav-link <?= menuAktif('kelola_tahun_ajaran.php', $current_page) ?>">
                    📅 Tahun Ajaran
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_walikelas.php"
                   class="nav-link <?= menuAktif('kelola_walikelas.php', $current_page) ?>">
                    👨‍🏫 Data Wali Kelas
                </a>
            </li>

            <li class="nav-item">
                <a href="penempatan_siswa.php"
                   class="nav-link <?= menuAktif('penempatan_siswa.php', $current_page) ?>">
                    📋 Penempatan Siswa
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_pelanggaran_kategori.php"
                   class="nav-link <?= menuAktif('kelola_pelanggaran_kategori.php', $current_page) ?>">
                    📂 Kategori Pelanggaran
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_jenis_pelanggaran.php"
                   class="nav-link <?= menuAktif('kelola_jenis_pelanggaran.php', $current_page) ?>">
                    ⚠️ Jenis Pelanggaran
                </a>
            </li>

            <li class="nav-item">
                <a href="tindakan.php"
                   class="nav-link <?= menuAktif('tindakan.php', $current_page) ?>">
                    📝 Tindakan
                </a>
            </li>

            <!-- MENU RIWAYAT UNTUK ADMIN -->
            <li class="nav-item">
                <a href="riwayat.php"
                   class="nav-link <?= menuAktif('riwayat.php', $current_page) ?>">
                    📖 Riwayat Pelanggaran
                </a>
            </li>

            <li class="nav-item">
                <a href="rekap_poin.php"
                   class="nav-link <?= menuAktif('laporan.php', $current_page) ?>">
                    📊 laporan
                </a>
            </li>

        
            <li class="menu-title">Informasi</li>

            <li class="nav-item">
                <a href="about_me.php"
                   class="nav-link <?= menuAktif('about_me.php', $current_page) ?>">
                    👤 About Me
                </a>
            </li>

        <?php elseif ($role === 'guru'): ?>

            <li class="menu-title">Menu Guru</li>

            <li class="nav-item">
                <a href="dashboard.php"
                   class="nav-link <?= menuAktif('dashboard.php', $current_page) ?>">
                    🏠 Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="catat_pelanggaran.php"
                   class="nav-link <?= menuAktif('catat_pelanggaran.php', $current_page) ?>">
                    ✍️ Catat Pelanggaran
                </a>
            </li>

            <li class="nav-item">
                <a href="tindakan.php"
                   class="nav-link <?= menuAktif('tindakan.php', $current_page) ?>">
                    📝 Tindakan
                </a>
            </li>

            <li class="nav-item">
                <a href="riwayat.php"
                   class="nav-link <?= menuAktif('riwayat.php', $current_page) ?>">
                    📖 Riwayat
                </a>
            </li>

            <li class="nav-item">
                <a href="rekap_poin.php"
                   class="nav-link <?= menuAktif('rekap_poin.php', $current_page) ?>">
                    📊 Rekap Poin
                </a>
            </li>

        <li class="nav-item">
                <a href="rekap_poin.php"
                   class="nav-link <?= menuAktif('laporan.php', $current_page) ?>">
                    📊 laporan
                </a>
            </li>

        

        <?php endif; ?>

        <hr class="border-secondary">

        <li class="nav-item">
            <a href="logout.php" class="nav-link text-danger">
                🚪 Logout
            </a>
        </li>

    </ul>

</div>
