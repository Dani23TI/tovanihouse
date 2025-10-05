<?php
$host = "localhost";   // atau 127.0.0.1
$user = "root";        // default Laragon
$pass = "";            // default Laragon biasanya kosong
$db   = "kosan_db";    // ganti sesuai nama database kamu

$conn = mysqli_connect($host, $user, $pass, $db); // pakai $conn

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
