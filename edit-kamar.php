<?php include 'cek-login.php'; ?>
<?php
include 'koneksi.php';

$id = $_GET['id'];
$kamar = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM kamar WHERE id='$id'"));
$kosan = mysqli_query($conn,"SELECT * FROM kosan");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Kamar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container my-4">

<?php include 'navbar.php'; ?>

<h2 class="mb-4">Edit Kamar</h2>
<form action="proses-edit-kamar.php" method="POST" class="row g-3">
  <input type="hidden" name="id" value="<?= $kamar['id'] ?>">

  <div class="col-md-6">
    <label class="form-label">Kosan</label>
    <select name="id_kosan" class="form-select" required>
      <?php while($row=mysqli_fetch_assoc($kosan)){ ?>
        <option value="<?= $row['id'] ?>" <?= $row['id']==$kamar['id_kosan']?'selected':'' ?>>
          <?= $row['nama'] ?>
        </option>
      <?php } ?>
    </select>
  </div>
  <div class="col-md-6">
    <label class="form-label">Nomor Kamar</label>
    <input type="text" name="nomor_kamar" class="form-control" value="<?= $kamar['nomor_kamar'] ?>" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Harga</label>
    <input type="number" name="harga" class="form-control" value="<?= $kamar['harga'] ?>" required>
  </div>
  <div class="col-md-6">
    <label class="form-label">Status</label>
    <select name="status" class="form-select" required>
      <option value="kosong" <?= $kamar['status']=='kosong'?'selected':'' ?>>Kosong</option>
      <option value="terisi" <?= $kamar['status']=='terisi'?'selected':'' ?>>Terisi</option>
    </select>
  </div>
  <div class="col-12">
    <button type="submit" class="btn btn-success">Update</button>
    <a href="kamar.php" class="btn btn-secondary">Batal</a>
  </div>
</form>
</body>
</html>
