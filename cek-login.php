<?php
session_start();

// Periksa apakah session 'login' TIDAK ada atau TIDAK bernilai true.
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    // Jika tidak ada session, tendang pengguna ke halaman login
    header('Location: login.php');
    exit; // Pastikan tidak ada kode lain yang dieksekusi setelah redirect
}
?>