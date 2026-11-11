
<?php
// tambah_siswa.php
include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa</title>

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
        <h4><i class="bi bi-mortarboard-fill"></i> SISTEM SEKOLAH</h4>

        <a href="dashboard.php">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        <a href="kelola_siswa.php" class="active">
            <i class="bi bi-people-fill me-2"></i> Data Siswa
        </a>

        <a href="kelola_guru.php">
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
                Tambah Siswa
            </h3>
            <p class="text-muted">
                Silakan isi formulir untuk menambahkan data siswa baru.
            </p>
        </div>

        <div class="card">

            <div class="card-header">
                <h5 class="mb-0 fw-bold">Formulir Data Siswa</h5>
            </div>

            <div class="card-body p-4">

                <form action="proses_tambah_siswa.php" method="POST">

                    <div class="row">

  <div class="form-group">
    <label>NIS</label>
    <input type="text" name="nis" class="form-control" placeholder="Masukkan NIS" 
           maxlength="15" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
</div>

<div class="form-group">
    <label>NISN</label>
    <input type="text" name="nisn" class="form-control" placeholder="Masukkan NISN" 
           maxlength="10" onkeypress="return event.charCode >= 48 && event.charCode <= 57" required>
</div>

                        <!-- Nama -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" name="nama"
                                class="form-control"
                                placeholder="Masukkan nama lengkap siswa"
                                required>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="jenis_kelamin"
                                class="form-select" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                class="form-control"
                                required>
                        </div>

                        <!-- Alamat -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat"
                                class="form-control"
                                rows="4"
                                placeholder="Masukkan alamat lengkap siswa"
                                required></textarea>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Status Aktif</label>
                            <select name="status_aktif"
                                class="form-select" required>
                                <option value="1">Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>

                    </div>

                    <hr>

                    <!-- Tombol -->
                    <div class="d-flex justify-content-end gap-2">

                        <a href="kelola_siswa.php"
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
