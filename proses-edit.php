<?php
include 'koneksi.php';

$id = $_POST['id'];
$nomor = $_POST['nomor_kamar'];
$nama = $_POST['nama_penghuni'];
$tgl_masuk = $_POST['tgl_masuk'];
$tempo = $_POST['tgl_jatuh_tempo'];
$harga = $_POST['harga_per_bulan'];
$kosan = $_POST['nama_kosan'];

mysqli_query($conn, "UPDATE kosan SET nomor_kamar='$nomor', nama_penghuni='$nama', tgl_masuk='$tgl_masuk', tgl_jatuh_tempo='$tempo', harga_per_bulan='$harga', nama_kosan='$kosan' WHERE id=$id");

header("Location: data-kos.php");
?>
