<?php
include 'cek-login.php';
include 'koneksi.php';

// Pastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil data yang relevan dari form
    $id             = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $jumlah         = isset($_POST['jumlah']) ? intval($_POST['jumlah']) : 0;
    $jatuh_tempo    = isset($_POST['jatuh_tempo']) ? trim($_POST['jatuh_tempo']) : '';
    $metode         = isset($_POST['metode']) ? trim($_POST['metode']) : '';
    $status         = isset($_POST['status']) ? trim($_POST['status']) : '';

    // Pastikan ID transaksi valid dan data lainnya tidak kosong
    if ($id > 0 && !empty($jatuh_tempo) && !empty($metode) && !empty($status)) {
        
        // Siapkan query UPDATE yang lebih ringkas
        $stmt = $conn->prepare("
            UPDATE transaksi 
            SET 
                jumlah = ?, 
                jatuh_tempo = ?, 
                metode = ?, 
                status = ?
            WHERE 
                id = ?
        ");
        
        // Bind 5 parameter (i untuk integer, s untuk string)
        $stmt->bind_param("isssi", $jumlah, $jatuh_tempo, $metode, $status, $id);

        // Eksekusi query
        if ($stmt->execute()) {
            // Jika berhasil, arahkan kembali ke halaman daftar transaksi
            header("Location: transaksi.php?status=sukses_update");
        } else {
            // Jika gagal, bisa ditambahkan notifikasi error
            header("Location: transaksi.php?status=gagal_update");
        }
        
        $stmt->close();
    } else {
        // Jika ID atau data lain tidak valid, kembalikan
        header("Location: transaksi.php?status=gagal_data_kosong");
    }
    
    $conn->close();
    
} else {
    // Jika halaman diakses langsung tanpa POST, kembalikan
    header("Location: transaksi.php");
}

exit;
?>