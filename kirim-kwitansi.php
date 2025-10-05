<?php
include 'koneksi.php'; // Sebaiknya cek-login.php juga di-include jika halaman ini butuh login
// include 'cek-login.php';

// Validasi ID transaksi dari URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID transaksi tidak valid atau tidak disertakan.");
}
$id = intval($_GET['id']);

// Ambil data transaksi menggunakan prepared statement untuk keamanan
$stmt = $conn->prepare("SELECT * FROM transaksi WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$trx = $result->fetch_assoc();

if (!$trx) {
    die("Transaksi tidak ditemukan.");
}

// Ambil data penghuni untuk mendapatkan nomor WhatsApp
$stmt_penghuni = $conn->prepare("SELECT nohp FROM penghuni WHERE id = ?");
$stmt_penghuni->bind_param("i", $trx['id_penghuni']);
$stmt_penghuni->execute();
$result_penghuni = $stmt_penghuni->get_result();
$penghuni = $result_penghuni->fetch_assoc();

// --- WhatsApp Handling ---
$wa_link = '';
if ($penghuni && !empty($penghuni['nohp'])) {
    // Membersihkan dan memformat nomor HP ke format internasional (62)
    $no = preg_replace('/[^0-9]/','', $penghuni['nohp']);
    if (substr($no, 0, 1) == '0') {
        $no = '62' . substr($no, 1);
    } elseif (substr($no, 0, 2) != '62') {
        $no = '62' . $no;
    }

    // Teks pesan untuk WhatsApp
    $text = "Kwitansi Pembayaran - Kos Dashboard%0A%0A"
          . "ID Transaksi: " . rawurlencode($trx['id']) . "%0A"
          . "Nama: " . rawurlencode($trx['nama_penghuni']) . "%0A"
          . "Kosan: " . rawurlencode($trx['nama_kosan']) . "%0A"
          . "Jumlah: *Rp " . rawurlencode(number_format($trx['jumlah'], 0, ',', '.')) . "*%0A"
          . "Status: " . rawurlencode(ucfirst($trx['status'])) . "%0A%0A"
          . "Terima kasih atas pembayaran Anda.";

    $wa_link = "https://wa.me/{$no}?text={$text}";
}

// --- Membangun HTML untuk tampilan kwitansi ---
$kwitansi_html = '
  <div style="font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
    <div style="text-align: center; margin-bottom: 20px;">
        <h2 style="margin: 0; color: #0d6efd;">Kwitansi Pembayaran</h2>
        <p style="margin: 5px 0; color: #666;">ID Transaksi: #'.htmlspecialchars($trx['id']).'</p>
    </div>
    <hr>
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
      <tr style="border-bottom: 1px solid #eee;"><td style="padding: 8px; color: #555;">Telah Diterima Dari</td><td style="padding: 8px;"><strong>'.htmlspecialchars($trx['nama_penghuni']).'</strong></td></tr>
      <tr style="border-bottom: 1px solid #eee;"><td style="padding: 8px; color: #555;">Untuk Pembayaran</td><td style="padding: 8px;">Sewa '.htmlspecialchars($trx['nama_kosan']).'</td></tr>
      <tr style="border-bottom: 1px solid #eee;"><td style="padding: 8px; color: #555;">Jatuh Tempo</td><td style="padding: 8px;">'.date('d F Y', strtotime($trx['jatuh_tempo'])).'</td></tr>
      <tr style="border-bottom: 1px solid #eee;"><td style="padding: 8px; color: #555;">Metode Pembayaran</td><td style="padding: 8px;">'.htmlspecialchars($trx['metode']).'</td></tr>
      <tr style="border-bottom: 1px solid #eee;"><td style="padding: 8px; color: #555;">Status</td><td style="padding: 8px; font-weight: bold; color: '.($trx['status'] == 'lunas' ? 'green' : 'red').';">'.ucfirst(htmlspecialchars($trx['status'])).'</td></tr>
    </table>
    <div style="margin-top: 20px; text-align: right;">
        <p style="font-size: 16px; margin: 0;">Jumlah Pembayaran</p>
        <h3 style="font-size: 24px; margin: 5px 0; color: #0d6efd;">Rp '.number_format($trx['jumlah'], 0, ',', '.').'</h3>
    </div>
    <hr>
    <p style="font-size: 12px; color: #888; text-align: center;">Terima kasih atas pembayaran Anda. Kwitansi ini dicetak secara otomatis dan sah tanpa tanda tangan.</p>
  </div>
';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Transaksi #<?= htmlspecialchars($trx['id']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .kwitansi-actions {
            max-width: 640px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        @media print {
            body { background-color: #fff; }
            .no-print { display: none !important; }
            .kwitansi-container { margin: 0; padding: 0; }
        }
    </style>
</head>
<body class="py-4">

<div class="container kwitansi-container">

    <div class="kwitansi-actions mb-4 no-print">
        <h4 class="mb-3">Opsi Kwitansi</h4>
        <div class="d-flex flex-wrap gap-2">
            <a href="transaksi.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Kembali ke Transaksi
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="bi bi-printer-fill"></i> Cetak / Simpan PDF
            </button>
            <?php if($wa_link): ?>
                <a href="<?= $wa_link ?>" target="_blank" class="btn btn-success">
                    <i class="bi bi-whatsapp"></i> Kirim via WhatsApp
                </a>
            <?php else: ?>
                <button class="btn btn-success" disabled title="Nomor WhatsApp penghuni tidak tersedia">
                    <i class="bi bi-whatsapp"></i> Kirim via WhatsApp
                </button>
            <?php endif; ?>
        </div>
    </div>

    <?= $kwitansi_html ?>

</div>

</body>
</html>