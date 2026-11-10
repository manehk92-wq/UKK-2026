```php
<?php
// layout/sidebar.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$current_page = basename($_SERVER['PHP_SELF']);
$role = $_SESSION['role'] ?? '';

function menuAktif($halaman, $current_page) {
    return $current_page === $halaman ? 'active' : '';
}
?>

<style>
    body {
        margin: 0;
        padding-left: 0;
        background: #f4f6f9;
        font-family: Arial, sans-serif;
    }

    .container-fluid > .row {
        display: block;
        margin: 0;
    }

    .container-fluid > .row > main,
    main.menu-page-content {
        width: calc(100% - 250px);
        max-width: none;
        margin-left: 250px !important;
        padding: 28px !important;
        overflow-x: hidden;
        transition: width 0.25s ease, margin-left 0.25s ease;
    }

    .content {
        margin-left: 250px !important;
        padding: 28px !important;
    }

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
        box-sizing: border-box;
        transition: transform 0.25s ease;
    }

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
        color: #fff;
        font-size: 22px;
        line-height: 1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        transition: left 0.25s ease;
    }

    .sidebar-backdrop {
        display: none;
    }

    body.sidebar-closed {
        padding-left: 0;
    }

    body.sidebar-closed .sidebar {
        transform: translateX(-100%);
    }

    body.sidebar-closed .sidebar-toggle {
        left: 12px;
    }

    body.sidebar-closed .container-fluid > .row > main,
    body.sidebar-closed main.menu-page-content,
    body.sidebar-closed .content,
    body.sidebar-closed .main-content {
        width: 100%;
        margin-left: 0 !important;
    }

    .table-responsive {
        overflow-x: auto;
        border-radius: 8px;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive > .table {
        min-width: 720px;
        margin-bottom: 0;
        border-color: #e2e8f0;
        color: #263248;
        vertical-align: middle;
    }

    .table-responsive > .table thead th {
        padding: 13px 12px;
        border-bottom: 0;
        background-color: #17233f;
        color: #fff;
        font-size: 0.84rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .table-responsive > .table tbody td {
        padding: 11px 12px;
        line-height: 1.5;
    }

    .table-responsive > .table tbody tr:hover > * {
        background-color: #f0f5ff;
    }

    .table-responsive > .table td .btn {
        margin: 2px;
    }

    .dt-container .dt-layout-row {
        align-items: center;
        gap: 12px;
    }

    .dt-container .dt-search input,
    .dt-container .dt-length select {
        min-height: 38px;
        border-color: #d7deea;
        border-radius: 8px;
    }

    .dt-container .dt-search input {
        margin-left: 8px;
    }

    .dt-container .dt-info {
        color: #667085;
        font-size: 0.875rem;
    }

    .sidebar h4 {
        color: white;
        font-weight: bold;
        margin-bottom: 30px;
        padding: 35px 10px 0;
    }

    .sidebar .nav-link {
        display: block;
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

    @media (max-width: 768px) {
        body {
            padding-left: 0;
        }

        .container-fluid > .row > main,
        main.menu-page-content,
        .content,
        body.sidebar-closed .container-fluid > .row > main,
        body.sidebar-closed main.menu-page-content,
        body.sidebar-closed .content {
            width: 100%;
            margin-left: 0 !important;
            padding: 68px 15px 20px !important;
        }

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

        body.sidebar-open .sidebar-backdrop {
            display: block;
            position: fixed;
            inset: 0;
            z-index: 9998;
            border: 0;
            background: rgba(0, 0, 0, 0.35);
        }
    }
</style>

<button
    type="button"
    class="sidebar-toggle"
    aria-label="Buka menu navigasi"
    aria-controls="app-sidebar"
    aria-expanded="false"
>
    <span aria-hidden="true">&#9776;</span>
</button>
<button type="button" class="sidebar-backdrop" aria-label="Tutup menu navigasi"></button>

<div class="sidebar" id="app-sidebar">

    <h4>🎓 SISTEM SEKOLAH</h4>

    <ul class="nav nav-pills flex-column">

        <?php if ($role === 'admin'): ?>

            <li class="menu-title">Menu Administrator</li>

            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?= menuAktif('dashboard.php', $current_page) ?>">
                    🏠 Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_siswa.php" class="nav-link <?= menuAktif('kelola_siswa.php', $current_page) ?>">
                    👨‍🎓 Data Siswa
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_guru.php" class="nav-link <?= menuAktif('kelola_guru.php', $current_page) ?>">
                    👨‍🏫 Data Guru
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_kelas.php" class="nav-link <?= menuAktif('kelola_kelas.php', $current_page) ?>">
                    🏫 Data Kelas
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_tahun_ajaran.php" class="nav-link <?= menuAktif('kelola_tahun_ajaran.php', $current_page) ?>">
                    📅 Tahun Ajaran
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_walikelas.php" class="nav-link <?= menuAktif('kelola_walikelas.php', $current_page) ?>">
                    👨‍🏫 Data Wali Kelas
                </a>
            </li>

            <li class="nav-item">
                <a href="penempatan_siswa.php" class="nav-link <?= menuAktif('penempatan_siswa.php', $current_page) ?>">
                    📋 Penempatan Siswa
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_pelanggaran_kategori.php" class="nav-link <?= menuAktif('kelola_pelanggaran_kategori.php', $current_page) ?>">
                    📂 Kategori Pelanggaran
                </a>
            </li>

            <li class="nav-item">
                <a href="kelola_jenis_pelanggaran.php" class="nav-link <?= menuAktif('kelola_jenis_pelanggaran.php', $current_page) ?>">
                    ⚠️ Jenis Pelanggaran
                </a>
            </li>

            <li class="nav-item">
                <a href="tindakan.php" class="nav-link <?= menuAktif('tindakan.php', $current_page) ?>">
                    📝 Tindakan
                </a>
            </li>

            <li class="nav-item">
                <a href="riwayat.php" class="nav-link <?= menuAktif('riwayat.php', $current_page) ?>">
                    📖 Riwayat Pelanggaran
                </a>
            </li>

            <li class="nav-item">
                <a href="rekap_poin.php" class="nav-link <?= menuAktif('rekap_poin.php', $current_page) ?>">
                    📊 Rekap Poin
                </a>
            </li>

            <li class="menu-title">Informasi</li>

            <li class="nav-item">
                <a href="about_me.php" class="nav-link <?= menuAktif('about_me.php', $current_page) ?>">
                    👤 About Me
                </a>
            </li>

        <?php elseif ($role === 'guru'): ?>

            <li class="menu-title">Menu Guru</li>

            <li class="nav-item">
                <a href="dashboard.php" class="nav-link <?= menuAktif('dashboard.php', $current_page) ?>">
                    🏠 Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="catat_pelanggaran.php" class="nav-link <?= menuAktif('catat_pelanggaran.php', $current_page) ?>">
                    ✍️ Catat Pelanggaran
                </a>
            </li>

            <li class="nav-item">
                <a href="tindakan.php" class="nav-link <?= menuAktif('tindakan.php', $current_page) ?>">
                    📝 Tindakan
                </a>
            </li>

            <li class="nav-item">
                <a href="riwayat.php" class="nav-link <?= menuAktif('riwayat.php', $current_page) ?>">
                    📖 Riwayat
                </a>
            </li>

            <li class="nav-item">
                <a href="rekap_poin.php" class="nav-link <?= menuAktif('rekap_poin.php', $current_page) ?>">
                    📊 Rekap Poin
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

<script>
    (() => {
        const toggle = document.querySelector('.sidebar-toggle');
        const backdrop = document.querySelector('.sidebar-backdrop');

        if (!toggle || !backdrop) {
            return;
        }

        const isMobile = () => window.matchMedia('(max-width: 768px)').matches;

        const updateToggleState = () => {
            const isVisible = isMobile()
                ? document.body.classList.contains('sidebar-open')
                : !document.body.classList.contains('sidebar-closed');

            toggle.setAttribute('aria-expanded', String(isVisible));
            toggle.setAttribute(
                'aria-label',
                isVisible ? 'Tutup menu navigasi' : 'Buka menu navigasi'
            );
        };

        const closeSidebar = () => {
            document.body.classList.remove('sidebar-open', 'sidebar-closed');
            updateToggleState();
        };

        toggle.addEventListener('click', () => {
            const className = isMobile() ? 'sidebar-open' : 'sidebar-closed';
            document.body.classList.toggle(className);
            updateToggleState();
        });

        backdrop.addEventListener('click', closeSidebar);
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeSidebar();
            }
        });

        window.addEventListener('resize', closeSidebar);
        updateToggleState();
    })();
</script>
```