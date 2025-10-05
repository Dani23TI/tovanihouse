<?php include 'cek-login.php'; ?>
<?php
include 'koneksi.php';

// ambil bulan & tahun dari form (kalau ada), default bulan & tahun sekarang
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$kosan = isset($_GET['kosan']) ? $_GET['kosan'] : '';
$kosan_list = mysqli_query($conn, "SELECT nama FROM kosan ORDER BY nama ASC");

$where_kosan = '';
if ($kosan !== '') {
  $kosan_safe = mysqli_real_escape_string($conn, $kosan);
  $where_kosan = "AND nama_kosan = '$kosan_safe'";
}

// Total pemasukan
$total_pemasukan = mysqli_fetch_assoc(
  mysqli_query($conn,"
      SELECT COALESCE(SUM(jumlah),0) as total
      FROM transaksi
      WHERE status='lunas'
        AND MONTH(jatuh_tempo)='$bulan'
        AND YEAR(jatuh_tempo)='$tahun'
        $where_kosan
  ")
)['total'];

$lunas = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) as jml
        FROM transaksi
        WHERE status='lunas'
        AND MONTH(jatuh_tempo)='$bulan'
        AND YEAR(jatuh_tempo)='$tahun'
        $where_kosan
    ")
)['jml'];

$belum_lunas = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT COUNT(*) as jml
        FROM transaksi
        WHERE status='belum lunas'
        AND MONTH(jatuh_tempo)='$bulan'
        AND YEAR(jatuh_tempo)='$tahun'
        $where_kosan
    ")
)['jml'];

