<?php include 'cek-login.php'; ?>
<?php
include 'koneksi.php';

// ambil penghuni + kosan + harga kamar
$penghuni_query = mysqli_query($conn,"
    SELECT penghuni.id, penghuni.nama AS nama_penghuni, 
           kamar.nomor_kamar, kosan.nama AS nama_kosan, kosan.id AS id_kosan, kamar.harga
    FROM penghuni
    JOIN kamar ON penghuni.id_kamar = kamar.id
    JOIN kosan ON kamar.id_kosan = kosan.id
    ORDER BY penghuni.nama
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Transaksi Baru</title>
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
    <script>
    function isiData(select){
      // Ambil semua data dari atribut data-*
      let selectedOption = select.options[select.selectedIndex];
      let namaPenghuni   = selectedOption.getAttribute("data-nama");
      let namaKosan      = selectedOption.getAttribute("data-kosan");
      let idKosan        = selectedOption.getAttribute("data-idkosan");
      let harga          = selectedOption.getAttribute("data-harga");
      
      // Masukkan data ke input yang sesuai
      document.getElementById("nama_penghuni").value = namaPenghuni;
      document.getElementById("nama_kosan").value    = namaKosan;
      document.getElementById("id_kosan").value      = idKosan;
      document.getElementById("jumlah").value        = harga;
    }
    </script>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold text-primary mb-0">
                    <i class="bi bi-wallet2 me-2"></i>Buat Transaksi Baru
                </h3>
                <a href="transaksi.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <div class="card p-4">
                <form action="proses-tambah-transaksi.php" method="POST">
                    <div class="mb-3">
                        <label for="id_penghuni" class="form-label fw-semibold">Pilih Penghuni</label>
                        <select name="id_penghuni" id="id_penghuni" class="form-select" onchange="isiData(this)" required>
                            <option value="" disabled selected>-- Pilih Penghuni untuk mengisi data otomatis --</option>
                            <?php while($row=mysqli_fetch_assoc($penghuni_query)){ ?>
                                <option value="<?= $row['id'] ?>" 
                                        data-nama="<?= htmlspecialchars($row['nama_penghuni']) ?>" 
                                        data-kosan="<?= htmlspecialchars($row['nama_kosan']) ?>" 
                                        data-idkosan="<?= $row['id_kosan'] ?>" 
                                        data-harga="<?= $row['harga'] ?>">
                                    <?= htmlspecialchars($row['nama_penghuni']) ?> - <?= htmlspecialchars($row['nama_kosan']) ?> (Kamar <?= htmlspecialchars($row['nomor_kamar']) ?>)
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <input type="hidden" id="nama_penghuni" name="nama_penghuni">
                    <input type="hidden" id="nama_kosan" name="nama_kosan">
                    <input type="hidden" id="id_kosan" name="id_kosan">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jumlah" class="form-label fw-semibold">Jumlah Tagihan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" id="jumlah" name="jumlah" class="form-control" required readonly>
                            </div>
                            <small class="text-muted">Jumlah terisi otomatis sesuai harga kamar.</small>
                        </div>
                         <div class="col-md-6 mb-3">
                            <label for="jatuh_tempo" class="form-label fw-semibold">Jatuh Tempo</label>
                            <input type="date" name="jatuh_tempo" id="jatuh_tempo" class="form-control" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="metode" class="form-label fw-semibold">Metode Pembayaran</label>
                            <select name="metode" id="metode" class="form-select" required>
                                <option value="Cash">Cash</option>
                                <option value="Transfer">Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-semibold">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="belum lunas" selected>Belum Lunas</option>
                                <option value="lunas">Lunas</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-success btn-lg">Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>