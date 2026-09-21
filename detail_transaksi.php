<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

$query = mysqli_prepare($koneksi, "
    SELECT
        pesanan.id_pesanan,
        pesanan.nama_pelanggan,
        pesanan.total,
        pesanan.status,
        pesanan.tanggal,
        users.nama AS nama_kasir
    FROM pesanan
    INNER JOIN users
        ON pesanan.id_user = users.id_user
    WHERE pesanan.id_pesanan = ?
");

mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$pesanan = mysqli_fetch_assoc($result);

if (!$pesanan) {
    header("Location: data_transaksi.php");
    exit;
}


$detail = mysqli_prepare($koneksi, "
    SELECT
        menu.nama_menu,
        detail_pesanan.jumlah,
        detail_pesanan.harga,
        detail_pesanan.subtotal
    FROM detail_pesanan
    INNER JOIN menu
        ON detail_pesanan.id_menu = menu.id_menu
    WHERE detail_pesanan.id_pesanan = ?
");

mysqli_stmt_bind_param($detail, "i", $id);
mysqli_stmt_execute($detail);

$detail_result = mysqli_stmt_get_result($detail);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Transaksi</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="manager-container">

        <div class="manager-header">

            <h1>Detail Transaksi</h1>

            <a href="data_transaksi.php" class="manager-logout">
                ← Kembali
            </a>

        </div>


        <div class="manager-content">

            <h2>
                Pesanan #
                <?php echo $pesanan['id_pesanan']; ?>
            </h2>

            <p>
                Pelanggan:
                <?php echo htmlspecialchars($pesanan['nama_pelanggan']); ?>
            </p>

            <p>
                Kasir:
                <?php echo htmlspecialchars($pesanan['nama_kasir']); ?>
            </p>

            <br>

            <div class="table-container">

                <table>

                    <thead>

                        <tr>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Harga</th>
                            <th>Subtotal</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php while ($item = mysqli_fetch_assoc($detail_result)) { ?>

                            <tr>

                                <td>
                                    <?php echo htmlspecialchars($item['nama_menu']); ?>
                                </td>

                                <td>
                                    <?php echo $item['jumlah']; ?>
                                </td>

                                <td>
                                    Rp
                                    <?php echo number_format($item['harga'], 0, ',', '.'); ?>
                                </td>

                                <td>
                                    Rp
                                    <?php echo number_format($item['subtotal'], 0, ',', '.'); ?>
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>


            <div class="detail-total">

                TOTAL:
                Rp
                <?php echo number_format($pesanan['total'], 0, ',', '.'); ?>

            </div>

        </div>

    </div>

</body>

</html>