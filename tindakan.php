
<?php
session_start();

require_once __DIR__ . '/config/koneksi.php';

// Periksa login
if (
    !isset($_SESSION['login']) ||
    $_SESSION['login'] !== true ||
    !isset($_SESSION['id_user'])
) {
    header("Location: login.php");
    exit;
}

// Token keamanan form
if (empty($_SESSION['csrf_tindakan'])) {
    $_SESSION['csrf_tindakan'] = bin2hex(random_bytes(32));
}

$pesan = '';
$jenis_pesan = 'success';

// Proses update tindakan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_POST['csrf_token'] ?? '';

    if (!hash_equals($_SESSION['csrf_tindakan'], $token)) {
        die("Permintaan tidak valid. Silakan muat ulang halaman.");
    }

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $tindakan = trim($_POST['tindakan'] ?? '');
    $status = trim($_POST['status'] ?? '');

    if (!$id || $tindakan === '' || $status === '') {
        $_SESSION['flash_tindakan'] = [
            'jenis' => 'danger',
            'pesan' => 'Semua data tindakan dan status wajib diisi!'
        ];
    } else {

        $sql = "UPDATE t_pelanggaran_siswa
                SET tindakan = ?, status = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($koneksi, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "ssi",
                $tindakan,
                $status,
                $id
            );

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['flash_tindakan'] = [
                    'jenis' => 'success',
                    'pesan' => 'Tindakan pelanggaran berhasil diperbarui!'
                ];
            } else {
                $_SESSION['flash_tindakan'] = [
                    'jenis' => 'danger',
                    'pesan' => 'Gagal memperbarui tindakan.'
                ];
            }

            mysqli_stmt_close($stmt);

        } else {
            $_SESSION['flash_tindakan'] = [
                'jenis' => 'danger',
                'pesan' => 'Query database gagal.'
            ];
        }
    }

    header("Location: tindakan.php");
    exit;
}

// Ambil pesan
if (isset($_SESSION['flash_tindakan'])) {
    $pesan = $_SESSION['flash_tindakan']['pesan'];
    $jenis_pesan = $_SESSION['flash_tindakan']['jenis'];

    unset($_SESSION['flash_tindakan']);
}

// Ambil data pelanggaran
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
        ORDER BY id DESC";

$result = mysqli_query($koneksi, $sql);

if (!$result) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}

// Fungsi keamanan output
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Tindakan Pelanggaran Siswa</title>

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

        .table th {
            background: #0d6efd;
            color: white;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        .form-tindakan {
            min-width: 210px;
        }

        .form-status {
            min-width: 160px;
        }
    </style>
</head>

<body>

<?php include __DIR__ . '/layout/sidebar.php'; ?>

<div class="container-fluid content">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

        <div>
            <h3 class="fw-bold">Data Tindakan Pelanggaran</h3>
            <p class="text-muted mb-0">
                Kelola tindak lanjut pelanggaran siswa.
            </p>
        </div>

        <a href="dashboard.php" class="btn btn-secondary">
            Kembali ke Dashboard
        </a>

    </div>

    <?php if ($pesan !== ''): ?>
        <div class="alert alert-<?= e($jenis_pesan) ?> alert-dismissible fade show">
            <?= e($pesan) ?>
            <button type="button" class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card">

        <div class="card-body">

            <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
                <h5 class="fw-bold">Daftar Pelanggaran Siswa</h5>
                <span class="badge bg-primary">
                    Total Data: <?= mysqli_num_rows($result) ?>
                </span>
            </div>

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

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
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($result) > 0): ?>

                        <?php $no = 1; ?>

                        <?php while ($row = mysqli_fetch_assoc($result)): ?>

                            <tr>

                                <td><?= $no++ ?></td>

                                <td><?= e($row['nama_siswa']) ?></td>

                                <td><?= e($row['nama_kelas']) ?></td>

                                <td><?= e($row['nama_pelanggaran']) ?></td>

                                <td><?= e($row['nama_guru']) ?></td>

                                <td><?= e($row['tanggal']) ?></td>

                                <td><?= e($row['keterangan']) ?></td>

                                <td>
                                    <span class="badge bg-danger">
                                        <?= e($row['poin']) ?>
                                    </span>
                                </td>

                                <form method="POST" action="tindakan.php">

                                    <input type="hidden" name="csrf_token"
                                           value="<?= e($_SESSION['csrf_tindakan']) ?>">

                                    <input type="hidden" name="id"
                                           value="<?= (int)$row['id'] ?>">

                                    <td>
                                        <textarea
                                            name="tindakan"
                                            class="form-control form-tindakan"
                                            rows="2"
                                            required
                                            placeholder="Masukkan tindakan..."
                                        ><?= e($row['tindakan']) ?></textarea>
                                    </td>

                                    <td>
                                        <select name="status"
                                                class="form-select form-status"
                                                required>

                                            <option value="">
                                                Pilih Status
                                            </option>

                                            <?php
                                            $daftar_status = [
                                                'Belum Ditindaklanjuti',
                                                'Diproses',
                                                'Selesai'
                                            ];

                                            foreach ($daftar_status as $s):
                                            ?>

                                                <option value="<?= e($s) ?>"
                                                    <?= $row['status'] === $s ? 'selected' : '' ?>>
                                                    <?= e($s) ?>
                                                </option>

                                            <?php endforeach; ?>

                                        </select>
                                    </td>

                                    <td>
                                        <button type="submit"
                                                class="btn btn-primary btn-sm">
                                            Simpan
                                        </button>
                                    </td>

                                </form>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="11" class="text-center py-4">
                                Belum ada data pelanggaran siswa.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
