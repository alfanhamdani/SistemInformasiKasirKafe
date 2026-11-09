<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'barista') {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

$query = mysqli_prepare($koneksi, "
    UPDATE pesanan
    SET status = 'selesai'
    WHERE id_pesanan = ?
    AND status = 'diproses'
");

mysqli_stmt_bind_param(
    $query,
    "i",
    $id
);

mysqli_stmt_execute($query);

header("Location: dashboard_barista.php");
exit;

?>