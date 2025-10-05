<?php
include 'koneksi.php';

$id = $_GET['id'];
mysqli_query($conn,"DELETE FROM kamar WHERE id='$id'");

header("Location: kamar.php");
