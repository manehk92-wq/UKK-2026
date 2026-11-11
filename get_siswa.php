<?php
// get_siswa.php
include 'config/koneksi.php';

if (isset($_POST['id_siswa'])) {
    $id_siswa = $_POST['id_siswa'];
    $query = mysqli_query($koneksi, "
        SELECT k.nama AS nama_kelas, k.id AS id_kelas
        FROM t_kelas k
        JOIN t_kelas_siswa ks ON ks.kelas_id = k.id
        WHERE ks.siswa_id = '$id_siswa'
        LIMIT 1
    ");
    $data = mysqli_fetch_assoc($query);
    echo json_encode($data);
}
?>
