
<?php
session_start();

require_once __DIR__ . '/config/koneksi.php';

// ==========================================
// CEK LOGIN
// ==========================================
if (
    !isset($_SESSION['login']) ||
    $_SESSION['login'] !== true ||
    !isset($_SESSION['id_user'])
) {
    header("Location: login.php");
    exit;
}

// ==========================================
// CEK HAK AKSES
// ==========================================
$role = strtolower(trim($_SESSION['role'] ?? ''));

if (!in_array($role, ['admin', 'guru'], true)) {
    header("Location: login.php");
    exit;
}

// ==========================================
// FUNGSI KEAMANAN
// ==========================================
function e($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}

// ==========================================
// PENCARIAN DATA
// ==========================================
$cari = trim($_GET['cari'] ?? '');

$sql = "SELECT
            id,
            nama_siswa,
            nama_kelas,
            nama_pelanggaran,
            nama_guru,
            tanggal,
            keterangan,
            poin,
            tindakan,
            status
        FROM t_pelanggaran_siswa
        WHERE status = 'Ditindaklanjuti'
        AND (
            nama_siswa LIKE ?
            OR nama_kelas LIKE ?
            OR nama_pelanggaran LIKE ?
            OR nama_guru LIKE ?
        )
        ORDER BY tanggal DESC, id DESC";

$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

$keyword = "%" . $cari . "%";

mysqli_stmt_bind_param(
    $stmt,
    "ssss",
    $keyword,
    $keyword,
    $keyword,
    $keyword
);

mysqli_stmt_execute($stmt);

// ==========================================
// HITUNG JUMLAH DATA
// ==========================================
mysqli_stmt_store_result($stmt);

$total_data = mysqli_stmt_num_rows($stmt);

// ==========================================
// AMBIL HASIL QUERY
// ==========================================
mysqli_stmt_bind_result(
    $stmt,
    $id,
    $nama_siswa,
    $nama_kelas,
    $nama_pelanggaran,
    $nama_guru,
    $tanggal,
    $keterangan,
    $poin,
    $tindakan,
    $status
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Riwayat Tindakan Siswa</title>

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

        .table thead th {
            background: #0d6efd;
            color: white;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .judul {
            font-weight: bold;
        }

        .info-card {
            border-left: 5px solid #198754;
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
        <?php include __DIR__ . '/layout/sidebar.php'; ?>

        <!-- KONTEN UTAMA -->
        <main class="menu-page-content content">

            <!-- JUDUL -->
            <div class="mb-4">

                <h3 class="judul">
                    📖 Riwayat Tindakan Siswa
                </h3>

                <p class="text-muted">
                    Daftar siswa yang sudah ditindaklanjuti.
                </p>

            </div>

            <!-- JUMLAH DATA -->
            <div class="card info-card mb-4">

                <div class="card-body">

                    <h6 class="text-muted">
                        Total Siswa Sudah Ditindaklanjuti
                    </h6>

                    <h2 class="fw-bold text-success mb-0">
                        <?= $total_data ?>
                    </h2>

                </div>

            </div>

            <!-- TABEL RIWAYAT -->
            <div class="card">

                <div class="card-body">

                    <div class="d-flex justify-content-between
                                align-items-center flex-wrap gap-2 mb-4">

                        <h5 class="fw-bold mb-0">
                            Data Riwayat Tindakan
                        </h5>

                        <span class="badge bg-success fs-6">
                            <?= $total_data ?> Data
                        </span>

                    </div>

                    <!-- PENCARIAN -->
                    <form method="GET"
                          action="riwayat.php"
                          class="row g-2 mb-4">

                        <div class="col-md-8">

                            <input
                                type="text"
                                name="cari"
                                class="form-control"
                                placeholder="Cari nama siswa, kelas, pelanggaran, atau guru..."
                                value="<?= e($cari) ?>"
                            >

                        </div>

                        <div class="col-md-2">

                            <button type="submit"
                                    class="btn btn-primary w-100">
                                🔍 Cari
                            </button>

                        </div>

                        <div class="col-md-2">

                            <a href="riwayat.php"
                               class="btn btn-secondary w-100">
                                Reset
                            </a>

                        </div>

                    </form>

                    <!-- TABEL -->
                    <div class="table-responsive">

                        <table class="table table-bordered
                                      table-hover align-middle">

                            <thead>

                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Pelanggaran</th>
                                    <th>Guru</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Poin</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                </tr>

                            </thead>

                            <tbody>

                            <?php if ($total_data > 0): ?>

                                <?php $no = 1; ?>

                                <?php while (mysqli_stmt_fetch($stmt)): ?>

                                    <tr>

                                        <td>
                                            <?= $no++ ?>
                                        </td>

                                        <td>
                                            <?= e($nama_siswa) ?>
                                        </td>

                                        <td>
                                            <?= e($nama_kelas) ?>
                                        </td>

                                        <td>
                                            <?= e($nama_pelanggaran) ?>
                                        </td>

                                        <td>
                                            <?= e($nama_guru) ?>
                                        </td>

                                        <td>
                                            <?= e($tanggal ?: '-') ?>
                                        </td>

                                        <td>
                                            <?= e($keterangan ?: '-') ?>
                                        </td>

                                        <td>
                                            <span class="badge bg-danger">
                                                <?= e($poin) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <?= e($tindakan ?: '-') ?>
                                        </td>

                                        <td>
                                            <span class="badge bg-success">
                                                <?= e($status) ?>
                                            </span>
                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>

                                    <td colspan="10"
                                        class="text-center py-4">

                                        <div class="text-muted">

                                            <h6 class="fw-bold">
                                                Belum Ada Riwayat
                                            </h6>

                                            <p class="mb-0">
                                                Belum ada siswa yang ditindaklanjuti.
                                            </p>

                                        </div>

                                    </td>

                                </tr>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                    <div class="text-muted small">
                        Menampilkan <?= $total_data ?>
                        data siswa yang sudah ditindaklanjuti.
                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

<?php
mysqli_stmt_close($stmt);
mysqli_close($koneksi);
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
