<?php
include 'cek-login.php';
include 'koneksi.php';

// Ambil filter dari URL
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$id_kosan = isset($_GET['id_kosan']) ? $_GET['id_kosan'] : '';

// Buat nama file dinamis
$nama_file = "laporan-transaksi-" . $tahun . "-" . $bulan . ".xls";

// Set header untuk download file Excel
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=$nama_file");

// --- Query untuk mengambil data transaksi sesuai filter ---
$sql = "SELECT * FROM transaksi WHERE MONTH(jatuh_tempo) = ? AND YEAR(jatuh_tempo) = ?";
$params = ["ss", $bulan, $tahun];

if (!empty($id_kosan)) {
    $sql .= " AND id_kosan = ?";
    $params[0] .= "i"; // Tambah tipe 'i' untuk integer
    $params[] = $id_kosan;
}
$sql .= " ORDER BY jatuh_tempo ASC";

$stmt = $conn->prepare($sql);
// Gunakan ... untuk unpack array parameter ke bind_param
$stmt->bind_param(...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
<h3>Laporan Transaksi Bulan <?= date('F', mktime(0, 0, 0, $bulan, 10)) ?> Tahun <?= $tahun ?></h3>

<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Penghuni</th>
            <th>Nama Kosan</th>
            <th>Jumlah</th>
            <th>Jatuh Tempo</th>
            <th>Metode Pembayaran</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php 
        $no = 1;
        $total_pemasukan = 0;
        while ($row = $result->fetch_assoc()): 
            if ($row['status'] == 'lunas') {
                $total_pemasukan += $row['jumlah'];
            }
        ?>
        <tr>
            <td><?= $no++ ?></td>
            <td><?= htmlspecialchars($row['nama_penghuni']) ?></td>
            <td><?= htmlspecialchars($row['nama_kosan']) ?></td>
            <td><?= $row['jumlah'] ?></td>
            <td><?= date('d M Y', strtotime($row['jatuh_tempo'])) ?></td>
            <td><?= htmlspecialchars($row['metode']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
        </tr>
        <?php endwhile; ?>
        <tr>
            <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL PEMASUKAN (LUNAS)</td>
            <td style="font-weight: bold;"><?= $total_pemasukan ?></td>
            <td colspan="3"></td>
        </tr>
    </tbody>
</table>