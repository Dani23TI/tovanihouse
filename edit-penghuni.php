<?php include 'cek-login.php'; ?>
<?php
include 'koneksi.php';

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT penghuni.*, kamar.nomor_kamar, kosan.nama as nama_kosan
    FROM penghuni
    JOIN kamar ON penghuni.id_kamar = kamar.id
    JOIN kosan ON kamar.id_kosan = kosan.id
    WHERE penghuni.id='$id'
"));

// ambil list kamar (biar bisa pindah kamar juga)
$kamar = mysqli_query($conn,"
    SELECT kamar.id, kamar.nomor_kamar, kosan.nama as nama_kosan, kamar.status
    FROM kamar
    JOIN kosan ON kamar.id_kosan=kosan.id
");
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Penghuni</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="container my-4">
  <h3>Edit Penghuni</h3>
  <form method="post" action="proses-edit-penghuni.php" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $data['id'] ?>">
    <input type="hidden" name="id_kamar_lama" value="<?= $data['id_kamar'] ?>">

    <div class="mb-3">
      <label>Nama</label>
      <input type="text" name="nama" class="form-control" value="<?= $data['nama'] ?>" required>
    </div>
    <div class="mb-3">
      <label>No HP</label>
      <input type="text" name="nohp" class="form-control" value="<?= $data['nohp'] ?>" required>
    </div>
    <div class="mb-3">
      <label>No HP Darurat</label>
      <input type="text" name="nohp_darurat" class="form-control" value="<?= $data['nohp_darurat'] ?>">
    </div>
    <div class="mb-3">
      <label>Pilih Kamar</label>
      <select name="id_kamar" class="form-control" required>
        <?php while($d=mysqli_fetch_assoc($kamar)): ?>
          <option value="<?= $d['id'] ?>" 
            <?= ($data['id_kamar']==$d['id'])?'selected':'' ?>>
            <?= $d['nama_kosan']." - ".$d['nomor_kamar']." (".$d['status'].")" ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3">
      <label>Foto KTP</label><br>
      <?php if($data['foto_ktp']): ?>
        <img src="uploads/ktp/<?= $data['foto_ktp'] ?>" alt="KTP" width="120"><br>
        <small class="text-muted">Kosongkan jika tidak ingin ganti</small>
      <?php endif; ?>
      <input type="file" name="foto_ktp" class="form-control mt-2" accept="image/*">
    </div>
    <div class="mb-3">
      <label>Tanggal Masuk</label>
      <input type="date" name="tgl_masuk" class="form-control" value="<?= $data['tgl_masuk'] ?>" required>
    </div>
    <div class="mb-3">
      <label>Tanggal Keluar</label>
      <input type="date" name="tgl_keluar" class="form-control" value="<?= $data['tgl_keluar'] ?>">
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="penghuni.php" class="btn btn-secondary">Batal</a>
  </form>
</body>
</html>
