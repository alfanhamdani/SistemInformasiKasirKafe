<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}

$query = mysqli_query($koneksi, "
    SELECT *
    FROM menu
    ORDER BY id_menu DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Menu - Kafe Filosofi</title>

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

            <h2>Kelola Menu</h2>

            <div class="add-menu-box">

                <h3>Tambah Menu Baru</h3>

                <form action="tambah_menu.php" method="POST">

                    <div class="menu-form">

                        <div>
                            <label>Nama Menu</label>

                            <input type="text" name="nama_menu" placeholder="Contoh: Cold Brew" required>
                        </div>


                        <div>
                            <label>Harga</label>

                            <input type="number" name="harga" placeholder="25000" required>
                        </div>


                        <button type="submit">
                            + Tambah
                        </button>

                    </div>

                </form>

            </div>


            <div class="table-container">

                <table>

                    <thead>

                        <tr>
                            <th>No</th>
                            <th>Nama Menu</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $no = 1;

                        while ($menu = mysqli_fetch_assoc($query)) {

                            ?>

                            <tr>

                                <td>
                                    <?php echo $no++; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($menu['nama_menu']); ?>
                                </td>

                                <td>
                                    Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?>
                                </td>

                                <td>
                                    <?php echo $menu['status']; ?>
                                </td>

                                <td>

                                    <a href="edit_menu.php?id=<?php echo $menu['id_menu']; ?>" class="btn-edit">
                                        Edit
                                    </a>

                                    <a href="hapus_menu.php?id=<?php echo $menu['id_menu']; ?>" class="btn-delete"
                                        onclick="return confirm('Yakin ingin menghapus menu ini?')">
                                        ×
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