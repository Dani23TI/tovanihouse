<?php include 'cek-login.php'; ?>
<?php
include 'koneksi.php';
$kosan = mysqli_query($conn,"SELECT * FROM kosan ORDER BY nama ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kamar Baru</title>
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
                    <i class="bi bi-door-closed-fill me-2"></i>Tambah Kamar Baru
                </h3>
                <a href="kamar.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <div class="card p-4">
                <form action="proses-tambah-kamar.php" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_kosan" class="form-label fw-semibold">Pilih Kosan</label>
                            <select name="id_kosan" id="id_kosan" class="form-select" required>
                                <option value="" disabled selected>-- Pilih salah satu --</option>
                                <?php while($row=mysqli_fetch_assoc($kosan)){ ?>
                                    <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nomor_kamar" class="form-label fw-semibold">Nomor Kamar</label>
                            <input type="text" name="nomor_kamar" id="nomor_kamar" class="form-control" placeholder="Contoh: 101, A05" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="harga" class="form-label fw-semibold">Harga per Bulan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="harga" id="harga" class="form-control" placeholder="Contoh: 850000" required min="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-semibold">Status Awal</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="kosong" selected>Kosong</option>
                                <option value="terisi">Terisi</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-success btn-lg">Simpan Kamar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>