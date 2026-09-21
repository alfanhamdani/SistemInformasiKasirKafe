<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

$query = mysqli_prepare($koneksi, "
    DELETE FROM menu
    WHERE id_menu = ?
");

mysqli_stmt_bind_param($query, "i", $id);

mysqli_stmt_execute($query);

header("Location: kelola_menu.php");
exit;

?>