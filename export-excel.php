<?php
include 'cek-login.php';
include 'koneksi.php';

// Muat autoloader dari Composer
require 'vendor/autoload.php';

// Gunakan kelas-kelas yang diperlukan dari PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Ambil filter dari URL
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$id_kosan = isset($_GET['id_kosan']) ? $_GET['id_kosan'] : '';

// --- Query untuk mengambil data transaksi sesuai filter (Sama seperti sebelumnya) ---
$sql = "SELECT * FROM transaksi WHERE YEAR(jatuh_tempo) = ?";
$params = ["s", $tahun];
if (!empty($bulan)) {
    $sql .= " AND MONTH(jatuh_tempo) = ?";
    $params[0] .= "s";
    $params[] = $bulan;
}
if (!empty($id_kosan)) {
    $sql .= " AND id_kosan = ?";
    $params[0] .= "i";
    $params[] = $id_kosan;
}
$sql .= " ORDER BY jatuh_tempo ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param(...$params);
$stmt->execute();
$result = $stmt->get_result();

// --- Logika Pembuatan File .xlsx ---
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Judul Laporan
$bulan_nama = !empty($bulan) ? date('F', mktime(0, 0, 0, $bulan, 10)) : 'Semua Bulan';
$sheet->mergeCells('A1:H1');
$sheet->setCellValue('A1', 'Laporan Transaksi - ' . $bulan_nama . ' ' . $tahun);
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Header Tabel
$sheet->setCellValue('A3', 'No');
$sheet->setCellValue('B3', 'Nama Penghuni');
$sheet->setCellValue('C3', 'Nama Kosan');
$sheet->setCellValue('D3', 'Jumlah');
$sheet->setCellValue('E3', 'Jatuh Tempo');
$sheet->setCellValue('F3', 'Tanggal Lunas');
$sheet->setCellValue('G3', 'Metode Pembayaran');
$sheet->setCellValue('H3', 'Status');

// Style untuk Header
$headerStyle = $sheet->getStyle('A3:H3');
$headerStyle->getFont()->setBold(true);
$headerStyle->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// Isi Data dari Database
$rowNum = 4; // Mulai dari baris ke-4
$total_pemasukan = 0;
while ($row = $result->fetch_assoc()) {
    $sheet->setCellValue('A' . $rowNum, $rowNum - 3);
    $sheet->setCellValue('B' . $rowNum, $row['nama_penghuni']);
    $sheet->setCellValue('C' . $rowNum, $row['nama_kosan']);
    $sheet->setCellValue('D' . $rowNum, $row['jumlah']);
    $sheet->setCellValue('E' . $rowNum, date('d-m-Y', strtotime($row['jatuh_tempo'])));
    $sheet->setCellValue('F' . $rowNum, $row['tanggal_lunas'] ? date('d-m-Y', strtotime($row['tanggal_lunas'])) : '-');
    $sheet->setCellValue('G' . $rowNum, $row['metode']);
    $sheet->setCellValue('H' . $rowNum, $row['status']);

    if ($row['status'] == 'lunas') {
        $total_pemasukan += $row['jumlah'];
    }
    $rowNum++;
}

// Total Pemasukan
$sheet->mergeCells('A' . $rowNum . ':C' . $rowNum);
$sheet->setCellValue('A' . $rowNum, 'TOTAL PEMASUKAN (LUNAS)');
$sheet->setCellValue('D' . $rowNum, $total_pemasukan);
$sheet->getStyle('A' . $rowNum . ':D' . $rowNum)->getFont()->setBold(true);

// Atur format angka dan lebar kolom
$sheet->getStyle('D4:D' . $rowNum)->getNumberFormat()->setFormatCode('_("Rp"* #,##0_);_("Rp"* \(#,##0\);_("Rp"* "-"??_);_(@_)');
foreach (range('B', 'H') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// --- Logika Pengiriman File ke Browser ---
$nama_file = "laporan-transaksi-" . $tahun . "-" . $bulan_nama . ".xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $nama_file . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

$conn->close();
exit;
?>