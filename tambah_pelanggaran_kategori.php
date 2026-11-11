
<?php
// tambah_pelanggaran_kategori.php

include 'includes/cek_session.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori Pelanggaran</title>

    <link rel="stylesheet" href="layout/style.css">

    <style>
        .form-page {
            max-width: 850px;
            margin: 20px auto;
        }

        .form-card {
            background: #fff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.07);
            border: 1px solid #e9edf3;
        }

        .form-header {
            margin-bottom: 25px;
            padding-bottom: 18px;
            border-bottom: 1px solid #e9edf3;
        }

        .form-header h2 {
            color: #17233f;
            font-size: 23px;
            font-weight: bold;
            margin: 0 0 8px;
        }

        .form-header p {
            color: #6c757d;
            margin: 0;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: #344054;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 11px 13px;
            border: 1px solid #d7deea;
            border-radius: 8px;
            font-size: 14px;
            color: #263248;
            background: #fff;
            box-sizing: border-box;
            outline: none;
            transition: 0.2s;
        }

        .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
        }

        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e9edf3;
        }

        .btn-simpan {
            background: #0d6efd;
            color: #fff;
            border: none;
            padding: 11px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-simpan:hover {
            background: #0b5ed7;
        }

        .btn-kembali {
            display: inline-block;
            background: #6c757d;
            color: #fff;
            text-decoration: none;
            padding: 11px 22px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            transition: 0.2s;
        }

        .btn-kembali:hover {
            background: #5c636a;
            color: #fff;
        }

        @media (max-width: 600px) {
            .form-card {
                padding: 20px 16px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn-simpan,
            .btn-kembali {
                text-align: center;
            }
        }
    </style>
</head>

<body>

    <?php include 'layout/sidebar.php'; ?>

    <main class="menu-page-content">

        <div class="form-page">

            <div class="form-card">

                <div class="form-header">
                    <h2>📂 Tambah Kategori Pelanggaran</h2>
                    <p>Silakan isi data kategori pelanggaran dengan benar.</p>
                </div>

                <form action="proses_tambah_pelanggaran_kategori.php" method="POST">

                    <div class="form-group">
                        <label for="nama">Nama Kategori</label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            class="form-control"
                            placeholder="Contoh: Pelanggaran Ringan"
                            maxlength="100"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            class="form-control"
                            placeholder="Masukkan deskripsi kategori pelanggaran"
                        ></textarea>
                    </div>

                    <div class="form-group">
                        <label for="status_aktif">Status Aktif</label>
                        <select
                            id="status_aktif"
                            name="status_aktif"
                            class="form-control"
                            required
                        >
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-simpan">
                            💾 Simpan
                        </button>

                        <a href="kelola_pelanggaran_kategori.php" class="btn-kembali">
                            ← Kembali
                        </a>
                    </div>

                </form>

            </div>

        </div>

    </main>

</body>
</html>
