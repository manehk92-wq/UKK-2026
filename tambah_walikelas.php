
<?php
// tambah_walikelas.php

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil data tahun ajaran
$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC"
);

// Ambil data kelas
$kelas = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_kelas ORDER BY nama ASC"
);

// Ambil data guru
$guru = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_guru ORDER BY nama ASC"
);

if (!$tahun || !$kelas || !$guru) {
    die("Gagal mengambil data: " . htmlspecialchars(mysqli_error($koneksi)));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Wali Kelas</title>

    <link rel="stylesheet" href="layout/style.css">

    <style>
        .walikelas-container {
            max-width: 750px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .walikelas-header {
            margin-bottom: 25px;
        }

        .walikelas-header h1 {
            font-size: 26px;
            margin-bottom: 8px;
        }

        .walikelas-header p {
            color: #777;
            margin: 0;
        }

        .walikelas-card {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            box-sizing: border-box;
            padding: 11px 13px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 14px;
            background: #fff;
        }

        .form-group select:focus,
        .form-group input:focus {
            outline: none;
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13,110,253,0.12);
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
            color: white;
        }

        .btn-simpan:hover {
            background: #0b5ed7;
        }

        .btn-kembali {
            background: #6c757d;
            color: white;
        }

        .btn-kembali:hover {
            background: #5c636a;
        }

        @media (max-width: 576px) {
            .walikelas-card {
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

<div class="walikelas-container">

    <div class="walikelas-header">
        <h1>Tambah Wali Kelas</h1>
        <p>Silakan lengkapi formulir penambahan wali kelas.</p>
    </div>

    <div class="walikelas-card">

        <form action="proses_tambah_walikelas.php" method="POST">

            <div class="form-group">
                <label>Tahun Ajaran</label>

                <select name="tahun_ajaran_id" required>
                    <option value="">-- Pilih Tahun Ajaran --</option>

                    <?php while ($row = mysqli_fetch_assoc($tahun)) { ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['nama']); ?>
                        </option>
                    <?php } ?>

                </select>
            </div>

            <div class="form-group">
                <label>Kelas</label>

                <select name="kelas_id" required>
                    <option value="">-- Pilih Kelas --</option>

                    <?php while ($row = mysqli_fetch_assoc($kelas)) { ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['nama']); ?>
                        </option>
                    <?php } ?>

                </select>
            </div>

            <div class="form-group">
                <label>Guru</label>

                <select name="guru_id" required>
                    <option value="">-- Pilih Guru --</option>

                    <?php while ($row = mysqli_fetch_assoc($guru)) { ?>
                        <option value="<?php echo $row['id']; ?>">
                            <?php echo htmlspecialchars($row['nama']); ?>
                        </option>
                    <?php } ?>

                </select>
            </div>

            <div class="form-group">
                <label>Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" required>
            </div>

            <div class="form-group">
                <label>Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" required>
            </div>

            <div class="form-group">
                <label>Status</label>

                <select name="status_aktif" required>
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-simpan">
                    Simpan Wali Kelas
                </button>

                <a href="kelola_walikelas.php" class="btn-kembali">
                    Kembali
                </a>
            </div>

        </form>

    </div>

</div>

</body>
</html>
