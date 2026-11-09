```php
<?php
// hapus_tahun_ajaran.php

session_start();

include 'includes/cek_session.php';
include 'config/koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'] ?? '';

if (!ctype_digit((string) $id) || $id === '') {
    header('Location: kelola_tahun_ajaran.php');
    exit;
}

$id = (int) $id;

// Ambil nama tahun ajaran sebelum dihapus
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT nama FROM t_tahun_ajaran WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$hasil = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($hasil);
mysqli_stmt_close($stmt);

// Periksa apakah data ditemukan
if (!$data) {
    echo "<script>
        alert('Data tahun ajaran tidak ditemukan!');
        window.location.href = 'kelola_tahun_ajaran.php';
    </script>";
    exit;
}

// Mulai transaksi
mysqli_begin_transaction($koneksi);

try {

    // Cek apakah tahun ajaran masih digunakan
    $cek = mysqli_prepare(
        $koneksi,
        "SELECT COUNT(*) AS jumlah
         FROM t_pelanggaran_siswa
         WHERE tahun_ajaran_id = ?"
    );

    mysqli_stmt_bind_param($cek, "i", $id);
    mysqli_stmt_execute($cek);

    $hasil_cek = mysqli_stmt_get_result($cek);
    $jumlah_data = mysqli_fetch_assoc($hasil_cek)['jumlah'];

    mysqli_stmt_close($cek);

    // Jika masih digunakan, batalkan penghapusan
    if ($jumlah_data > 0) {
        mysqli_rollback($koneksi);

        echo "<script>
            alert('Tahun ajaran tidak bisa dihapus karena masih digunakan oleh $jumlah_data data pelanggaran siswa!');
            window.location.href = 'kelola_tahun_ajaran.php';
        </script>";
        exit;
    }

    // Hapus tahun ajaran jika tidak memiliki data terkait
    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_tahun_ajaran WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {

        mysqli_stmt_close($stmt);

        // Simpan aktivitas ke tabel log jika tabel tersedia
        $id_user = $_SESSION['id_user'] ?? null;
        $waktu = date('Y-m-d H:i:s');
        $aktivitas = "hapus tahun ajaran: " . $data['nama'];

        if ($id_user !== null) {

            $cek_tabel = mysqli_query(
                $koneksi,
                "SHOW TABLES LIKE 'tbl_log'"
            );

            if ($cek_tabel && mysqli_num_rows($cek_tabel) > 0) {

                $log = mysqli_prepare(
                    $koneksi,
                    "INSERT INTO tbl_log (id_user, aktivitas, waktu)
                     VALUES (?, ?, ?)"
                );

                if ($log) {
                    mysqli_stmt_bind_param(
                        $log,
                        "iss",
                        $id_user,
                        $aktivitas,
                        $waktu
                    );

                    mysqli_stmt_execute($log);
                    mysqli_stmt_close($log);
                }
            }

            if ($cek_tabel) {
                mysqli_free_result($cek_tabel);
            }
        }

        // Simpan perubahan
        mysqli_commit($koneksi);

        echo "<script>
            alert('Tahun ajaran berhasil dihapus!');
            window.location.href = 'kelola_tahun_ajaran.php';
        </script>";
        exit;

    } else {

        mysqli_stmt_close($stmt);
        mysqli_rollback($koneksi);

        echo "<script>
            alert('Tahun ajaran gagal dihapus!');
            window.location.href = 'kelola_tahun_ajaran.php';
        </script>";
        exit;
    }

} catch (mysqli_sql_exception $e) {

    mysqli_rollback($koneksi);

    echo "<script>
        alert('Terjadi kesalahan saat menghapus tahun ajaran. Data tidak jadi dihapus.');
        window.location.href = 'kelola_tahun_ajaran.php';
    </script>";
    exit;
}
?>