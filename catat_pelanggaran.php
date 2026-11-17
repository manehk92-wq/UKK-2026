<div class="card mb-4">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Catat Pelanggaran Siswa</h5>
    </div>
    <div class="card-body">
        <form action="simpan_pelanggaran.php" method="POST">
            <!-- Input Pencarian Siswa -->
            <div class="mb-3">
                <label class="form-label">Cari Siswa (NIS / Nama / Kelas)</label>
                <input type="text" id="cari_siswa" class="form-control" placeholder="Ketik NIS, Nama atau Kelas...">
            </div>

            <!-- Daftar Siswa -->
            <div class="mb-3">
                <label class="form-label">Pilih Siswa</label>
                <div id="daftar_siswa" style="max-height: 250px; overflow-y: auto; border: 1px solid #ced4da; border-radius: 0.375rem; padding: 10px;">
                    <?php
                    include '../config/koneksi.php';
                    $query = mysqli_query($koneksi, "SELECT s.*, k.nama_kelas 
                                                     FROM t_siswa s 
                                                     LEFT JOIN t_kelas k ON s.id_kelas = k.id_kelas 
                                                     ORDER BY s.nama_siswa ASC");
                    while ($siswa = mysqli_fetch_assoc($query)) {
                    ?>
                    <div class="form-check mb-2 siswa-item" 
                         data-nis="<?= $siswa['nis'] ?>" 
                         data-nama="<?= strtolower($siswa['nama_siswa']) ?>" 
                         data-kelas="<?= strtolower($siswa['nama_kelas']) ?>">
                        <input class="form-check-input" type="radio" name="id_siswa" value="<?= $siswa['id_siswa'] ?>" id="siswa_<?= $siswa['id_siswa'] ?>" required>
                        <label class="form-check-label w-100" for="siswa_<?= $siswa['id_siswa'] ?>">
                            <strong><?= $siswa['nis'] ?></strong> - <?= $siswa['nama_siswa'] ?> 
                            <span class="badge bg-secondary float-end"><?= $siswa['nama_kelas'] ?></span>
                        </label>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Pelanggaran</label>
                <select name="id_jenis" class="form-select" required>
                    <option value="">-- Pilih Jenis Pelanggaran --</option>
                    <?php
                    $q = mysqli_query($koneksi, "SELECT * FROM t_jenis_pelanggaran ORDER BY nama_jenis ASC");
                    while ($j = mysqli_fetch_assoc($q)) {
                        echo "<option value='$j[id_jenis]'>$j[nama_jenis] - Poin: $j[poin]</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3" placeholder="Masukkan keterangan pelanggaran..."></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan Pelanggaran</button>
            <a href="pelanggaran.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
