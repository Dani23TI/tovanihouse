<?php include 'cek-login.php'; ?>
<?php
include 'koneksi.php';
// Query hanya mengambil kamar yang statusnya 'kosong'
$kamar_query = mysqli_query($conn,"SELECT kamar.id, kamar.nomor_kamar, kosan.nama as nama_kosan 
                                  FROM kamar 
                                  JOIN kosan ON kamar.id_kosan=kosan.id 
                                  WHERE kamar.status='kosong'
                                  ORDER BY kosan.nama, kamar.nomor_kamar");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penghuni Baru</title>
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
                    <i class="bi bi-person-plus-fill me-2"></i>Tambah Penghuni Baru
                </h3>
                <a href="penghuni.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <div class="card p-4">
                <form method="post" action="proses-tambah-penghuni.php" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="nama" class="form-label fw-semibold">Nama</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nohp" class="form-label fw-semibold">No HP</label>
                            <input type="text" name="nohp" id="nohp" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nohp_darurat" class="form-label fw-semibold">No HP Darurat</label>
                            <input type="text" name="nohp_darurat" id="nohp_darurat" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="id_kamar" class="form-label fw-semibold">Pilih Kamar (Hanya kamar kosong yang tampil)</label>
                        <select name="id_kamar" id="id_kamar" class="form-select" required>
                            <option value="" disabled selected>-- Pilih salah satu --</option>
                            <?php while($d=mysqli_fetch_assoc($kamar_query)): ?>
                                <option value="<?= $d['id'] ?>">
                                    <?= htmlspecialchars($d['nama_kosan']) ." - Kamar ". htmlspecialchars($d['nomor_kamar']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="foto_ktp" class="form-label fw-semibold">Foto KTP</label>
                        <input type="file" name="foto_ktp" id="foto_ktp" class="form-control" accept="image/*" required>
                        <small class="text-muted">Format JPG/PNG, max 2MB.</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tgl_masuk" class="form-label fw-semibold">Tanggal Masuk</label>
                            <input type="date" name="tgl_masuk" id="tgl_masuk" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tgl_keluar" class="form-label fw-semibold">Tanggal Keluar</label>
                            <input type="date" name="tgl_keluar" id="tgl_keluar" class="form-control" required>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Simpan Data Penghuni</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>