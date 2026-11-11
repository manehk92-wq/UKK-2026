
<?php
// tambah_kelas.php

include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Kelas</title>

    <link rel="stylesheet" href="layout/style.css">

    <style>
        .kelas-container {
            max-width: 750px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .kelas-header {
            margin-bottom: 25px;
        }

        .kelas-header h1 {
            font-size: 26px;
            margin-bottom: 8px;
        }

        .kelas-header p {
            color: #777;
            margin: 0;
        }

        .kelas-card {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            box-sizing: border-box;
            padding: 11px 13px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 14px;
            background: #fff;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .btn-simpan,
        .btn-kembali {
            display: inline-block;
            padding: 11px 20px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-simpan {
            background: #0d6efd;
            color: #fff;
        }

        .btn-simpan:hover {
            background: #0b5ed7;
        }

        .btn-kembali {
            background: #6c757d;
            color: #fff;
        }

        .btn-kembali:hover {
            background: #5c636a;
        }

        @media (max-width: 576px) {
            .kelas-card {
                padding: 20px;
            }

            .form-actions {
                flex-direction: column;
            }

            .form-actions button,
            .form-actions a {
                text-align: center;
            }
        }
    </style>
</head>

<body>

<div class="kelas-container">

    <div class="kelas-header">
        <h1>Tambah Kelas</h1>
        <p>Silakan lengkapi formulir untuk menambahkan data kelas baru.</p>
    </div>

    <div class="kelas-card">

        <form action="proses_tambah_kelas.php" method="POST">

            <div class="form-group">
                <label>Nama Kelas</label>
                <input
                    type="text"
                    name="nama"
                    placeholder="Contoh: X RPL 1"
                    required
                >
            </div>

            <div class="form-group">
                <label>Tingkat</label>
                <input
                    type="text"
                    name="tingkat"
                    placeholder="Contoh: X, XI, XII"
                    required
                >
            </div>

            <div class="form-group">
                <label>Jurusan</label>
                <input
                    type="text"
                    name="jurusan"
                    placeholder="Contoh: RPL, TKJ, AKL"
                    required
                >
            </div>

            <div class="form-group">
                <label>Status Aktif</label>

                <select name="status_aktif" required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <div class="form-actions">

                <button type="submit" class="btn-simpan">
                    Simpan Kelas
                </button>

                <a href="kelola_kelas.php" class="btn-kembali">
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>
