<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}

$query = mysqli_query($koneksi, "
    SELECT *
    FROM users
    ORDER BY id_user DESC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Karyawan - Kafe Filosofi</title>

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

            <div class="content-title">

                <h2>Kelola Karyawan</h2>

                <a href="tambah_karyawan.php" class="add-button">
                    + Tambah Karyawan
                </a>

            </div>


            <div class="table-container">

                <table>

                    <thead>

                        <tr>

                            <th>No</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        <?php

                        $no = 1;

                        while ($user = mysqli_fetch_assoc($query)) {

                            ?>

                            <tr>

                                <td><?php echo $no++; ?></td>

                                <td>
                                    <?php echo htmlspecialchars($user['nama']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($user['username']); ?>
                                </td>

                                <td>
                                    <?php echo $user['role']; ?>
                                </td>

                                <td>
                                    <?php echo $user['status']; ?>
                                </td>

                                <td>

                                    <a href="edit_karyawan.php?id=<?php echo $user['id_user']; ?>" class="btn-edit">
                                        Edit
                                    </a>

                                    <a href="hapus_karyawan.php?id=<?php echo $user['id_user']; ?>" class="btn-delete"
                                        onclick="return confirm('Yakin ingin menghapus karyawan ini?')">
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