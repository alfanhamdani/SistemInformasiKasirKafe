<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'kasir') {
    header("Location: index.php");
    exit;
}

$nama_pelanggan = $_POST['nama_pelanggan'] ?? '';
$pesanan = $_POST['pesanan'] ?? '';

if ($nama_pelanggan == '' || $pesanan == '') {
    header("Location: dashboard_kasir.php");
    exit;
}

$data_pesanan = json_decode($pesanan, true);

if (!$data_pesanan || count($data_pesanan) == 0) {
    header("Location: dashboard_kasir.php");
    exit;
}

$total = 0;

foreach ($data_pesanan as $item) {

    $id_menu = intval($item['id']);
    $jumlah = intval($item['jumlah']);

    $query = mysqli_query($koneksi, "
        SELECT harga
        FROM menu
        WHERE id_menu = $id_menu
    ");

    $menu = mysqli_fetch_assoc($query);

    if ($menu) {
        $total += $menu['harga'] * $jumlah;
    }
}


$id_user = $_SESSION['id_user'];

$query_pesanan = mysqli_query($koneksi, "
    INSERT INTO pesanan
    (id_user, nama_pelanggan, total, status)
    VALUES
    ($id_user, '$nama_pelanggan', $total, 'menunggu')
");

if (!$query_pesanan) {
    die("Pesanan gagal disimpan: " . mysqli_error($koneksi));
}

$id_pesanan = mysqli_insert_id($koneksi);


foreach ($data_pesanan as $item) {

    $id_menu = intval($item['id']);
    $jumlah = intval($item['jumlah']);

    $query_menu = mysqli_query($koneksi, "
        SELECT harga
        FROM menu
        WHERE id_menu = $id_menu
    ");

    $menu = mysqli_fetch_assoc($query_menu);

    if ($menu) {

        $harga = $menu['harga'];

        $subtotal = $harga * $jumlah;

        mysqli_query($koneksi, "
            INSERT INTO detail_pesanan
            (id_pesanan, id_menu, jumlah, harga, subtotal)
            VALUES
            ($id_pesanan, $id_menu, $jumlah, $harga, $subtotal)
        ");

    }

}

header("Location: pembayaran.php?id_pesanan=" . $id_pesanan);
exit;

?>