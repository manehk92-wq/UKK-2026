
<?php
// ==========================================
// PROSES CATATAN PELANGGARAN - SIPENSIS
// ==========================================

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/koneksi.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// ==========================================
// CEK LOGIN
// ==========================================

if (
    !isset($_SESSION['login']) ||
    $_SESSION['login'] !== true ||
    !isset($_SESSION['id_user'])
) {
    header("Location: login.php");
    exit;
}

// Hanya menerima POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: catat_pelanggaran.php");
    exit;
}

// ==========================================
// FUNGSI KEMBALI
// ==========================================

function kembaliDenganError($pesan)
{
    $_SESSION['pesan_error'] = $pesan;
    header("Location: catat_pelanggaran.php");
    exit;
}

// ==========================================
// PROSES PENYIMPANAN
// ==========================================

try {

    // ID USER LOGIN
    $id_user = (int) $_SESSION['id_user'];

    // ======================================
    // CARI GURU BERDASARKAN USER LOGIN
    // ======================================

    $stmtGuru = mysqli_prepare($koneksi, "
        SELECT id, nama
        FROM t_guru
        WHERE user_id = ?
        AND status_aktif = 1
        LIMIT 1
    ");

    mysqli_stmt_bind_param($stmtGuru, "i", $id_user);
    mysqli_stmt_execute($stmtGuru);

    $dataGuru = mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmtGuru)
    );

    mysqli_stmt_close($stmtGuru);

    if (!$dataGuru) {
        kembaliDenganError(
            "Akun Anda belum terhubung dengan data guru atau guru tidak aktif."
        );
    }

    // ID GURU OTOMATIS
    $guru_id = (int) $dataGuru['id'];

    // ======================================
    // AMBIL DATA FORM
    // ======================================

    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);
    $pelanggaran_id = (int) ($_POST['pelanggaran_id'] ?? 0);

    $tanggal = trim($_POST['tanggal'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $keterangan = trim($_POST['keterangan'] ?? '');
    $tindakan = trim($_POST['tindakan'] ?? '');

    // ======================================
    // VALIDASI FORM
    // ======================================

    if (
        $siswa_id <= 0 ||
        $kelas_id <= 0 ||
        $tahun_ajaran_id <= 0 ||
        $pelanggaran_id <= 0 ||
        $tanggal === ''
    ) {
        kembaliDenganError("Data pelanggaran belum lengkap.");
    }

    // VALIDASI TANGGAL
    $formatTanggal = DateTime::createFromFormat('!Y-m-d', $tanggal);

    if (
        !$formatTanggal ||
        $formatTanggal->format('Y-m-d') !== $tanggal
    ) {
        kembaliDenganError("Format tanggal tidak valid.");
    }

    // VALIDASI STATUS
    $status_valid = [
        'Ditindaklanjuti',
        'Proses',
        'Selesai'
    ];

    if (!in_array($status, $status_valid, true)) {
        kembaliDenganError("Status pelanggaran tidak valid.");
    }

    // ======================================
    // VALIDASI SISWA
    // ======================================

    $stmtSiswa = mysqli_prepare($koneksi, "
        SELECT id
        FROM t_siswa
        WHERE id = ?
        AND status_aktif = 1
        LIMIT 1
    ");

    mysqli_stmt_bind_param($stmtSiswa, "i", $siswa_id);
    mysqli_stmt_execute($stmtSiswa);

    $dataSiswa = mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmtSiswa)
    );

    mysqli_stmt_close($stmtSiswa);

    if (!$dataSiswa) {
        kembaliDenganError("Siswa tidak ditemukan atau tidak aktif.");
    }

    // ======================================
    // VALIDASI KELAS SISWA
    // ======================================

    $stmtKelas = mysqli_prepare($koneksi, "
        SELECT siswa_id
        FROM t_kelas_siswa
        WHERE siswa_id = ?
        AND kelas_id = ?
        LIMIT 1
    ");

    mysqli_stmt_bind_param(
        $stmtKelas,
        "ii",
        $siswa_id,
        $kelas_id
    );

    mysqli_stmt_execute($stmtKelas);

    $dataKelas = mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmtKelas)
    );

    mysqli_stmt_close($stmtKelas);

    if (!$dataKelas) {
        kembaliDenganError("Kelas tidak sesuai dengan siswa yang dipilih.");
    }

    // ======================================
    // VALIDASI TAHUN AJARAN
    // ======================================

    $stmtTahun = mysqli_prepare($koneksi, "
        SELECT id
        FROM t_tahun_ajaran
        WHERE id = ?
        AND status_aktif = 1
        LIMIT 1
    ");

    mysqli_stmt_bind_param(
        $stmtTahun,
        "i",
        $tahun_ajaran_id
    );

    mysqli_stmt_execute($stmtTahun);

    $dataTahun = mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmtTahun)
    );

    mysqli_stmt_close($stmtTahun);

    if (!$dataTahun) {
        kembaliDenganError("Tahun ajaran tidak ditemukan atau tidak aktif.");
    }

    // ======================================
    // AMBIL POIN DAN KATEGORI PELANGGARAN
    // ======================================

    $stmtPelanggaran = mysqli_prepare($koneksi, "
        SELECT poin, pelanggaran_kategori_id
        FROM t_pelanggaran
        WHERE id = ?
        AND status_aktif = 1
        LIMIT 1
    ");

    mysqli_stmt_bind_param(
        $stmtPelanggaran,
        "i",
        $pelanggaran_id
    );

    mysqli_stmt_execute($stmtPelanggaran);

    $dataPelanggaran = mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmtPelanggaran)
    );

    mysqli_stmt_close($stmtPelanggaran);

    if (!$dataPelanggaran) {
        kembaliDenganError("Jenis pelanggaran tidak ditemukan atau tidak aktif.");
    }

    $poin = (int) $dataPelanggaran['poin'];

    $kategori_id = (int) $dataPelanggaran['pelanggaran_kategori_id'];

    // ======================================
    // SIMPAN CATATAN PELANGGARAN
    // ======================================

    $sql = "
        INSERT INTO t_pelanggaran_siswa
        (
            siswa_id,
            kelas_id,
            tahun_ajaran_id,
            pelanggaran_id,
            pelanggaran_kategori_id,
            guru_id,
            tanggal,
            poin,
            keterangan,
            tindakan,
            status
        )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = mysqli_prepare($koneksi, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iiiiiisisss",
        $siswa_id,
        $kelas_id,
        $tahun_ajaran_id,
        $pelanggaran_id,
        $kategori_id,
        $guru_id,
        $tanggal,
        $poin,
        $keterangan,
        $tindakan,
        $status
    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    // ======================================
    // BERHASIL
    // ======================================

    $_SESSION['pesan_sukses'] =
        "Catatan pelanggaran berhasil disimpan. Guru pelapor: " .
        $dataGuru['nama'];

    header("Location: catat_pelanggaran.php");
    exit;

} catch (mysqli_sql_exception $e) {

    error_log("Error Catat Pelanggaran: " . $e->getMessage());

    kembaliDenganError(
        "Gagal menyimpan catatan pelanggaran. Silakan periksa database."
    );
}
