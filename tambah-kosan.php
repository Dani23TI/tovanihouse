<?php include 'cek-login.php'; ?>
<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kosan Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        /* Menggunakan style yang konsisten dari halaman lain */
        body { background: #f5f7fa; }
        .navbar {
            background: linear-gradient(135deg, #0d6efd, #6610f2);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            border: none;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold text-primary mb-0">
                    <i class="bi bi-building-fill-add me-2"></i>Tambah Kosan Baru
                </h3>
                <a href="data-kos.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <div class="card p-4">
                <form method="post" action="proses-tambah-kosan.php">
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama Kosan</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label fw-semibold">Alamat</label>
                        <textarea name="alamat" id="alamat" class="form-control" rows="3" required></textarea>
                        <small class="text-muted">Alamat lengkap kosan.</small>
                    </div>
                    <div class="mb-3">
                        <label for="total_kamar" class="form-label fw-semibold">Jumlah Kamar</label>
                        <input type="number" name="total_kamar" id="total_kamar" class="form-control" required min="1">
                        <small class="text-muted">Jumlah total kamar yang akan dibuat untuk kosan ini.</small>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Simpan Data Kosan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>