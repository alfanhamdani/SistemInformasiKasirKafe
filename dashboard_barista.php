<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'barista') {
    header("Location: index.php");
    exit;
}

$query_baru = mysqli_query($koneksi, "
    SELECT
        pesanan.id_pesanan,
        pesanan.nama_pelanggan,
        pesanan.total,
        pesanan.tanggal,
        users.nama AS nama_kasir
    FROM pesanan
    INNER JOIN users
        ON pesanan.id_user = users.id_user
    WHERE pesanan.status = 'dibayar'
    ORDER BY pesanan.tanggal ASC
");


$query_proses = mysqli_query($koneksi, "
    SELECT
        pesanan.id_pesanan,
        pesanan.nama_pelanggan,
        pesanan.total,
        pesanan.tanggal,
        users.nama AS nama_kasir
    FROM pesanan
    INNER JOIN users
        ON pesanan.id_user = users.id_user
    WHERE pesanan.status = 'diproses'
    ORDER BY pesanan.tanggal ASC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kafe Filosofi - Barista</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="barista-container">

        <div class="barista-header">

            <h1>Kafe - Barista</h1>

            <a href="logout.php" class="logout-button" onclick="return confirm('Apakah Anda yakin ingin logout?');">
                Logout
            </a>

        </div>


        <div class="barista-content">


            <!-- PESANAN BARU -->

            <div class="barista-section">

                <div class="barista-section-title">

                    <h2>Pesanan Baru</h2>

                    <span>
                        <?php echo mysqli_num_rows($query_baru); ?> Pesanan
                    </span>

                </div>


                <?php if (mysqli_num_rows($query_baru) == 0) { ?>

                    <div class="empty-order">

                        Tidak ada pesanan baru.

                    </div>

                <?php } ?>


                <?php while ($pesanan = mysqli_fetch_assoc($query_baru)) { ?>


                    <div class="order-card">

                        <div class="order-card-header">

                            <div>

                                <strong>
                                    Pesanan #<?php echo $pesanan['id_pesanan']; ?>
                                </strong>

                                <p>
                                    <?php echo htmlspecialchars($pesanan['nama_pelanggan']); ?>
                                </p>

                            </div>

                            <span class="status-baru">
                                BARU
                            </span>

                        </div>


                        <div class="order-info">

                            <div>

                                <small>Kasir</small>

                                <p>
                                    <?php echo htmlspecialchars($pesanan['nama_kasir']); ?>
                                </p>

                            </div>


                            <div>

                                <small>Waktu</small>

                                <p>
                                    <?php
                                    echo date(
                                        'd-m-Y H:i',
                                        strtotime($pesanan['tanggal'])
                                    );
                                    ?>
                                </p>

                            </div>

                        </div>


                        <div class="order-detail">

                            <?php

                            $id_pesanan = $pesanan['id_pesanan'];

                            $detail_query = mysqli_query($koneksi, "
                            SELECT
                                menu.nama_menu,
                                detail_pesanan.jumlah
                            FROM detail_pesanan
                            INNER JOIN menu
                                ON detail_pesanan.id_menu = menu.id_menu
                            WHERE detail_pesanan.id_pesanan = '$id_pesanan'
                        ");

                            while ($item = mysqli_fetch_assoc($detail_query)) {

                                ?>

                                <div class="order-item">

                                    <span>
                                        <?php echo htmlspecialchars($item['nama_menu']); ?>
                                    </span>

                                    <strong>
                                        x<?php echo $item['jumlah']; ?>
                                    </strong>

                                </div>

                            <?php } ?>

                        </div>


                        <div class="order-total">

                            Total

                            <strong>
                                Rp <?php echo number_format(
                                    $pesanan['total'],
                                    0,
                                    ',',
                                    '.'
                                ); ?>
                            </strong>

                        </div>


                        <a href="mulai_proses.php?id=<?php echo $pesanan['id_pesanan']; ?>" class="process-button"
                            onclick="return confirm('Mulai memproses pesanan ini?')">
                            Mulai Proses
                        </a>

                    </div>


                <?php } ?>

            </div>


            <!-- PESANAN DALAM PROSES -->

            <div class="barista-section">

                <div class="barista-section-title">

                    <h2>Pesanan Dalam Proses</h2>

                    <span>
                        <?php echo mysqli_num_rows($query_proses); ?> Pesanan
                    </span>

                </div>


                <?php if (mysqli_num_rows($query_proses) == 0) { ?>

                    <div class="empty-order">

                        Tidak ada pesanan yang sedang diproses.

                    </div>

                <?php } ?>


                <?php while ($pesanan = mysqli_fetch_assoc($query_proses)) { ?>


                    <div class="order-card process-card">

                        <div class="order-card-header">

                            <div>

                                <strong>
                                    Pesanan #<?php echo $pesanan['id_pesanan']; ?>
                                </strong>

                                <p>
                                    <?php echo htmlspecialchars($pesanan['nama_pelanggan']); ?>
                                </p>

                            </div>

                            <span class="status-proses">
                                DIPROSES
                            </span>

                        </div>


                        <div class="order-detail">

                            <?php

                            $id_pesanan = $pesanan['id_pesanan'];

                            $detail_query = mysqli_query($koneksi, "
                            SELECT
                                menu.nama_menu,
                                detail_pesanan.jumlah
                            FROM detail_pesanan
                            INNER JOIN menu
                                ON detail_pesanan.id_menu = menu.id_menu
                            WHERE detail_pesanan.id_pesanan = '$id_pesanan'
                        ");

                            while ($item = mysqli_fetch_assoc($detail_query)) {

                                ?>

                                <div class="order-item">

                                    <span>
                                        <?php echo htmlspecialchars($item['nama_menu']); ?>
                                    </span>

                                    <strong>
                                        x<?php echo $item['jumlah']; ?>
                                    </strong>

                                </div>

                            <?php } ?>

                        </div>


                        <a href="selesai_pesanan.php?id=<?php echo $pesanan['id_pesanan']; ?>" class="finish-button"
                            onclick="return confirm('Apakah pesanan ini sudah selesai dibuat?')">
                            Pesanan Selesai
                        </a>

                    </div>


                <?php } ?>

            </div>


        </div>

    </div>

</body>

</html>