<?php include 'cek-login.php'; ?>
<?php
include 'koneksi.php';

// ambil daftar kosan untuk filter
$kosan_list = mysqli_query($conn, "SELECT * FROM kosan ORDER BY nama");

// cek filter kosan
$id_kosan = isset($_GET['id_kosan']) ? $_GET['id_kosan'] : '';

$sql = "
    SELECT penghuni.*, kamar.nomor_kamar, kosan.nama AS nama_kosan
    FROM penghuni
    JOIN kamar ON penghuni.id_kamar = kamar.id
    JOIN kosan ON kamar.id_kosan = kosan.id
";

// tambahin filter kosan kalau ada
if ($id_kosan) {
    $id_kosan = mysqli_real_escape_string($conn, $id_kosan);
    $sql .= " WHERE kosan.id = '$id_kosan'";
}

$query = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Penghuni</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f5f7fa;
    }
    .navbar {
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }
    .table thead {
      background: #0d6efd;
      color: white;
    }
    .table-hover tbody tr:hover {
      background-color: #eef3ff;
    }
    .btn-primary {
      background-color: #0d6efd;
      border: none;
    }
    .btn-primary:hover {
      background-color: #0b5ed7;
    }
    .ktp-thumb {
      cursor: pointer;
      border-radius: 6px;
      transition: 0.3s;
    }
    .ktp-thumb:hover {
      transform: scale(1.05);
      box-shadow: 0 0 8px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-primary"><i class="bi bi-people-fill me-2"></i>Data Penghuni</h3>
    <a href="tambah-penghuni.php" class="btn btn-success shadow-sm">
      <i class="bi bi-person-plus-fill me-1"></i> Tambah Penghuni
    </a>
  </div>

  <!-- Filter Kosan -->
  <form method="get" class="row g-3 mb-3">
    <div class="col-md-4">
      <select name="id_kosan" class="form-select shadow-sm" onchange="this.form.submit()">
        <option value="">-- Semua Kosan --</option>
        <?php while($k=mysqli_fetch_assoc($kosan_list)){ ?>
          <option value="<?= $k['id'] ?>" <?= ($id_kosan==$k['id'])?'selected':'' ?>>
            <?= htmlspecialchars($k['nama']) ?>
          </option>
        <?php } ?>
      </select>
    </div>
    <?php if($id_kosan){ ?>
    <div class="col-md-2">
      <a href="penghuni.php" class="btn btn-secondary shadow-sm"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</a>
    </div>
    <?php } ?>
  </form>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>No HP</th>
            <th>No HP Darurat</th>
            <th>Kosan</th>
            <th>Kamar</th>
            <th>Tgl Masuk</th>
            <th>Tgl Keluar</th>
            <th>Foto KTP</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; while($d=mysqli_fetch_assoc($query)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($d['nama']) ?></td>
            <td><?= htmlspecialchars($d['nohp']) ?></td>
            <td><?= htmlspecialchars($d['nohp_darurat']) ?></td>
            <td><?= htmlspecialchars($d['nama_kosan']) ?></td>
            <td><?= htmlspecialchars($d['nomor_kamar']) ?></td>
            <td><?= htmlspecialchars($d['tgl_masuk']) ?></td>
            <td><?= htmlspecialchars($d['tgl_keluar']) ?></td>
            <td>
              <?php if($d['foto_ktp']): ?>
                <img src="uploads/ktp/<?= $d['foto_ktp'] ?>" width="60" class="ktp-thumb"
                     data-bs-toggle="modal" data-bs-target="#ktpModal" 
                     onclick="showKtp('uploads/ktp/<?= $d['foto_ktp'] ?>')">
              <?php else: ?>
                <span class="text-muted">-</span>
              <?php endif; ?>
            </td>
            <td>
              <a href="edit-penghuni.php?id=<?= $d['id'] ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil-square"></i>
              </a>
              <a href="hapus-penghuni.php?id=<?= $d['id'] ?>"
                 onclick="return confirm('Yakin hapus penghuni ini?')"
                 class="btn btn-danger btn-sm">
                 <i class="bi bi-trash-fill"></i>
              </a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal untuk preview KTP -->
<div class="modal fade" id="ktpModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-body text-center p-0">
        <img id="ktpPreview" src="" class="img-fluid rounded">
      </div>
    </div>
  </div>
</div>

<script>
function showKtp(src) {
  document.getElementById('ktpPreview').src = src;
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
