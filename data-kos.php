<?php include 'cek-login.php'; ?>
<?php
include 'koneksi.php';

$query = mysqli_query($conn,"
    SELECT kosan.*, COUNT(kamar.id) AS jumlah_kamar,
           SUM(CASE WHEN kamar.status='terisi' THEN 1 ELSE 0 END) AS terisi,
           SUM(CASE WHEN kamar.status='kosong' THEN 1 ELSE 0 END) AS kosong
    FROM kosan
    LEFT JOIN kamar ON kosan.id = kamar.id_kosan
    GROUP BY kosan.id
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Kosan</title>
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
      border: none;
    }
    .table thead {
      background: #0d6efd;
      color: white;
    }
    .table-hover tbody tr:hover {
      background-color: #eef3ff;
    }
    .btn-success {
        box-shadow: 0 2px 6px rgba(40,167,69,0.4);
    }
  </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container my-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold text-primary"><i class="bi bi-building me-2"></i>Data Kosan</h3>
    <a href="tambah-kosan.php" class="btn btn-success shadow-sm">
      <i class="bi bi-plus-lg me-1"></i> Tambah Kosan
    </a>
  </div>

  <div class="card p-3">
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Kosan</th>
            <th>Alamat</th>
            <th>Total Kamar</th>
            <th>Kamar Terisi</th>
            <th>Kamar Kosong</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=1; while($row=mysqli_fetch_assoc($query)): ?>
          <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama']) ?></td>
            <td><?= htmlspecialchars($row['alamat']) ?></td>
            <td><?= $row['jumlah_kamar'] ?></td>
            <td><?= $row['terisi'] ?? 0 ?></td>
            <td><?= $row['kosong'] ?? 0 ?></td>
            <td>
                <a href="hapus-kosan.php?id=<?= $row['id'] ?>" 
                   class="btn btn-danger btn-sm" 
                   onclick="return confirm('PERINGATAN! Menghapus kosan ini akan menghapus SEMUA data kamar, penghuni, dan riwayat transaksi yang terkait. Data tidak bisa dikembalikan. Apakah Anda benar-benar yakin?')">
                   <i class="bi bi-trash-fill"></i> Hapus
                </a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>