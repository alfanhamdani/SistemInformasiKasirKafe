<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'manajer') {
    header("Location: index.php");
    exit;
}

$id = intval($_GET['id'] ?? 0);

$query = mysqli_prepare($koneksi, "
    SELECT *
    FROM users
    WHERE id_user = ?
");

mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);

$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: kelola_karyawan.php");
    exit;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $role = $_POST['role'];
    $status = $_POST['status'];

    if ($_POST['password'] != '') {

        $password = md5($_POST['password']);

        $update = mysqli_prepare($koneksi, "
            UPDATE users
            SET nama = ?, username = ?, password = ?, role = ?, status = ?
            WHERE id_user = ?
        ");

        mysqli_stmt_bind_param(
            $update,
            "sssssi",
            $nama,
            $username,
            $password,
            $role,
            $status,
            $id
        );

    } else {

        $update = mysqli_prepare($koneksi, "
            UPDATE users
            SET nama = ?, username = ?, role = ?, status = ?
            WHERE id_user = ?
        ");

        mysqli_stmt_bind_param(
            $update,
            "ssssi",
            $nama,
            $username,
            $role,
            $status,
            $id
        );

    }

    mysqli_stmt_execute($update);

    header("Location: kelola_karyawan.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Karyawan</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="form-page">

        <div class="form-box">

            <h2>Edit Karyawan</h2>

            <form method="POST">

                <label>Nama</label>

                <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required>

                <label>Username</label>

                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

                <label>Password Baru</label>

                <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">

                <label>Role</label>

                <select name="role">

                    <option value="kasir" <?php echo $user['role'] == 'kasir' ? 'selected' : ''; ?>
                        >
                        Kasir
                    </option>

                    <option value="barista" <?php echo $user['role'] == 'barista' ? 'selected' : ''; ?>
                        >
                        Barista
                    </option>

                </select>

                <label>Status</label>

                <select name="status">

                    <option value="aktif" <?php echo $user['status'] == 'aktif' ? 'selected' : ''; ?>
                        >
                        Aktif
                    </option>

                    <option value="nonaktif" <?php echo $user['status'] == 'nonaktif' ? 'selected' : ''; ?>
                        >
                        Nonaktif
                    </option>

                </select>

                <button type="submit">
                    Simpan Perubahan
                </button>

            </form>

            <a href="kelola_karyawan.php">
                ← Kembali
            </a>

        </div>

    </div>

</body>

</html>