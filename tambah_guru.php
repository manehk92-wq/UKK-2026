<?php
// tambah_guru.php
include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Guru</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="layout/style.css">
</head>

<body>
<div class="container-fluid">
    <div class="row">

        <?php include 'layout/sidebar.php'; ?>

        <!-- KONTEN -->
        <main class="menu-page-content">
            <h1 class="mb-4">Tambah Guru</h1>

            <p class="text-muted mb-4">
                Silakan isi formulir di bawah untuk menambahkan data guru baru.
            </p>

            <!-- CARD FORM -->
            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <form action="proses_tambah_guru.php" method="POST">

                        <div class="row">

                            <!-- NIP -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NIP</label>
                                <input type="text"
                                       name="nip"
                                       class="form-control"
                                       placeholder="Masukkan NIP guru"
                                       required>
                            </div>

                            <!-- Nama Guru -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Guru</label>
                                <input type="text"
                                       name="nama"
                                       class="form-control"
                                       placeholder="Masukkan nama lengkap guru"
                                       required>
                            </div>

                            <!-- Email -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email"
                                       name="email"
                                       class="form-control"
                                       placeholder="Masukkan email guru"
                                       autocomplete="email"
                                       required>
                            </div>

                            <!-- Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Password</label>
                                <input type="password"
                                       name="password"
                                       class="form-control"
                                       placeholder="Masukkan password"
                                       minlength="8"
                                       autocomplete="new-password"
                                       required>
                                <small class="text-muted">
                                    Minimal 8 karakter.
                                </small>
                            </div>

                            <!-- Konfirmasi Password -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Konfirmasi Password</label>
                                <input type="password"
                                       name="konfirmasi_password"
                                       class="form-control"
                                       placeholder="Ulangi password"
                                       minlength="8"
                                       autocomplete="new-password"
                                       required>
                            </div>

                            <!-- Status Aktif -->
                            <div class="col-md-6 mb-4">
                                <label class="form-label">Status Aktif</label>
                                <select name="status_aktif"
                                        class="form-select"
                                        required>
                                    <option value="1">Aktif</option>
                                    <option value="0">Tidak Aktif</option>
                                </select>
                            </div>

                        </div>

                        <hr>

                        <!-- TOMBOL -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="kelola_guru.php"
                               class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i>
                                Kembali
                            </a>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i>
                                Simpan Data
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </main>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
