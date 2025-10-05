<?php
include 'koneksi.php';

$id          = $_POST['id'];
$id_kosan    = $_POST['id_kosan'];
$nomor_kamar = $_POST['nomor_kamar'];
$harga       = $_POST['harga'];
$status      = $_POST['status'];

mysqli_query($conn,"
    UPDATE kamar 
    SET id_kosan='$id_kosan', nomor_kamar='$nomor_kamar', harga='$harga', status='$status' 
    WHERE id='$id'
");

header("Location: kamar.php");