$transaksi = mysqli_query($conn,"
    SELECT * FROM transaksi
    WHERE MONTH(jatuh_tempo)='$bulan'
      AND YEAR(jatuh_tempo)='$tahun'
      $where_kosan
    ORDER BY id DESC LIMIT 5
");

$chart = mysqli_query($conn,"
    SELECT DATE_FORMAT(jatuh_tempo, '%Y-%m') as bulan,
           COALESCE(SUM(jumlah),0) as total
    FROM transaksi
    WHERE status='lunas'
      $where_kosan
    GROUP BY DATE_FORMAT(jatuh_tempo, '%Y-%m')
    ORDER BY bulan ASC
");
$labels=[]; $data=[];
while($row=mysqli_fetch_assoc($chart)){
    $labels[] = $row['bulan'];
    $data[] = $row['total'];
}

$total_kos = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as jml FROM kosan"))['jml'];
$total_kamar = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as jml FROM kamar"))['jml'];
$kamar_kosong = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as jml FROM kamar WHERE status='kosong'"))['jml'];
$kamar_terisi = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as jml FROM kamar WHERE status='terisi'"))['jml'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Kos</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background: #f5f6fa;
      font-family: 'Inter', sans-serif;
      color: #333;
    }
    .navbar {
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    /* DIUBAH DI SINI */
    h2, h4 {
      font-weight: 700;
      color: #0d6efd; /* Warna diubah menjadi biru primer */
    }

    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0 3px 10px rgba(0,0,0,0.08);
      transition: transform .2s ease, box-shadow .2s ease;
    }
    .card:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

    .form-select, .btn {
      border-radius: 10px;
    }

    .summary-card h5 {
      font-size: 1rem;
      color: #fff;
      font-weight: 500;
    }

    .summary-card h3 {
      font-weight: 700;
      margin-top: 10px;
    }

    table thead {
      background: #1e293b;
      color: #fff;
    }

    .table {
      border-radius: 12px;
      overflow: hidden;
    }

    .badge {
      font-size: 0.9em;
      padding: 8px 12px;
      border-radius: 10px;
    }

    .filter-box {
      background: #fff;
      padding: 15px;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }

    footer {
      margin-top: 60px;
      text-align: center;
      color: #888;
      font-size: 0.9em;
    }

    /* Warna lembut ala modern dashboard */
    .bg-gradient-success { background: linear-gradient(135deg, #10b981, #34d399); }
    .bg-gradient-primary { background: linear-gradient(135deg, #3b82f6, #60a5fa); }
    .bg-gradient-warning { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
    .bg-gradient-info { background: linear-gradient(135deg, #06b6d4, #22d3ee); }
    .bg-gradient-danger { background: linear-gradient(135deg, #ef4444, #f87171); }
    .bg-gradient-secondary { background: linear-gradient(135deg, #6b7280, #9ca3af); }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h2><i class="bi bi-pie-chart-fill me-2"></i>Dashboard Kos</h2>
    </div>

    <form method="get" class="filter-box row g-3 mb-4">
      <div class="col-md-3">
        <select name="kosan" class="form-select">
          <option value="">-- Semua Kosan --</option>
          <?php while($k = mysqli_fetch_assoc($kosan_list)){
            $selected = ($kosan == $k['nama']) ? 'selected' : '';
            echo "<option value='{$k['nama']}' $selected>{$k['nama']}</option>";
          } ?>
        </select>
      </div>
      <div class="col-md-3">
        <select name="bulan" class="form-select">
          <?php for($i=1;$i<=12;$i++){
            $val = str_pad($i,2,'0',STR_PAD_LEFT);
            $selected = ($bulan==$val)?'selected':'';
            echo "<option value='$val' $selected>".date('F', mktime(0,0,0,$i,10))."</option>";
          } ?>
        </select>
      </div>
      <div class="col-md-3">
        <select name="tahun" class="form-select">
          <?php
          $tahun_sekarang = date('Y');
          for($i=$tahun_sekarang-5;$i<=$tahun_sekarang+1;$i++){
            $selected = ($tahun==$i)?'selected':'';
            echo "<option value='$i' $selected>$i</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
      </div>
    </form>

    <div class="row mb-4">
      <div class="col-md-4">
        <div class="card bg-gradient-success text-white summary-card p-3">
          <h5>Total Pemasukan</h5>
          <h3>Rp <?= number_format($total_pemasukan,0,',','.') ?></h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-gradient-primary text-white summary-card p-3">
          <h5>Transaksi Lunas</h5>
          <h3><?= $lunas ?> Transaksi</h3>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card bg-gradient-warning text-white summary-card p-3">
          <h5>Belum Lunas</h5>
          <h3><?= $belum_lunas ?> Transaksi</h3>
        </div>
      </div>
    </div>

    <div class="row mb-4">
      <div class="col-md-3">
        <div class="card bg-gradient-info text-white summary-card p-3">
          <h5>Total Kos</h5>
          <h3><?= $total_kos ?></h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-gradient-secondary text-white summary-card p-3">
          <h5>Total Kamar</h5>
          <h3><?= $total_kamar ?></h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-gradient-success text-white summary-card p-3">
          <h5>Kamar Terisi</h5>
          <h3><?= $kamar_terisi ?></h3>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card bg-gradient-danger text-white summary-card p-3">
          <h5>Kamar Kosong</h5>
          <h3><?= $kamar_kosong ?></h3>
        </div>
      </div>
    </div>

    <div class="card p-4 mb-5">
      <h4 class="mb-3"><i class="bi bi-graph-up-arrow me-2"></i>Pemasukan Bulanan</h4>
      <canvas id="chartPemasukan" height="90"></canvas>
    </div>

    <div class="card p-4 mb-5">
      <h4 class="mb-3"><i class="bi bi-receipt-cutoff me-2"></i>Transaksi Terbaru</h4>
      <div class="table-responsive">
        <table class="table align-middle">
          <thead>
            <tr>
              <th>Penghuni</th>
              <th>Kosan</th>
              <th>Jumlah</th>
              <th>Metode</th>
              <th>Jatuh Tempo</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php while($row=mysqli_fetch_assoc($transaksi)){ ?>
            <tr>
              <td><?= htmlspecialchars($row['nama_penghuni']) ?></td>
              <td><?= htmlspecialchars($row['nama_kosan']) ?></td>
              <td><strong>Rp <?= number_format($row['jumlah'],0,',','.') ?></strong></td>
              <td><?= htmlspecialchars($row['metode']) ?></td>
              <td><?= htmlspecialchars($row['jatuh_tempo']) ?></td>
              <td>
                <?php if($row['status']=='lunas'){ ?>
                  <span class="badge bg-success">Lunas</span>
                <?php } else { ?>
                  <span class="badge bg-warning text-dark">Belum Lunas</span>
                <?php } ?>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>

    <footer>
      &copy; <?= date('Y') ?> Dashboard Kos — Dibuat dengan ❤️ dan Bootstrap 5
    </footer>
  </div>

  <script>
    const ctx = document.getElementById('chartPemasukan').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode($labels) ?>,
            datasets: [{
                label: 'Pemasukan',
                data: <?= json_encode($data) ?>,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59,130,246,0.2)',
                fill: true,
                tension: 0.3,
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false }},
            scales: { y: { beginAtZero: true } }
        }
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>