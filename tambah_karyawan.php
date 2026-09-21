<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];

    $query = mysqli_prepare($koneksi, "
        INSERT INTO users
        (nama, username, password, role, status)
        VALUES (?, ?, ?, ?, 'aktif')
    ");

    mysqli_stmt_bind_param(
        $query,
        "ssss",
        $nama,
        $username,
        $password,
        $role
    );

    mysqli_stmt_execute($query);

    header("Location: kelola_karyawan.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>Tambah Karyawan</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="form-page">

        <div class="form-box">

            <h2>Tambah Karyawan</h2>

            <form method="POST">

                <label>Nama</label>

                <input type="text" name="nama" required>

                <label>Username</label>

                <input type="text" name="username" required>

                <label>Password</label>

                <input type="password" name="password" required>

                <label>Role</label>

                <select name="role" required>

                    <option value="kasir">
                        Kasir
                    </option>

                    <option value="barista">
                        Barista
                    </option>

                </select>

                <button type="submit">
                    Simpan
                </button>

            </form>

            <a href="kelola_karyawan.php">
                ← Kembali
            </a>

        </div>

    </div>

</body>

</html>