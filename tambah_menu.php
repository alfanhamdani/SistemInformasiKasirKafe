<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}

$nama_menu = $_POST['nama_menu'] ?? '';
$harga = $_POST['harga'] ?? 0;

$query = mysqli_prepare($koneksi, "
    INSERT INTO menu
    (nama_menu, harga, status)
    VALUES (?, ?, 'tersedia')
");

mysqli_stmt_bind_param(
    $query,
    "sd",
    $nama_menu,
    $harga
);

mysqli_stmt_execute($query);

header("Location: kelola_menu.php");
exit;

?>