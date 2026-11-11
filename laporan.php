
<?php
// laporan.php

session_start();

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header('Location: login.php');
    exit;
}

// Ambil filter dari URL
$cari = trim($_GET['cari'] ?? '');
$status = trim($_GET['status'] ?? '');
$tanggal_awal = trim($_GET['tanggal_awal'] ?? '');
$tanggal_akhir = trim($_GET['tanggal_akhir'] ?? '');

// Validasi format tanggal
if ($tanggal_awal !== '' &&
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_awal)) {
    $tanggal_awal = '';
}

if ($tanggal_akhir !== '' &&
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_akhir)) {
    $tanggal_akhir = '';
}

// Validasi rentang tanggal
if ($tanggal_awal !== '' && $tanggal_akhir !== '' &&
    $tanggal_awal > $tanggal_akhir) {
    $tanggal_awal = '';
    $tanggal_akhir = '';
}

// Query laporan
$sql = "
    SELECT
        p.id,
        p.nama_siswa,
        p.nama_kelas,
        p.nama_pelanggaran,
        p.nama_guru,
        p.tanggal,
        p.keterangan,
        p.poin,
        p.tindakan,
        p.status,
        ta.nama AS tahun_ajaran
    FROM t_pelanggaran_siswa p
    LEFT JOIN t_tahun_ajaran ta
        ON p.tahun_ajaran_id = ta.id
    WHERE
        (
            p.nama_siswa LIKE ?
            OR p.nama_kelas LIKE ?
            OR p.nama_guru LIKE ?
            OR p.nama_pelanggaran LIKE ?
        )
        AND (? = '' OR p.status = ?)
        AND (? = '' OR p.tanggal >= ?)
        AND (? = '' OR p.tanggal <= ?)
    ORDER BY p.tanggal DESC, p.id DESC
";

$stmt = mysqli_prepare($koneksi, $sql);

$keyword = '%' . $cari . '%';

mysqli_stmt_bind_param(
    $stmt,
    "ssssssssss",
    $keyword,
    $keyword,
    $keyword,
    $keyword,
    $status,
    $status,
    $tanggal_awal,
    $tanggal_awal,
    $tanggal_akhir,
    $tanggal_akhir
);

mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);

// Tampung data laporan
$laporan = [];
$total_poin = 0;

while ($row = mysqli_fetch_assoc($hasil)) {
    $laporan[] = $row;
    $total_poin += (int) $row['poin'];
}

$total_pelanggaran = count($laporan);

mysqli_stmt_close($stmt);

// Fungsi untuk mengamankan output
function aman($teks) {
    return htmlspecialchars((string) $teks, ENT_QUOTES, 'UTF-8');
}

