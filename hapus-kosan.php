<?php
include 'cek-login.php';
include 'koneksi.php';

// Validasi ID yang dikirim dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: data-kos.php?status=gagal_invalid_id');
    exit;
}

$id_kosan = intval($_GET['id']);

// Menggunakan transaksi database untuk memastikan semua proses berhasil atau tidak sama sekali
$conn->begin_transaction();

try {
    // 1. Hapus semua transaksi yang penghuninya ada di kosan ini
    $stmt1 = $conn->prepare("
        DELETE FROM transaksi 
        WHERE id_penghuni IN (
            SELECT id FROM penghuni WHERE id_kamar IN (
                SELECT id FROM kamar WHERE id_kosan = ?
            )
        )
    ");
    $stmt1->bind_param("i", $id_kosan);
    $stmt1->execute();
    $stmt1->close();

    // 2. Hapus semua penghuni yang kamarnya ada di kosan ini
    $stmt2 = $conn->prepare("DELETE FROM penghuni WHERE id_kamar IN (SELECT id FROM kamar WHERE id_kosan = ?)");
    $stmt2->bind_param("i", $id_kosan);
    $stmt2->execute();
    $stmt2->close();

    // 3. Hapus semua kamar yang ada di kosan ini (INI YANG DIPERBAIKI)
    // Menghapus karakter ')' yang salah tempat
    $stmt3 = $conn->prepare("DELETE FROM kamar WHERE id_kosan = ?");
    $stmt3->bind_param("i", $id_kosan);
    $stmt3->execute();
    $stmt3->close();

    // 4. Setelah semua data terkait bersih, hapus kosan itu sendiri
    $stmt4 = $conn->prepare("DELETE FROM kosan WHERE id = ?");
    $stmt4->bind_param("i", $id_kosan);
    $stmt4->execute();
    $stmt4->close();

    // Jika semua query berhasil, simpan perubahan secara permanen
    $conn->commit();
    
    // Arahkan kembali dengan status sukses
    header('Location: data-kos.php?status=sukses_hapus');

} catch (mysqli_sql_exception $exception) {
    // Jika ada satu saja query yang gagal, batalkan semua perubahan
    $conn->rollback();
    
    // Arahkan kembali dengan status gagal dan pesan error untuk debug
    header('Location: data-kos.php?status=gagal_hapus&error=' . urlencode($exception->getMessage()));
}

$conn->close();
exit;
?>