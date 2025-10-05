<?php
include 'koneksi.php';

$id = $_GET['id'];

// cari kamar yang ditempati
$data = mysqli_fetch_assoc(mysqli_query($conn,"SELECT id_kamar FROM penghuni WHERE id='$id'"));
$id_kamar = $data['id_kamar'];

// hapus penghuni
mysqli_query($conn,"DELETE FROM penghuni WHERE id='$id'");

// update kamar jadi kosong
mysqli_query($conn,"UPDATE kamar SET status='kosong' WHERE id='$id_kamar'");

header("Location: penghuni.php");
?>
