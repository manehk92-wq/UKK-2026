<?php
include 'includes/cek_session.php';
include 'config/koneksi.php';

$id = $_GET['id'] ?? 0;

$stmt = mysqli_prepare($koneksi, "DELETE FROM t_pelanggaran WHERE id = ?");
mysqli_stmt_bind_param($stmt, 'i', $id);
mysqli_stmt_execute($stmt);

header("Location: kelola_jenis_pelanggaran.php");
exit;
