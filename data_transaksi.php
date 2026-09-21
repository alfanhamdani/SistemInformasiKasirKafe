<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}

$query = mysqli_query($koneksi, "
    SELECT
        pesanan.id_pesanan,
        pesanan.nama_pelanggan,
        users.nama AS nama_kasir,
        pesanan.total,
        pesanan.status,
        pesanan.tanggal
    FROM pesanan
    INNER JOIN users
        ON pesanan.id_user = users.id_user
    ORDER BY pesanan.tanggal DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Transaksi - Kafe Filosofi</title>

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

            <h2>Data Transaksi</h2>

            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Pelanggan</th>
                            <th>Kasir</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $no = 1;

                        while ($data = mysqli_fetch_assoc($query)) {

                            ?>

                            <tr>

                                <td>
                                    <?php echo $no++; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($data['nama_pelanggan']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($data['nama_kasir']); ?>
                                </td>

                                <td>
                                    Rp <?php echo number_format($data['total'], 0, ',', '.'); ?>
                                </td>

                                <td>
                                    <?php echo $data['status']; ?>
                                </td>

                                <td>
                                    <?php echo date('d-m-Y H:i', strtotime($data['tanggal'])); ?>
                                </td>

                                <td>

                                    <a href="detail_transaksi.php?id=<?php echo $data['id_pesanan']; ?>" class="btn-edit">
                                        Detail
                                    </a>

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