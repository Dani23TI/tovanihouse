<?php
$host = "sql102.infinityfree.com";   // atau 127.0.0.1
$user = "if0_40095105";        // default Laragon
$pass = "Gonzales346";            // default Laragon biasanya kosong
$db   = "if0_40095105_kosan";    // ganti sesuai nama database kamu

$conn = mysqli_connect($host, $user, $pass, $db); // pakai $conn

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
