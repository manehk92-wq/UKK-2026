
<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil ID siswa
$id = $_GET['id'] ?? '';

if ($id == '') {
    die("ID siswa tidak ditemukan!");
}

// Ambil data siswa
$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_siswa WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$siswa = mysqli_fetch_assoc($result);

if (!$siswa) {
    die("Data siswa tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Siswa</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            color: #334155;
        }

        .content-wrapper {
            padding: 30px;
        }

        .page-title {
            font-size: 25px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 25px;
        }

        .form-card {
            background: #fff;
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
            overflow: hidden;
        }

        .card-header-custom {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: white;
            padding: 22px 28px;
        }

        .card-header-custom h4 {
            margin: 0;
            font-size: 21px;
            font-weight: 600;
        }

        .card-body {
            padding: 30px;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 11px 13px;
            font-size: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
        }

        textarea.form-control {
            min-height: 110px;
        }

        .card-footer-custom {
            padding: 20px 30px;
            background: #fff;
            border-top: 1px solid #e2e8f0;
        }

        .btn-primary {
            background: #2563eb;
            border: none;
            padding: 11px 22px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .btn-secondary {
            padding: 11px 22px;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .content-wrapper {
                padding: 15px;
            }

            .card-body {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <!-- Sidebar -->
    <?php include 'layout/sidebar.php'; ?>

    <!-- Konten Utama -->
    <div class="content-wrapper">

        <div class="page-title">
            <i class="bi bi-people-fill text-primary"></i>
            Edit Data Siswa
        </div>

        <div class="card form-card">

            <!-- Header -->
            <div class="card-header-custom">
                <h4>
                    <i class="bi bi-pencil-square me-2"></i>
                    Form Edit Siswa
                </h4>
                <small>Silakan ubah informasi data siswa di bawah ini.</small>
            </div>

            <!-- Form -->
            <form action="proses_edit_siswa.php" method="POST">

                <input type="hidden" name="id"
                    value="<?= htmlspecialchars($siswa['id']); ?>">

                <div class="card-body">

                    <div class="row g-4">

                        <!-- Kolom Kiri -->
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label">NIS</label>
                                <input type="text" name="nis"
                                    class="form-control"
                                    value="<?= htmlspecialchars($siswa['nis'] ?? ''); ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">NISN</label>
                                <input type="text" name="nisn"
                                    class="form-control"
                                    value="<?= htmlspecialchars($siswa['nisn'] ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Siswa</label>
                                <input type="text" name="nama"
                                    class="form-control"
                                    value="<?= htmlspecialchars($siswa['nama'] ?? ''); ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-select" required>
                                    <option value="Laki-laki"
                                        <?= ($siswa['jenis_kelamin'] ?? '') == 'Laki-laki' ? 'selected' : ''; ?>>
                                        Laki-laki
                                    </option>

                                    <option value="Perempuan"
                                        <?= ($siswa['jenis_kelamin'] ?? '') == 'Perempuan' ? 'selected' : ''; ?>>
                                        Perempuan
                                    </option>
                                </select>
                            </div>

                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir"
                                    class="form-control"
                                    value="<?= htmlspecialchars($siswa['tanggal_lahir'] ?? ''); ?>">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat"
                                    class="form-control"
                                    rows="4"><?= htmlspecialchars($siswa['alamat'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status Aktif</label>
                                <select name="status_aktif" class="form-select" required>
                                    <option value="Aktif"
                                        <?= ($siswa['status_aktif'] ?? '') == 'Aktif' ? 'selected' : ''; ?>>
                                        Aktif
                                    </option>

                                    <option value="Tidak Aktif"
                                        <?= ($siswa['status_aktif'] ?? '') == 'Tidak Aktif' ? 'selected' : ''; ?>>
                                        Tidak Aktif
                                    </option>
                                </select>
                            </div>

                        </div>

                    </div>

                </div>

                <!-- Footer -->
                <div class="card-footer-custom">

                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-save me-1"></i>
                        Simpan Perubahan
                    </button>

                    <a href="kelola_siswa.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>

                </div>

            </form>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
