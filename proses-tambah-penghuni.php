<?php
include 'koneksi.php';

$nama = $_POST['nama'];
$nohp = $_POST['nohp'];
$nohp_darurat = $_POST['nohp_darurat'];
$id_kamar = $_POST['id_kamar'];
$tgl_masuk = $_POST['tgl_masuk'];
$tgl_keluar = $_POST['tgl_keluar'];

// handle upload KTP
$foto_ktp = '';
if(isset($_FILES['foto_ktp']) && $_FILES['foto_ktp']['error'] == 0){
    $ext = pathinfo($_FILES['foto_ktp']['name'], PATHINFO_EXTENSION);
    $new_name = time() . '_' . rand(100,999) . '.' . $ext;
    $target = "uploads/ktp/" . $new_name;

    if(!is_dir("uploads/ktp")) mkdir("uploads/ktp", 0777, true);

    if(move_uploaded_file($_FILES['foto_ktp']['tmp_name'], $target)){
        $foto_ktp = $new_name;
    }
}

// insert ke tabel penghuni
mysqli_query($conn,"INSERT INTO penghuni 
    (id_kamar, nama, nohp, nohp_darurat, kamar, tgl_masuk, tgl_keluar, foto_ktp)
    VALUES 
    ('$id_kamar','$nama','$nohp','$nohp_darurat',
     (SELECT nomor_kamar FROM kamar WHERE id='$id_kamar'),
     '$tgl_masuk','$tgl_keluar','$foto_ktp')");

// update status kamar jadi 'terisi'
mysqli_query($conn,"UPDATE kamar SET status='terisi' WHERE id='$id_kamar'");

header("Location: penghuni.php");
?>
