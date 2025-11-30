<?php
session_start();

$_SESSION['nama'] = $_POST['nama'];
$_SESSION['lokasi'] = $_POST['lokasi'];
$_SESSION['saldo'] = $_POST['saldo'];

header("Location: index.php");
exit;
