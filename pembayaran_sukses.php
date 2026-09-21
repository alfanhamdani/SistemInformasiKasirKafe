<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'kasir') {
    header("Location: index.php");
    exit;
}

$id_pesanan = intval($_GET['id_pesanan'] ?? 0);

$query = mysqli_prepare($koneksi, "
    SELECT
        pesanan.id_pesanan,
        pesanan.nama_pelanggan,
        pesanan.total,
        pembayaran.metode_pembayaran,
        pembayaran.jumlah_bayar,
        pembayaran.kembalian,
        pembayaran.tanggal_pembayaran
    FROM pesanan
    INNER JOIN pembayaran
        ON pesanan.id_pesanan = pembayaran.id_pesanan
    WHERE pesanan.id_pesanan = ?
    LIMIT 1
");

mysqli_stmt_bind_param(
    $query,
    "i",
    $id_pesanan
);

mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);

$data = mysqli_fetch_assoc($result);

if (!$data) {
    header("Location: dashboard_kasir.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Pembayaran Berhasil - Kafe Filosofi</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="success-container">

        <div class="success-box">

            <div class="success-icon">
                ✓
            </div>

            <h1>Pembayaran Berhasil</h1>

            <p class="success-text">
                Transaksi berhasil disimpan.
            </p>


            <div class="receipt">

                <div class="receipt-row">
                    <span>No. Pesanan</span>
                    <strong>
                        #<?php echo $data['id_pesanan']; ?>
                    </strong>
                </div>

                <div class="receipt-row">
                    <span>Pelanggan</span>
                    <strong>
                        <?php echo htmlspecialchars($data['nama_pelanggan']); ?>
                    </strong>
                </div>

                <div class="receipt-row">
                    <span>Total</span>
                    <strong>
                        Rp <?php echo number_format($data['total'], 0, ',', '.'); ?>
                    </strong>
                </div>

                <div class="receipt-row">
                    <span>Metode</span>
                    <strong>
                        <?php echo strtoupper($data['metode_pembayaran']); ?>
                    </strong>
                </div>

                <div class="receipt-row">
                    <span>Bayar</span>
                    <strong>
                        Rp <?php echo number_format($data['jumlah_bayar'], 0, ',', '.'); ?>
                    </strong>
                </div>

                <div class="receipt-row">
                    <span>Kembalian</span>
                    <strong>
                        Rp <?php echo number_format($data['kembalian'], 0, ',', '.'); ?>
                    </strong>
                </div>

            </div>


            <a href="dashboard_kasir.php" class="success-button">
                Kembali ke Kasir
            </a>

        </div>

    </div>

</body>

</html>