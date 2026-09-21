<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

if ($id == $_SESSION['id_user']) {
    die("Akun yang sedang digunakan tidak dapat dihapus.");
}

$query = mysqli_prepare($koneksi, "
    DELETE FROM users
    WHERE id_user = ?
    AND role != 'manajer'
");

mysqli_stmt_bind_param($query, "i", $id);

mysqli_stmt_execute($query);

header("Location: kelola_karyawan.php");
exit;

?>