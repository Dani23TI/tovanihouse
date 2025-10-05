<?php include 'cek-login.php'; ?>
<?php
include 'koneksi.php';

// Ambil daftar kosan untuk dropdown filter
$kosan_list_query = mysqli_query($conn, "SELECT id, nama FROM kosan ORDER BY nama");

// Ambil filter id_kosan dari URL
$id_kosan = isset($_GET['id_kosan']) ? $_GET['id_kosan'] : '';

// --- Query Utama (Lebih Efisien & Aman dengan Prepared Statements) ---
$sql = "
    SELECT kamar.*, kosan.nama AS nama_kosan
    FROM kamar
    JOIN kosan ON kamar.id_kosan = kosan.id
";

// Tambahkan kondisi WHERE jika filter kosan dipilih
if (!empty($id_kosan)) {
    $sql .= " WHERE kamar.id_kosan = ?";
}

$sql .= " ORDER BY kosan.nama, kamar.nomor_kamar";

// Siapkan dan eksekusi query
$stmt = $conn->prepare($sql);

if (!empty($id_kosan)) {
    // Bind parameter jika ada filter
    $stmt->bind_param("i", $id_kosan);
}

$stmt->execute();
$query_result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Kamar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    /* Menggunakan style yang konsisten dari halaman penghuni.php */
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
  </style>
</head>
<body>

  <?php include 'navbar.php'; ?>

  <div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h3 class="fw-bold text-primary">
        <i class="bi bi-door-closed-fill me-2"></i>Data Kamar
      </h3>
      <a href="tambah-kamar.php" class="btn btn-success shadow-sm">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kamar
      </a>
    </div>

    <form method="get" class="row g-3 mb-3">
      <div class="col-md-4">
        <select name="id_kosan" class="form-select shadow-sm" onchange="this.form.submit()">
          <option value="">-- Semua Kosan --</option>
          <?php while($k = mysqli_fetch_assoc($kosan_list_query)){ ?>
            <option value="<?= $k['id'] ?>" <?= ($id_kosan == $k['id']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($k['nama']) ?>
            </option>
          <?php } ?>
        </select>
      </div>
      <?php if($id_kosan){ ?>
      <div class="col-md-2">
        <a href="kamar.php" class="btn btn-secondary shadow-sm"><i class="bi bi-arrow-counterclockwise me-1"></i>Reset</a>
      </div>
      <?php } ?>
    </form>

    <div class="card p-3">
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr>
              <th>No</th>
              <th>Kosan</th>
              <th>Nomor Kamar</th>
              <th>Status</th>
              <th>Harga per Bulan</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; while($row = $query_result->fetch_assoc()){ ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['nama_kosan']) ?></td>
              <td><strong>Kamar <?= htmlspecialchars($row['nomor_kamar']) ?></strong></td>
              <td>
                <?php if($row['status'] == 'terisi'){ ?>
                  <span class="badge bg-danger rounded-pill px-3 py-2">Terisi</span>
                <?php } else { ?>
                  <span class="badge bg-success rounded-pill px-3 py-2">Kosong</span>
                <?php } ?>
              </td>
              <td class="fw-semibold text-dark">Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
              <td>
                <a href="edit-kamar.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm me-1">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>
                <a href="hapus-kamar.php?id=<?= $row['id'] ?>" 
                   onclick="return confirm('Yakin hapus kamar ini?')" 
                   class="btn btn-danger btn-sm">
                  <i class="bi bi-trash-fill"></i> Hapus
                </a>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>