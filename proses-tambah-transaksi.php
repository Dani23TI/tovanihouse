<?php
include 'koneksi.php';

$id_penghuni   = $_POST['id_penghuni'];
$nama_penghuni = $_POST['nama_penghuni'];
$nama_kosan    = $_POST['nama_kosan'];
$jumlah        = $_POST['jumlah'];
$metode        = $_POST['metode'];
$jatuh_tempo   = $_POST['jatuh_tempo'];
$status        = $_POST['status'];

// kalau status langsung lunas, set jatuh_tempo ke +1 bulan
if($status == 'lunas'){
    $jatuh_tempo = date('Y-m-d', strtotime("$jatuh_tempo +1 month"));
}

mysqli_query($conn,"
  INSERT INTO transaksi 
  (id_penghuni, nama_penghuni, nama_kosan, jumlah, metode, jatuh_tempo, status) 
  VALUES ('$id_penghuni','$nama_penghuni','$nama_kosan','$jumlah','$metode','$jatuh_tempo','$status')
");

header("Location: transaksi.php");
