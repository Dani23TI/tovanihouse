<?php
include 'koneksi.php';
$q1 = mysqli_query($conn,"SELECT SUM(jumlah) as total FROM transaksi");
$pemasukan = mysqli_fetch_assoc($q1)['total'] ?? 0;

$q2 = mysqli_query($conn,"SELECT SUM(jumlah) as piutang FROM transaksi WHERE metode='Belum Lunas'");
$piutang = mysqli_fetch_assoc($q2)['piutang'] ?? 0;

$q3 = mysqli_query($conn,"SELECT SUM(jumlah) as keluar FROM transaksi WHERE jumlah < 0");
$pengeluaran = abs(mysqli_fetch_assoc($q3)['keluar'] ?? 0);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Statistik</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<?php include 'navbar.php'; ?>
<div class="container my-4">
  <h3 class="mb-4">📊 Statistik</h3>
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <h6 class="text-muted">Total Pemasukan</h6>
          <h3 class="text-success">Rp <?= number_format($pemasukan,0,',','.') ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <h6 class="text-muted">Piutang</h6>
          <h3 class="text-warning">Rp <?= number_format($piutang,0,',','.') ?></h3>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm text-center">
        <div class="card-body">
          <h6 class="text-muted">Pengeluaran</h6>
          <h3 class="text-danger">Rp <?= number_format($pengeluaran,0,',','.') ?></h3>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
