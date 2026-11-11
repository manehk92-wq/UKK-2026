
<?php
// edit_guru.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'] ?? '';

if (!ctype_digit((string) $id) || $id === '') {
    die("ID guru tidak valid!");
}

// Ambil data guru
$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_guru WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$guru = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$guru) {
    die("Data guru tidak ditemukan!");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Guru</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            margin: 0;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            color: #334155;
        }

        .content-wrapper {
            width: 100%;
            padding: 30px;
            box-sizing: border-box;
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
            width: 100%;
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

    <?php include 'layout/sidebar.php'; ?>

    <div class="content-wrapper">

        <div class="page-title">
            <i class="bi bi-person-badge-fill text-primary"></i>
            Edit Data Guru
        </div>

        <div class="card form-card">

            <div class="card-header-custom">
                <h4>
                    <i class="bi bi-pencil-square me-2"></i>
                    Form Edit Guru
                </h4>
                <small>Silakan ubah informasi data guru di bawah ini.</small>
            </div>

            <form action="proses_edit_guru.php" method="POST">

                <input type="hidden" name="id"
                    value="<?= htmlspecialchars($guru['id']); ?>">

                <div class="card-body">

                    <div class="row g-4">

                        <!-- Kolom Kiri -->
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text"
                                    name="nip"
                                    class="form-control"
                                    value="<?= htmlspecialchars($guru['nip'] ?? ''); ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Guru</label>
                                <input type="text"
                                    name="nama"
                                    class="form-control"
                                    value="<?= htmlspecialchars($guru['nama'] ?? ''); ?>"
                                    required>
                            </div>

                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    value="<?= htmlspecialchars($guru['email'] ?? ''); ?>"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status Aktif</label>
                                <select name="status_aktif" class="form-select" required>

                                    <option value="Aktif"
                                        <?= ($guru['status_aktif'] ?? '') == 'Aktif' ? 'selected' : ''; ?>>
                                        Aktif
                                    </option>

                                    <option value="Tidak Aktif"
                                        <?= ($guru['status_aktif'] ?? '') == 'Tidak Aktif' ? 'selected' : ''; ?>>
                                        Tidak Aktif
                                    </option>

                                </select>
                            </div>

                        </div>

                        <!-- User ID -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">User ID</label>
                                <input type="number"
                                    name="user_id"
                                    class="form-control"
                                    value="<?= htmlspecialchars($guru['user_id'] ?? ''); ?>"
                                    readonly>
                                <small class="text-muted">
                                    ID pengguna yang terhubung dengan data guru.
                                </small>
                            </div>
                        </div>

                        <!-- Password Baru -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Password Baru</label>
                                <input type="password"
                                    name="password"
                                    id="password"
                                    class="form-control"
                                    placeholder="Kosongkan jika tidak ingin mengganti password"
                                    minlength="6"
                                    autocomplete="new-password">
                                <small class="text-muted">
                                    Minimal 6 karakter. Kosongkan jika tetap menggunakan password lama.
                                </small>
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password"
                                    name="konfirmasi_password"
                                    id="konfirmasi_password"
                                    class="form-control"
                                    placeholder="Ulangi password baru"
                                    minlength="6"
                                    autocomplete="new-password">
                            </div>
                        </div>

                    </div>

                </div>

                <div class="card-footer-custom">

                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-save me-1"></i>
                        Simpan Perubahan
                    </button>

                    <a href="kelola_guru.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Kembali
                    </a>

                </div>

            </form>

        </div>

    </div>

    <script>
        const password = document.getElementById('password');
        const konfirmasi = document.getElementById('konfirmasi_password');

        document.querySelector('form').addEventListener('submit', function(event) {
            if (password.value !== konfirmasi.value) {
                event.preventDefault();
                alert('Password baru dan konfirmasi password tidak sama!');
                konfirmasi.focus();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
