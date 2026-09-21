<?php

session_start();

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kafe Filosofi - Manajer</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="manager-container">

        <div class="manager-header">

            <h1>Kafe Filosofi - Manajer</h1>

            <a href="index.php" class="kembali">
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


        <div class="manager-welcome">

            <h2>Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?></h2>

            <p>
                Kelola data dan aktivitas Kafe Filosofi melalui menu di atas.
            </p>

        </div>


        <div class="manager-cards">

            <a href="kelola_menu.php" class="manager-card">

                <div class="manager-card-icon">
                    ☕
                </div>

                <h3>Kelola Menu</h3>

                <p>
                    Tambah, edit, dan hapus menu minuman.
                </p>

            </a>


            <a href="data_transaksi.php" class="manager-card">

                <div class="manager-card-icon">
                    📋
                </div>

                <h3>Data Transaksi</h3>

                <p>
                    Melihat seluruh transaksi pelanggan.
                </p>

            </a>


            <a href="kelola_karyawan.php" class="manager-card">

                <div class="manager-card-icon">
                    👥
                </div>

                <h3>Kelola Karyawan</h3>

                <p>
                    Mengelola akun kasir dan barista.
                </p>

            </a>


            <a href="laporan.php" class="manager-card">

                <div class="manager-card-icon">
                    📊
                </div>

                <h3>Laporan</h3>

                <p>
                    Melihat ringkasan transaksi dan pendapatan.
                </p>

            </a>

        </div>

    </div>

</body>

</html>