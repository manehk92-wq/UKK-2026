<?php
// catat_pelanggaran.php
include 'includes/cek_session.php';
include 'config/koneksi.php';

// Daftar siswa beserta nama kelas
// Daftar siswa beserta nama kelas
$siswa = mysqli_query(
    $koneksi,
    "SELECT s.id, s.nis, s.nama, k.nama AS nama_kelas
     FROM t_siswa s
     LEFT JOIN t_kelas_siswa ks ON s.id = ks.siswa_id
     LEFT JOIN t_kelas k ON ks.kelas_id = k.id
     WHERE s.status_aktif = 1
     ORDER BY s.nama ASC"
);


// Daftar tahun ajaran
$tahun = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_tahun_ajaran
     WHERE status_aktif = 1
     ORDER BY id DESC"
);

// Daftar pelanggaran beserta kategori
$pelanggaran = mysqli_query(
    $koneksi,
    "SELECT id, nama, poin, pelanggaran_kategori_id
     FROM t_pelanggaran
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Daftar guru
$guru = mysqli_query(
    $koneksi,
    "SELECT id, nama
     FROM t_guru
     WHERE status_aktif = 1
     ORDER BY nama ASC"
);

// Data catatan pelanggaran
$sql = "SELECT
            ps.*,
            ta.nama AS tahun_ajaran,
            s.nama AS nama_siswa,
            k.nama AS nama_kelas,
            p.nama AS nama_pelanggaran,
            g.nama AS nama_guru
        FROM t_pelanggaran_siswa ps
        LEFT JOIN t_tahun_ajaran ta
            ON ps.tahun_ajaran_id = ta.id
        LEFT JOIN t_siswa s
            ON ps.siswa_id = s.id
        LEFT JOIN t_kelas k
            ON ps.kelas_id = k.id
        LEFT JOIN t_pelanggaran p
            ON ps.pelanggaran_id = p.id
        LEFT JOIN t_guru g
            ON ps.guru_id = g.id
        ORDER BY ps.id DESC";

$hasil = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catat Pelanggaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include 'layout/sidebar.php'; ?>
        <main class="menu-page-content">
            <h2 class="mb-4">Catat Pelanggaran Siswa</h2>

            <?php if (isset($_SESSION['pesan_sukses'])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION['pesan_sukses']) ?>
                    <?php unset($_SESSION['pesan_sukses']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['pesan_error'])): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($_SESSION['pesan_error']) ?>
                    <?php unset($_SESSION['pesan_error']); ?>
                </div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header">Form Catat Pelanggaran</div>
                <div class="card-body">
                    <form action="proses_simpan_pelanggaran.php" method="POST" id="formPelanggaran">
                        <div class="row">
                            <!-- Siswa -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Siswa</label>
                                <select name="siswa_id" id="siswa_id" class="form-select" required>
                                    <option value="">-- Pilih Siswa --</option>
                                    <?php while ($s = mysqli_fetch_assoc($siswa)): ?>
                                        <option value="<?= $s['id'] ?>">
                                            <?= htmlspecialchars($s['nis'] . ' - ' . $s['nama'] . ' - ' . ($s['nama_kelas'] ?? 'Belum ada kelas')) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Kelas Otomatis -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kelas</label>
                                <input type="text" id="nama_kelas" class="form-control" readonly placeholder="Pilih siswa terlebih dahulu">
                                <input type="hidden" name="kelas_id" id="kelas_id">
                            </div>

                            <!-- Tahun Ajaran -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Ajaran</label>
                                <select name="tahun_ajaran_id" class="form-select" required>
                                    <option value="">-- Pilih Tahun Ajaran --</option>
                                    <?php while ($ta = mysqli_fetch_assoc($tahun)): ?>
                                        <option value="<?= $ta['id'] ?>"><?= htmlspecialchars($ta['nama']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Jenis Pelanggaran -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis Pelanggaran</label>
                                <select name="pelanggaran_id" id="pelanggaran" class="form-select" required>
                                    <option value="">-- Pilih Pelanggaran --</option>
                                    <?php while ($p = mysqli_fetch_assoc($pelanggaran)): ?>
                                        <option value="<?= $p['id'] ?>"
                                                data-poin="<?= $p['poin'] ?>"
                                                data-kategori="<?= $p['pelanggaran_kategori_id'] ?>">
                                            <?= htmlspecialchars($p['nama'] . ' (' . $p['poin'] . ' poin)') ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <input type="hidden" name="pelanggaran_kategori_id" id="kategori">
                            </div>

                            <!-- Guru Pelapor -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Guru Pelapor</label>
                                <select name="guru_id" class="form-select" required>
                                    <option value="">-- Pilih Guru --</option>
                                    <?php while ($g = mysqli_fetch_assoc($guru)): ?>
                                        <option value="<?= $g['id'] ?>"><?= htmlspecialchars($g['nama']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <!-- Tanggal -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
                            </div>

                            <!-- Poin -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Poin Pelanggaran</label>
                                <input type="number" name="poin" id="poin" class="form-control" min="0" required readonly>
                            </div>

                            <!-- Status -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="Ditindaklanjuti">Ditindaklanjuti</option>
                                    <option value="Proses">Proses</option>
                                    <option value="Selesai">Selesai</option>
                                </select>
                            </div>

                            <!-- Keterangan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control" rows="3"></textarea>
                            </div>

                            <!-- Tindakan -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tindakan</label>
                                <textarea name="tindakan" class="form-control" rows="3"></textarea>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Pelanggaran</button>
                        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="card">
                <div class="card-header">Data Catatan Pelanggaran</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Pelanggaran</th>
                                    <th>Guru</th>
                                    <th>Tanggal</th>
                                    <th>Poin</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php while ($row = mysqli_fetch_assoc($hasil)): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= htmlspecialchars($row['nama_siswa'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['nama_kelas'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['tahun_ajaran'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['nama_pelanggaran'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['nama_guru'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['tanggal'] ?? '-') ?></td>
                                        <td><?= (int)($row['poin'] ?? 0) ?></td>
                                        <td><?= htmlspecialchars($row['tindakan'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($row['status'] ?? '-') ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// Auto-fill poin dan kategori dari pilihan pelanggaran
const pilihanPelanggaran = document.getElementById('pelanggaran');
const inputPoin = document.getElementById('poin');
const inputKategori = document.getElementById('kategori');

pilihanPelanggaran.addEventListener('change', function () {
    const opsi = this.options[this.selectedIndex];
    inputPoin.value = opsi.dataset.poin || '';
    inputKategori.value = opsi.dataset.kategori || '';
});

// Auto-fill kelas saat pilih siswa
$(document).ready(function(){
    $('#siswa_id').change(function(){
        var id_siswa = $(this).val();
        if(id_siswa != ''){
            $.ajax({
                url: 'get_siswa.php',
                method: 'POST',
                data: {id_siswa: id_siswa},
                dataType: 'json',
                success: function(res){
                    $('#nama_kelas').val(res.nama_kelas);
                    $('#kelas_id').val(res.id_kelas);
                },
                error: function(){
                    alert('Gagal mengambil data kelas');
                }
            });
        } else {
            $('#nama_kelas').val('');
            $('#kelas_id').val('');
        }
    });
});
</script>
</body>
</html>
