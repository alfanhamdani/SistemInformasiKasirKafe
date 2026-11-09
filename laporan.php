<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}


$query_total = mysqli_query($koneksi, "
    SELECT COUNT(*) AS total
    FROM pesanan
");

$data_total = mysqli_fetch_assoc($query_total);


$query_pendapatan = mysqli_query($koneksi, "
    SELECT COALESCE(SUM(total), 0) AS pendapatan
    FROM pesanan
");

$data_pendapatan = mysqli_fetch_assoc($query_pendapatan);


$query_menu = mysqli_query($koneksi, "
    SELECT
        menu.nama_menu,
        SUM(detail_pesanan.jumlah) AS jumlah_terjual
    FROM detail_pesanan
    INNER JOIN menu
        ON detail_pesanan.id_menu = menu.id_menu
    INNER JOIN pesanan
        ON detail_pesanan.id_pesanan = pesanan.id_pesanan
    WHERE pesanan.status IN ('dibayar', 'selesai')
    GROUP BY detail_pesanan.id_menu
    ORDER BY jumlah_terjual DESC
    LIMIT 5
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan - Kafe Filosofi</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="manager-container">

        <div class="manager-header">

            <h1>Kafe Filosofi - Manajer</h1>

            <a href="dashboard_manajer.php" class="manager-logout">
                ← Kembali
            </a>

        </div>


        <div class="manager-menu">

            <a href="kelola_menu.php" class="manager-menu-button">
                ☕ Kelola Menu
            </a>

            <a href="data_transaksi.php" class="manager-menu-button">
                📋 Data Transaksi
            </a>

            <a href="kelola_karyawan.php" class="manager-menu-button">
                👥 Kelola Karyawan
            </a>

            <a href="laporan.php" class="manager-menu-button">
                📊 Laporan
            </a>

        </div>


        <div class="manager-content">

            <h2>Laporan</h2>


            <div class="report-cards">

                <div class="report-card">

                    <span>Total Transaksi</span>

                    <strong>
                        <?php echo $data_total['total']; ?>
                    </strong>

                </div>


                <div class="report-card">

                    <span>Total Pendapatan</span>

                    <strong>
                        Rp <?php echo number_format($data_pendapatan['pendapatan'], 0, ',', '.'); ?>
                    </strong>

                </div>

            </div>


            <h3 class="report-title">
                Menu Terlaris
            </h3>


            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Nama Menu</th>
                            <th>Jumlah Terjual</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $no = 1;

                        while ($menu = mysqli_fetch_assoc($query_menu)) {

                            ?>

                            <tr>

                                <td>
                                    <?php echo $no++; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($menu['nama_menu']); ?>
                                </td>

                                <td>
                                    <?php echo $menu['jumlah_terjual']; ?> item
                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>

</html>