<?php
include 'koneksi.php';

$id = $_POST['id'];
$id_kamar_lama = $_POST['id_kamar_lama'];
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

// query update
$sql = "UPDATE penghuni SET 
            id_kamar='$id_kamar',
            nama='$nama',
            nohp='$nohp',
            nohp_darurat='$nohp_darurat',
            kamar=(SELECT nomor_kamar FROM kamar WHERE id='$id_kamar'),
            tgl_masuk='$tgl_masuk',
            tgl_keluar='$tgl_keluar'";

if($foto_ktp != ''){
    $sql .= ", foto_ktp='$foto_ktp'";
}

$sql .= " WHERE id='$id'";
mysqli_query($conn, $sql);

// update status kamar lama -> kosong (kalau ganti kamar)
if($id_kamar != $id_kamar_lama){
    mysqli_query($conn,"UPDATE kamar SET status='kosong' WHERE id='$id_kamar_lama'");
    mysqli_query($conn,"UPDATE kamar SET status='terisi' WHERE id='$id_kamar'");
}

header("Location: penghuni.php");
?>
