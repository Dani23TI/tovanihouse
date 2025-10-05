<?php include 'cek-login.php'; ?>
<?php
include 'koneksi.php';

// --- BAGIAN AKSI (ACTION HANDLER) ---
// (Aksi lunaskan dan perpanjang tetap sama)
if (isset($_GET['action']) && $_GET['action'] == 'lunaskan' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE transaksi SET status='lunas' WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
    header("Location: transaksi.php");
    exit;
}
if (isset($_GET['action']) && $_GET['action'] == 'perpanjang') {
    mysqli_query($conn, "
        UPDATE transaksi
        SET jatuh_tempo = DATE_ADD(jatuh_tempo, INTERVAL 1 MONTH),
            status='belum lunas'
        WHERE status='lunas'
    ");
    header("Location: transaksi.php");
    exit;
}

// --- PENGAMBILAN DATA UTAMA DENGAN FILTER ---
$kosan_list = mysqli_query($conn, "SELECT id, nama FROM kosan ORDER BY nama ASC");

// Ambil filter dari URL, default ke bulan dan tahun sekarang
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$id_kosan = isset($_GET['id_kosan']) ? $_GET['id_kosan'] : '';

// Siapkan query dengan filter
$sql = "SELECT * FROM transaksi WHERE MONTH(jatuh_tempo) = ? AND YEAR(jatuh_tempo) = ?";
$params = ["ss", $bulan, $tahun];

if (!empty($id_kosan)) {
    $sql .= " AND id_kosan = ?";
    $params[0] .= "i";
    $params[] = $id_kosan;
}
$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param(...$params);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Transaksi</title>
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
        .table thead {
            background: #0d6efd;
            color: white;
        }
        .table-hover tbody tr:hover { background-color: #eef3ff; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h3 class="fw-bold text-primary mb-0">
            <i class="bi bi-wallet-fill me-2"></i>Data Transaksi
        </h3>
        <div class="d-flex gap-2">
            <a href="tambah-transaksi.php" class="btn btn-success shadow-sm">
                <i class="bi bi-plus-lg me-1"></i> Tambah Transaksi
            </a>
            <a href="transaksi.php?action=perpanjang"
               onclick="return confirm('Yakin ingin perpanjang semua transaksi LUNAS ke bulan berikutnya?')"
               class="btn btn-warning shadow-sm">
                <i class="bi bi-arrow-clockwise me-1"></i> Perpanjang Semua
            </a>
        </div>
    </div>
    
    <div class="card p-3 mb-3">
        <form method="get" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="id_kosan" class="form-label">Filter Kosan</label>
                <select name="id_kosan" id="id_kosan" class="form-select">
                    <option value="">-- Semua Kosan --</option>
                    <?php mysqli_data_seek($kosan_list, 0); while($k = mysqli_fetch_assoc($kosan_list)){
                        $selected = ($id_kosan == $k['id']) ? 'selected' : '';
                        echo "<option value='{$k['id']}' $selected>" . htmlspecialchars($k['nama']) . "</option>";
                    } ?>
                </select>
            </div>
            <div class="col-md-3">
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" id="bulan" class="form-select">
                    <?php for($i=1;$i<=12;$i++){
                        $val = str_pad($i,2,'0',STR_PAD_LEFT);
                        $selected = ($bulan==$val)?'selected':'';
                        echo "<option value='$val' $selected>".date('F', mktime(0,0,0,$i,10))."</option>";
                    } ?>
                </select>
            </div>
            <div class="col-md-2">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-select">
                    <?php $tahun_sekarang = date('Y'); for($i=$tahun_sekarang-5;$i<=$tahun_sekarang+1;$i++){
                        $selected = ($tahun==$i)?'selected':'';
                        echo "<option value='$i' $selected>$i</option>";
                    } ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100" formaction="transaksi.php">
                    <i class="bi bi-funnel-fill"></i> Filter
                </button>
                <button type="submit" class="btn btn-success w-100" formaction="export-excel.php">
                    <i class="bi bi-file-earmark-excel-fill"></i> Export
                </button>
            </div>
        </form>
    </div>

    <div class="card p-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>Penghuni & Kosan</th>
                    <th>Jumlah</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($row['nama_penghuni']) ?></strong><br>
                            <small class="text-muted"><?= htmlspecialchars($row['nama_kosan']) ?></small>
                        </td>
                        <td class="fw-bold">Rp <?= number_format($row['jumlah'], 0, ',', '.') ?></td>
                        <td><?= date('d M Y', strtotime($row['jatuh_tempo'])) ?></td>
                        <td>
                            <?php if ($row['status'] == 'lunas') { ?>
                                <span class="badge bg-success rounded-pill px-3 py-2">Lunas</span>
                            <?php } else { ?>
                                <span class="badge bg-danger rounded-pill px-3 py-2">Belum Lunas</span>
                            <?php } ?>
                        </td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <?php if ($row['status'] == 'belum lunas') { ?>
                                    <a href="transaksi.php?action=lunaskan&id=<?= $row['id'] ?>" class="btn btn-success btn-sm">
                                       <i class="bi bi-check-circle-fill"></i> Lunaskan
                                    </a>
                                <?php } ?>
                                <a href="edit-transaksi.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <a href="kirim-kwitansi.php?id=<?= $row['id'] ?>" class="btn btn-info btn-sm text-white" target="_blank">
                                    <i class="bi bi-receipt"></i> Kwitansi
                                </a>
                                <a href="hapus-transaksi.php?id=<?= $row['id'] ?>"
                                   onclick="return confirm('Hapus transaksi ini?')"
                                   class="btn btn-danger btn-sm">
                                   <i class="bi bi-trash-fill"></i> Hapus
                                </a>
                            </div>
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