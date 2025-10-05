<?php
include 'koneksi.php';

$id = $_GET['id'];

// ambil data transaksi
$q = mysqli_query($conn,"SELECT * FROM transaksi WHERE id='$id'");
$data = mysqli_fetch_assoc($q);

if($data){
    // kalau status lunas, baru bisa diperpanjang
    if($data['status'] == 'lunas'){
        $jatuh_tempo_baru = date('Y-m-d', strtotime($data['jatuh_tempo'].' +1 month'));
        mysqli_query($conn,"
            UPDATE transaksi 
            SET status='belum lunas', jatuh_tempo='$jatuh_tempo_baru' 
            WHERE id='$id'
        ");
    }
}

header("Location: transaksi.php");
