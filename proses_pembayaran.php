<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'kasir') {
    header("Location: index.php");
    exit;
}

$id_pesanan = intval($_POST['id_pesanan'] ?? 0);

$metode = $_POST['metode_pembayaran'] ?? '';

$total = floatval($_POST['total'] ?? 0);

$jumlah_bayar = floatval($_POST['jumlah_bayar'] ?? 0);


if ($id_pesanan <= 0 || $metode == '') {
    die("Data pembayaran tidak lengkap.");
}


if (!in_array($metode, ['cash', 'qris', 'e-wallet'])) {
    die("Metode pembayaran tidak valid.");
}


/* Jika pembayaran cash */

if ($metode == 'cash') {

    if ($jumlah_bayar < $total) {
        die("Uang pembayaran kurang.");
    }

    $kembalian = $jumlah_bayar - $total;

} else {

    $jumlah_bayar = $total;

    $kembalian = 0;

}


/* Simpan pembayaran */

$query = mysqli_prepare($koneksi, "
    INSERT INTO pembayaran
    (
        id_pesanan,
        metode_pembayaran,
        jumlah_bayar,
        kembalian
    )
    VALUES (?, ?, ?, ?)
");

mysqli_stmt_bind_param(
    $query,
    "isdd",
    $id_pesanan,
    $metode,
    $jumlah_bayar,
    $kembalian
);

$simpan = mysqli_stmt_execute($query);


if (!$simpan) {
    die("Pembayaran gagal disimpan.");
}


/* Update status pesanan */

$update = mysqli_prepare($koneksi, "
    UPDATE pesanan
    SET status = 'dibayar'
    WHERE id_pesanan = ?
");

mysqli_stmt_bind_param(
    $update,
    "i",
    $id_pesanan
);

mysqli_stmt_execute($update);


/* Masuk ke halaman berhasil */

header(
    "Location: pembayaran_sukses.php?id_pesanan=" . $id_pesanan
);

exit;

?>