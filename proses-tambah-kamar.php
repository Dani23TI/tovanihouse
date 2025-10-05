<?php
include 'koneksi.php';

$id_kosan    = $_POST['id_kosan'];
$nomor_kamar = $_POST['nomor_kamar'];
$harga       = $_POST['harga'];
$status      = $_POST['status'];

mysqli_query($conn,"
    INSERT INTO kamar (id_kosan, nomor_kamar, harga, status) 
    VALUES ('$id_kosan','$nomor_kamar','$harga','$status')
");

header("Location: kamar.php");
