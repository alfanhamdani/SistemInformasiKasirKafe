<?php

session_start();
include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}

$id_user = intval($_GET['id'] ?? 0);

if ($id_user <= 0) {
    header("Location: dashboard_manajer.php");
    exit;
}

if ($id_user == $_SESSION['id_user']) {
    echo "<script>
        alert('Akun yang sedang login tidak dapat dihapus!');
        window.location.href='dashboard_manajer.php';
    </script>";
    exit;
}

$cek = mysqli_prepare($koneksi, "
    SELECT COUNT(*) AS jumlah
    FROM pesanan
    WHERE id_user = ?
");

mysqli_stmt_bind_param($cek, "i", $id_user);
mysqli_stmt_execute($cek);

$result = mysqli_stmt_get_result($cek);
$data = mysqli_fetch_assoc($result);

if ($data['jumlah'] > 0) {
    echo "<script>
        alert('Karyawan tidak dapat dihapus karena sudah memiliki riwayat transaksi!');
        window.location.href='dashboard_manajer.php';
    </script>";
    exit;
}

$hapus = mysqli_prepare($koneksi, "
    DELETE FROM users
    WHERE id_user = ?
");

mysqli_stmt_bind_param($hapus, "i", $id_user);

try {
    mysqli_stmt_execute($hapus);

    echo "<script>
        alert('Karyawan berhasil dihapus!');
        window.location.href='dashboard_manajer.php';
    </script>";

} catch (mysqli_sql_exception $e) {

    echo "<script>
        alert('Karyawan tidak dapat dihapus karena masih terhubung dengan data lain!');
        window.location.href='dashboard_manajer.php';
    </script>";

}

?>