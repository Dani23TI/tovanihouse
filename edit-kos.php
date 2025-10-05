<?php include 'cek-login.php'; ?>
<?php include 'koneksi.php';
$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM kosan WHERE id=$id"));
?>
<?php
session_start();
if (!isset($_SESSION['login'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Data Kos</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
  <div class="sidebar">
    <h2>Kos Dashboard</h2>
  </div>
  <div class="content">
    <h1>Edit Data</h1>
    <form method="POST" action="proses-edit.php">
      <input type="hidden" name="id" value="<?= $data['id'] ?>">
      <input type="text" name="nomor_kamar" value="<?= $data['nomor_kamar'] ?>" required>
      <input type="text" name="nama_penghuni" value="<?= $data['nama_penghuni'] ?>">
      <input type="date" name="tgl_masuk" value="<?= $data['tgl_masuk'] ?>">
      <input type="date" name="tgl_jatuh_tempo" value="<?= $data['tgl_jatuh_tempo'] ?>">
      <input type="number" name="harga_per_bulan" value="<?= $data['harga_per_bulan'] ?>">
      <input type="text" name="nama_kosan" value="<?= $data['nama_kosan'] ?>">
      <button type="submit">Update</button>
    </form>
  </div>
</body>
</html>