// Format tanggal
function formatTanggal($tanggal) {
    if (empty($tanggal) || $tanggal === '0000-00-00') {
        return '-';
    }

    return date('d-m-Y', strtotime($tanggal));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .content {
            padding: 25px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .table th {
            background: #0d6efd;
            color: white;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .judul-laporan {
            font-weight: bold;
            color: #333;
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 15px;
            }
        }

        @media print {
            .sidebar,
            .sidebar-toggle,
            .sidebar-backdrop,
            .filter-area,
            .tombol-aksi,
            .btn,
            nav {
                display: none !important;
            }

            .content {
                margin: 0;
                padding: 0;
            }

            body {
                background: white;
            }

            .card {
                box-shadow: none;
            }

            .table th {
                background: #ddd !important;
                color: black !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

<?php include __DIR__ . '/layout/sidebar.php'; ?>

<div class="content">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="judul-laporan">Laporan Pelanggaran Siswa</h3>
            <p class="text-muted mb-0">
                Data laporan pelanggaran siswa
            </p>
        </div>

        <div class="tombol-aksi">
            <button onclick="window.print()" class="btn btn-success">
                Cetak Laporan
            </button>
        </div>
    </div>

    <!-- Ringkasan -->
    <div class="row mb-4">

        <div class="col-md-6 mb-3">
            <div class="card p-3">
                <div class="text-muted">Total Pelanggaran</div>
                <h3 class="text-primary mb-0">
                    <?= $total_pelanggaran; ?>
                </h3>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card p-3">
                <div class="text-muted">Total Poin Pelanggaran</div>
                <h3 class="text-danger mb-0">
                    <?= $total_poin; ?>
                </h3>
            </div>
        </div>

    </div>

    <!-- Filter -->
    <div class="card p-4 mb-4 filter-area">

        <h5 class="mb-3">Filter Laporan</h5>

        <form method="GET" action="laporan.php">

            <div class="row">

                <div class="col-md-4 mb-3">
                    <label class="form-label">Cari Data</label>
                    <input
                        type="text"
                        name="cari"
                        class="form-control"
                        placeholder="Nama siswa, kelas, guru..."
                        value="<?= aman($cari); ?>"
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>

                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>

                        <option value="Belum Ditindaklanjuti"
                            <?= $status === 'Belum Ditindaklanjuti' ? 'selected' : ''; ?>>
                            Belum Ditindaklanjuti
                        </option>

                        <option value="Diproses"
                            <?= $status === 'Diproses' ? 'selected' : ''; ?>>
                            Diproses
                        </option>

                        <option value="Selesai"
                            <?= $status === 'Selesai' ? 'selected' : ''; ?>>
                            Selesai
                        </option>
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Awal</label>
                    <input
                        type="date"
                        name="tanggal_awal"
                        class="form-control"
                        value="<?= aman($tanggal_awal); ?>"
                    >
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Akhir</label>
                    <input
                        type="date"
                        name="tanggal_akhir"
                        class="form-control"
                        value="<?= aman($tanggal_akhir); ?>"
                    >
                </div>

            </div>

            <button type="submit" class="btn btn-primary">
                Cari Laporan
            </button>

            <a href="laporan.php" class="btn btn-secondary">
                Reset
            </a>

        </form>
    </div>

    <!-- Tabel Laporan -->
    <div class="card p-3">

        <div class="text-center mb-4">
            <h4 class="fw-bold">LAPORAN DATA PELANGGARAN SISWA</h4>
            <p class="mb-0">Laporan berdasarkan data yang tercatat di sistem</p>
        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-striped">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Tahun Ajaran</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Guru</th>
                        <th>Tanggal</th>
                        <th>Poin</th>
                        <th>Tindakan</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                <?php if ($total_pelanggaran > 0): ?>

                    <?php $no = 1; ?>

                    <?php foreach ($laporan as $row): ?>

                        <tr>
                            <td><?= $no++; ?></td>

                            <td><?= aman($row['nama_siswa']); ?></td>

                            <td><?= aman($row['nama_kelas']); ?></td>

                            <td>
                                <?= aman($row['tahun_ajaran'] ?? '-'); ?>
                            </td>

                            <td><?= aman($row['nama_pelanggaran']); ?></td>

                            <td><?= aman($row['nama_guru']); ?></td>

                            <td><?= formatTanggal($row['tanggal']); ?></td>

                            <td class="text-center">
                                <?= (int) $row['poin']; ?>
                            </td>

                            <td><?= aman($row['tindakan'] ?: '-'); ?></td>

                            <td>
                                <?php
                                $warna = 'secondary';

                                if ($row['status'] === 'Selesai') {
                                    $warna = 'success';
                                } elseif ($row['status'] === 'Diproses') {
                                    $warna = 'warning';
                                } elseif ($row['status'] === 'Belum Ditindaklanjuti') {
                                    $warna = 'danger';
                                }
                                ?>

                                <span class="badge bg-<?= $warna; ?>">
                                    <?= aman($row['status'] ?: '-'); ?>
                                </span>
                            </td>
                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            Tidak ada data laporan pelanggaran.
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

                <?php if ($total_pelanggaran > 0): ?>
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="7" class="text-end">
                                Total Poin
                            </td>
                            <td class="text-center">
                                <?= $total_poin; ?>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                <?php endif; ?>

            </table>

        </div>

        <div class="mt-3 text-muted">
            Jumlah data: <?= $total_pelanggaran; ?> pelanggaran
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>