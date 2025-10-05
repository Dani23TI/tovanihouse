<?php
include 'koneksi.php';

$nama = $_POST['nama'];
$alamat = $_POST['alamat'];
$total = $_POST['total_kamar'];

mysqli_query($conn, "INSERT INTO kosan (nama,alamat,total_kamar) VALUES ('$nama','$alamat',$total)");
$id_kosan = mysqli_insert_id($conn);

// otomatis generate kamar
for($i=1;$i<=$total;$i++){
    mysqli_query($conn,"INSERT INTO kamar (id_kosan,nomor_kamar,harga,status) VALUES ($id_kosan,'Kamar $i',0,'kosong')");
}

header("Location: data-kos.php");
?>
