
<?php
session_start();

include 'includes/cek_session.php';
include 'config/koneksi.php';

$pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama_pelanggaran'] ?? '');
    $kategori_id = (int) ($_POST['pelanggaran_kategori_id'] ?? 0);
    $poin = (int) ($_POST['poin'] ?? 0);
    $deskripsi = trim($_POST['keterangan'] ?? '');

    // Validasi input
    if ($nama === '') {
        $pesan = "<div class='alert alert-danger'>
                    Nama pelanggaran wajib diisi!
                  </div>";
    } elseif ($kategori_id <= 0) {
        $pesan = "<div class='alert alert-danger'>
                    Silakan pilih kategori pelanggaran!
                  </div>";
    } elseif ($poin < 0) {
        $pesan = "<div class='alert alert-danger'>
                    Poin tidak boleh negatif!
                  </div>";
    } else {

        if ($deskripsi === '') {
            $deskripsi = '-';
        }

        // Periksa kategori
        $cek_kategori = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_pelanggaran_kategori
             WHERE id = ? AND status_aktif = 1"
        );

        mysqli_stmt_bind_param(
            $cek_kategori,
            "i",
            $kategori_id
        );

        mysqli_stmt_execute($cek_kategori);
        $hasil_kategori = mysqli_stmt_get_result($cek_kategori);

        if (mysqli_num_rows($hasil_kategori) === 0) {

            $pesan = "<div class='alert alert-danger'>
                        Kategori tidak ditemukan atau tidak aktif!
                      </div>";

        } else {

            // Simpan data pelanggaran
            $stmt = mysqli_prepare(
                $koneksi,
                "INSERT INTO t_pelanggaran
                (
                    pelanggaran_kategori_id,
                    nama,
                    poin,
                    deskripsi,
                    status_aktif
                )
                VALUES (?, ?, ?, ?, 1)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "isis",
                $kategori_id,
                $nama,
                $poin,
                $deskripsi
            );

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_close($stmt);
                mysqli_stmt_close($cek_kategori);

                header("Location: kelola_jenis_pelanggaran.php?status=sukses");
                exit;

            } else {

                $pesan = "<div class='alert alert-danger'>
                            Gagal menyimpan data pelanggaran!
                          </div>";

                mysqli_stmt_close($stmt);
            }
        }

        mysqli_stmt_close($cek_kategori);
    }
}

// Ambil daftar kategori aktif
$kategori_query = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_pelanggaran_kategori
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Jenis Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="layout/style.css">

    <style>
        .pelanggaran-container {
            max-width: 750px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .pelanggaran-header {
            margin-bottom: 25px;
        }

        .pelanggaran-header h2 {
            font-size: 26px;
            margin-bottom: 8px;
        }

        .pelanggaran-header p {
            color: #777;
            margin: 0;
        }

        .pelanggaran-card {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
        }

        .pelanggaran-card .form-label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .pelanggaran-card .form-control,
        .pelanggaran-card .form-select {
            width: 100%;
            box-sizing: border-box;
            padding: 11px 13px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            font-size: 14px;
        }

        .pelanggaran-card .form-control:focus,
        .pelanggaran-card .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.12);
        }

        .pelanggaran-card textarea {
            min-height: 110px;
            resize: vertical;
        }

        .pelanggaran-actions {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        .pelanggaran-actions .btn {
            padding: 11px 20px;
            border-radius: 6px;
        }

        @media (max-width: 576px) {
            .pelanggaran-card {
                padding: 20px;
            }

            .pelanggaran-actions {
                flex-direction: column;
            }

            .pelanggaran-actions .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<div class="pelanggaran-container">

    <div class="pelanggaran-header">
        <h2>Tambah Pelanggaran Baru</h2>
        <p>Silakan lengkapi formulir untuk menambahkan jenis pelanggaran.</p>
    </div>

    <?php echo $pesan; ?>

    <div class="pelanggaran-card">

        <form method="POST">

            <div class="mb-3">
                <label class="form-label">
                    Nama Pelanggaran
                </label>

                <input
                    type="text"
                    name="nama_pelanggaran"
                    class="form-control"
                    maxlength="150"
                    value="<?php echo htmlspecialchars($_POST['nama_pelanggaran'] ?? ''); ?>"
                    placeholder="Masukkan nama pelanggaran"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Kategori Pelanggaran
                </label>

                <select
                    name="pelanggaran_kategori_id"
                    class="form-select"
                    required
                >

                    <option value="">-- Pilih Kategori --</option>

                    <?php while ($kategori = mysqli_fetch_assoc($kategori_query)): ?>

                        <option
                            value="<?php echo $kategori['id']; ?>"
                            <?php echo (
                                ($_POST['pelanggaran_kategori_id'] ?? '') == $kategori['id']
                            ) ? 'selected' : ''; ?>
                        >
                            <?php echo htmlspecialchars($kategori['nama']); ?>
                        </option>

                    <?php endwhile; ?>

                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Poin Pelanggaran
                </label>

                <input
                    type="number"
                    name="poin"
                    class="form-control"
                    min="0"
                    value="<?php echo htmlspecialchars($_POST['poin'] ?? ''); ?>"
                    placeholder="Masukkan jumlah poin"
                    required
                >
            </div>

            <div class="mb-3">
                <label class="form-label">
                    Keterangan
                </label>

                <textarea
                    name="keterangan"
                    class="form-control"
                    rows="4"
                    placeholder="Masukkan keterangan pelanggaran"
                ><?php echo htmlspecialchars($_POST['keterangan'] ?? ''); ?></textarea>
            </div>

            <div class="pelanggaran-actions">

                <button type="submit" class="btn btn-primary">
                    Simpan Pelanggaran
                </button>

                <a href="kelola_jenis_pelanggaran.php"
                   class="btn btn-secondary">
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
