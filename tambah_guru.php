
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        /* Sidebar tetap */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #212529;
            color: white;
            padding: 20px 15px;
        }

        .sidebar h4 {
            text-align: center;
            margin-bottom: 30px;
            font-weight: bold;
        }

        .sidebar a {
            display: block;
            color: #ddd;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #0d6efd;
            color: white;
        }

        /* Konten utama */
        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.07);
        }

        .card-header {
            background: white;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .form-label {
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 190px;
            }

            .main-content {
                margin-left: 190px;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h4>
            <i class="bi bi-mortarboard-fill"></i>
            SISTEM SEKOLAH
        </h4>

        <a href="dashboard.php">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        <a href="kelola_siswa.php">
            <i class="bi bi-people-fill me-2"></i> Data Siswa
        </a>

        <a href="kelola_guru.php" class="active">
            <i class="bi bi-person-badge me-2"></i> Data Guru
        </a>

        <a href="kelola_kelas.php">
            <i class="bi bi-building me-2"></i> Data Kelas
        </a>

        <a href="logout.php">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="main-content">

        <div class="mb-4">
            <h3 class="fw-bold">
                <i class="bi bi-person-plus-fill text-primary"></i>
                Tambah Guru
            </h3>

            <p class="text-muted">
                Silakan isi formulir untuk menambahkan data guru baru.
            </p>
        </div>

        <!-- CARD FORM -->
        <div class="card">

            <div class="card-header">
                <h5 class="mb-0 fw-bold">
                    Formulir Data Guru
                </h5>
            </div>

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

    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
