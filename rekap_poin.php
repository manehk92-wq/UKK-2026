
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/koneksi.php';

// ===============================
// CEK LOGIN DAN ROLE
// ===============================
if (
    !isset($_SESSION['login']) ||
    $_SESSION['login'] !== true ||
    !isset($_SESSION['id_user'])
) {
    header("Location: login.php");
    exit;
}

$role = $_SESSION['role'] ?? '';

if (!in_array($role, ['admin', 'guru'], true)) {
    header("Location: login.php");
    exit;
}

// ===============================
// FUNGSI KEAMANAN
// ===============================
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

// ===============================
// PENCARIAN
// ===============================
$cari = trim($_GET['cari'] ?? '');
$keyword = '%' . $cari . '%';

// ===============================
// QUERY REKAP POIN SISWA
// ===============================
$sql = "SELECT
            nama_siswa,
            nama_kelas,
            COUNT(*) AS jumlah_pelanggaran,
            COALESCE(SUM(poin), 0) AS total_poin
        FROM t_pelanggaran_siswa
        WHERE nama_siswa LIKE ?
           OR nama_kelas LIKE ?
        GROUP BY nama_siswa, nama_kelas
        ORDER BY total_poin DESC, nama_siswa ASC";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Query gagal: " . e(mysqli_error($koneksi)));
}

mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

mysqli_stmt_bind_result(
    $stmt,
    $nama_siswa,
    $nama_kelas,
    $jumlah_pelanggaran,
    $total_poin
);

// ===============================
// AMBIL DATA REKAP
// ===============================
$data_rekap = [];
$total_seluruh_pelanggaran = 0;
$total_seluruh_poin = 0;

while (mysqli_stmt_fetch($stmt)) {
    $data_rekap[] = [
        'nama_siswa' => $nama_siswa,
        'nama_kelas' => $nama_kelas,
        'jumlah_pelanggaran' => $jumlah_pelanggaran,
        'total_poin' => $total_poin
    ];

    $total_seluruh_pelanggaran += (int)$jumlah_pelanggaran;
    $total_seluruh_poin += (int)$total_poin;
}

$total_siswa = count($data_rekap);

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rekap Poin Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .content {
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,.07);
        }

        .judul {
            font-weight: bold;
        }

        .table thead th {
            background: #0d6efd;
            color: white;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .info-card {
            border-left: 5px solid #0d6efd;
        }

        @media (max-width: 768px) {
            .content {
                padding: 15px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <?php
        include __DIR__ . '/layout/sidebar.php';
        ?>

        <!-- KONTEN UTAMA -->
        <main class="menu-page-content content">

            <div class="mb-4">
                <h3 class="judul">📊 Rekap Poin Pelanggaran Siswa</h3>
                <p class="text-muted">
                    Ringkasan jumlah pelanggaran dan total poin setiap siswa.
                </p>
            </div>

            <!-- KARTU RINGKASAN -->
            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <div class="card info-card h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Jumlah Siswa</h6>
                            <h2 class="fw-bold text-primary">
                                <?= $total_siswa ?>
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card info-card h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Total Pelanggaran</h6>
                            <h2 class="fw-bold text-danger">
                                <?= $total_seluruh_pelanggaran ?>
                            </h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card info-card h-100">
                        <div class="card-body">
                            <h6 class="text-muted">Total Seluruh Poin</h6>
                            <h2 class="fw-bold text-warning">
                                <?= $total_seluruh_poin ?>
                            </h2>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TABEL REKAP -->
            <div class="card">
                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center flex-wrap gap-2 mb-4">

                        <h5 class="fw-bold mb-0">Data Rekap Siswa</h5>

                        <span class="badge bg-primary fs-6">
                            <?= $total_siswa ?> Siswa
                        </span>

                    </div>

                    
<!-- FORM PENCARIAN -->
<form method="GET" action="rekap_poin.php" class="row g-2 mb-4">

    <div class="col-md-8">
        <input
            type="text"
            name="cari"
            class="form-control"
            placeholder="Cari nama siswa atau kelas..."
            value="<?= e($cari) ?>"
        >
    </div>

    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search"></i> Cari
        </button>
    </div>

    <div class="col-md-2">
        <a href="rekap_poin.php" class="btn btn-secondary w-100">
            Reset
        </a>
    </div>

</form>

                    <!-- TABEL -->
                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jumlah Pelanggaran</th>
                                    <th>Total Poin</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php if ($total_siswa > 0): ?>

                                <?php $no = 1; ?>

                                <?php foreach ($data_rekap as $data): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>

                                        <td>
                                            <?= e($data['nama_siswa']) ?>
                                        </td>

                                        <td>
                                            <?= e($data['nama_kelas']) ?>
                                        </td>

                                        <td>
                                            <span class="badge bg-danger">
                                                <?= e($data['jumlah_pelanggaran']) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                <?= e($data['total_poin']) ?> Poin
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <span class="text-muted">
                                            Belum ada data rekap pelanggaran.
                                        </span>
                                    </td>
                                </tr>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                    <div class="text-muted small">
                        Menampilkan <?= $total_siswa ?> data siswa.
                    </div>

                </div>
            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
mysqli_close($koneksi);
?>
