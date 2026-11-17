
<?php
// layout/sidebar.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$role = strtolower(trim($_SESSION['role'] ?? ''));

function menuAktif($halaman, $current_page) {
    return $current_page === $halaman ? 'active' : '';
}
?>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: #f4f6f9;
    font-family: Arial, sans-serif;
}

/* SIDEBAR */
.sidebar {
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    width: 250px;
    height: 100vh;
    background: #212529;
    padding: 20px 15px;
    overflow-y: auto;
    z-index: 9999;
    transition: transform .25s ease;
}

.sidebar h4 {
    color: white;
    font-weight: bold;
    margin: 20px 0 30px;
    padding: 15px 10px;
}

.sidebar .nav-link {
    display: block;
    color: #ddd;
    padding: 11px 15px;
    border-radius: 8px;
    margin-bottom: 5px;
    transition: .2s;
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

.menu-title {
    color: #adb5bd;
    font-size: 11px;
    font-weight: bold;
    text-transform: uppercase;
    margin: 20px 10px 10px;
}

/* KONTEN UTAMA */
.main-content,
main.menu-page-content,
.content {
    width: calc(100% - 250px);
    margin-left: 250px !important;
    padding: 28px !important;
    min-height: 100vh;
    transition: .25s ease;
}

/* TOMBOL SIDEBAR */
.sidebar-toggle {
    position: fixed;
    top: 12px;
    left: 195px;
    z-index: 10001;
    width: 42px;
    height: 42px;
    border: 0;
    border-radius: 8px;
    background: #0d6efd;
    color: white;
    font-size: 22px;
    cursor: pointer;
    transition: .25s;
}

/* SIDEBAR DITUTUP */
body.sidebar-closed .sidebar {
    transform: translateX(-100%);
}

body.sidebar-closed .sidebar-toggle {
    left: 12px;
}

body.sidebar-closed .main-content,
body.sidebar-closed main.menu-page-content,
body.sidebar-closed .content {
    width: 100%;
    margin-left: 0 !important;
}

/* TABEL */
.table-responsive {
    overflow-x: auto;
    border-radius: 8px;
}

/* MOBILE */
.sidebar-backdrop {
    display: none;
}

@media (max-width: 768px) {

    .sidebar {
        width: 220px;
        transform: translateX(-100%);
    }

    body.sidebar-open .sidebar {
        transform: translateX(0);
    }

    .sidebar-toggle {
        left: 12px;
    }

    body.sidebar-open .sidebar-toggle {
        left: 230px;
    }

    .main-content,
    main.menu-page-content,
    .content {
        width: 100%;
        margin-left: 0 !important;
        padding: 68px 15px 20px !important;
    }

    body.sidebar-open .sidebar-backdrop {
        display: block;
        position: fixed;
        inset: 0;
        z-index: 9998;
        border: 0;
        background: rgba(0,0,0,.4);
    }
}
</style>

<!-- TOMBOL SIDEBAR -->
<button type="button" class="sidebar-toggle"
        aria-label="Buka menu navigasi">
    ☰
</button>

<button type="button"
        class="sidebar-backdrop"
        aria-label="Tutup sidebar">
</button>

<!-- SIDEBAR -->
<div class="sidebar" id="app-sidebar">

    <h4>🎓 SISTEM SEKOLAH</h4>

    <ul class="nav nav-pills flex-column">

        <?php if ($role === 'admin'): ?>

            <li class="menu-title">Administrator</li>

            <li><a href="dashboard.php"
                class="nav-link <?= menuAktif('dashboard.php', $current_page) ?>">🏠 Dashboard</a></li>

            <li><a href="kelola_siswa.php"
                class="nav-link <?= menuAktif('kelola_siswa.php', $current_page) ?>">👨‍🎓 Data Siswa</a></li>

            <li><a href="kelola_guru.php"
                class="nav-link <?= menuAktif('kelola_guru.php', $current_page) ?>">👨‍🏫 Data Guru</a></li>

            <li><a href="kelola_kelas.php"
                class="nav-link <?= menuAktif('kelola_kelas.php', $current_page) ?>">🏫 Data Kelas</a></li>

            <li><a href="kelola_tahun_ajaran.php"
                class="nav-link <?= menuAktif('kelola_tahun_ajaran.php', $current_page) ?>">📅 Tahun Ajaran</a></li>

            <li><a href="kelola_walikelas.php"
                class="nav-link <?= menuAktif('kelola_walikelas.php', $current_page) ?>">👨‍🏫 Data Wali Kelas</a></li>

            <li><a href="penempatan_siswa.php"
                class="nav-link <?= menuAktif('penempatan_siswa.php', $current_page) ?>">📋 Penempatan Siswa</a></li>

            <li><a href="kelola_pelanggaran_kategori.php"
                class="nav-link <?= menuAktif('kelola_pelanggaran_kategori.php', $current_page) ?>">📂 Kategori Pelanggaran</a></li>

            <li><a href="kelola_jenis_pelanggaran.php"
                class="nav-link <?= menuAktif('kelola_jenis_pelanggaran.php', $current_page) ?>">⚠️ Jenis Pelanggaran</a></li>

            <li><a href="tindakan.php"
                class="nav-link <?= menuAktif('tindakan.php', $current_page) ?>">📝 Tindakan</a></li>

            <li><a href="riwayat.php"
                class="nav-link <?= menuAktif('riwayat.php', $current_page) ?>">📖 Riwayat Pelanggaran</a></li>

            <li><a href="rekap_poin.php"
                class="nav-link <?= menuAktif('rekap_poin.php', $current_page) ?>">📊 Rekap Poin</a></li>

            <li><a href="laporan.php"
                class="nav-link <?= menuAktif('laporan.php', $current_page) ?>">📑 Laporan</a></li>

            <li><a href="about_me.php"
                class="nav-link <?= menuAktif('about_me.php', $current_page) ?>">👤 About Me</a></li>

        <?php elseif ($role === 'guru'): ?>

            <li class="menu-title">Menu Guru</li>

            <li><a href="dashboard.php"
                class="nav-link <?= menuAktif('dashboard.php', $current_page) ?>">🏠 Dashboard</a></li>

            <li><a href="catat_pelanggaran.php"
                class="nav-link <?= menuAktif('catat_pelanggaran.php', $current_page) ?>">✍️ Catat Pelanggaran</a></li>

            <li><a href="tindakan.php"
                class="nav-link <?= menuAktif('tindakan.php', $current_page) ?>">📝 Tindakan</a></li>

            <li><a href="riwayat.php"
                class="nav-link <?= menuAktif('riwayat.php', $current_page) ?>">📖 Riwayat</a></li>

            <li><a href="rekap_poin.php"
                class="nav-link <?= menuAktif('rekap_poin.php', $current_page) ?>">📊 Rekap Poin</a></li>

            <li><a href="laporan.php"
                class="nav-link <?= menuAktif('laporan.php', $current_page) ?>">📑 Laporan</a></li>

        <?php elseif ($role === 'petugas'): ?>

            <li class="menu-title">Menu Petugas</li>

            <li><a href="dashboard.php"
                class="nav-link <?= menuAktif('dashboard.php', $current_page) ?>">🏠 Dashboard</a></li>

            <li><a href="catat_pelanggaran.php"
                class="nav-link <?= menuAktif('catat_pelanggaran.php', $current_page) ?>">✍️ Catat Pelanggaran</a></li>

            <li><a href="riwayat.php"
                class="nav-link <?= menuAktif('riwayat.php', $current_page) ?>">📖 Riwayat</a></li>

        <?php endif; ?>

        <hr class="border-secondary">

        <li>
            <a href="logout.php" class="nav-link text-danger">
                🚪 Logout
            </a>
        </li>

    </ul>
</div>

<script>
(() => {
    const toggle = document.querySelector('.sidebar-toggle');
    const backdrop = document.querySelector('.sidebar-backdrop');

    if (!toggle || !backdrop) return;

    const mobile = () =>
        window.matchMedia('(max-width: 768px)').matches;

    toggle.addEventListener('click', () => {
        if (mobile()) {
            document.body.classList.toggle('sidebar-open');
        } else {
            document.body.classList.toggle('sidebar-closed');
        }
    });

    backdrop.addEventListener('click', () => {
        document.body.classList.remove('sidebar-open');
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.body.classList.remove('sidebar-open');
        }
    });
})();
</script>
