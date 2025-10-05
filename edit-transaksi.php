<?php include 'cek-login.php'; ?>
<?php
include 'koneksi.php';

// 1. Ambil ID dari URL dan validasi
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: transaksi.php');
    exit;
}
$id = $_GET['id'];

// 2. Ambil data transaksi yang akan diedit (tidak perlu join lagi)
$stmt_transaksi = $conn->prepare("SELECT * FROM transaksi WHERE id = ?");
$stmt_transaksi->bind_param("i", $id);
$stmt_transaksi->execute();
$result_transaksi = $stmt_transaksi->get_result();
$transaksi = $result_transaksi->fetch_assoc();

// Jika data dengan ID tersebut tidak ditemukan, kembalikan
if (!$transaksi) {
    header('Location: transaksi.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Transaksi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
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
                    <i class="bi bi-pencil-square me-2"></i>Edit Transaksi
                </h3>
                <a href="transaksi.php" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali
                </a>
            </div>

            <div class="card p-4">
                <form action="proses-edit-transaksi.php" method="POST">
                    <input type="hidden" name="id" value="<?= $transaksi['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Penghuni</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($transaksi['nama_penghuni']) ?> - <?= htmlspecialchars($transaksi['nama_kosan']) ?>" disabled readonly>
                        <small class="text-muted">Nama penghuni tidak dapat diubah dari halaman ini.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="jumlah" class="form-label fw-semibold">Jumlah Tagihan</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" id="jumlah" name="jumlah" class="form-control" value="<?= htmlspecialchars($transaksi['jumlah']) ?>" required>
                            </div>
                        </div>
                         <div class="col-md-6 mb-3">
                            <label for="jatuh_tempo" class="form-label fw-semibold">Jatuh Tempo</label>
                            <input type="date" name="jatuh_tempo" id="jatuh_tempo" class="form-control" value="<?= htmlspecialchars($transaksi['jatuh_tempo']) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="metode" class="form-label fw-semibold">Metode Pembayaran</label>
                            <select name="metode" id="metode" class="form-select" required>
                                <option value="Cash" <?= ($transaksi['metode'] == 'Cash') ? 'selected' : '' ?>>Cash</option>
                                <option value="Transfer" <?= ($transaksi['metode'] == 'Transfer') ? 'selected' : '' ?>>Transfer</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-semibold">Status</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="belum lunas" <?= ($transaksi['status'] == 'belum lunas') ? 'selected' : '' ?>>Belum Lunas</option>
                                <option value="lunas" <?= ($transaksi['status'] == 'lunas') ? 'selected' : '' ?>>Lunas</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-grid mt-3">
                        <button type="submit" class="btn btn-primary btn-lg">Update Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>