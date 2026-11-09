<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}

$id_menu = intval($_GET['id'] ?? 0);

if ($id_menu <= 0) {
    header("Location: kelola_menu.php");
    exit;
}

// Cek apakah menu sudah digunakan dalam transaksi

$cek = mysqli_prepare($koneksi, "
    SELECT COUNT(*) AS jumlah
    FROM detail_pesanan
    WHERE id_menu = ?
");

mysqli_stmt_bind_param($cek, "i", $id_menu);
mysqli_stmt_execute($cek);

$result = mysqli_stmt_get_result($cek);
$data = mysqli_fetch_assoc($result);

// Jika menu sudah memiliki riwayat transaksi

if ($data['jumlah'] > 0) {

    echo "<script>
        alert('Menu tidak dapat dihapus karena sudah memiliki riwayat transaksi!');
        window.location.href='kelola_menu.php';
    </script>";

    exit;
}

// Jika belum pernah digunakan, menu boleh dihapus

$hapus = mysqli_prepare($koneksi, "
    DELETE FROM menu
    WHERE id_menu = ?
");

mysqli_stmt_bind_param($hapus, "i", $id_menu);

try {

    mysqli_stmt_execute($hapus);

    echo "<script>
        alert('Menu berhasil dihapus!');
        window.location.href='kelola_menu.php';
    </script>";

} catch (mysqli_sql_exception $e) {

    echo "<script>
        alert('Menu tidak dapat dihapus karena masih terhubung dengan data lain!');
        window.location.href='kelola_menu.php';
    </script>";

}

?>