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
    FROM menu
    WHERE id_menu = ?
");

mysqli_stmt_bind_param($query, "i", $id);
mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);
$menu = mysqli_fetch_assoc($result);

if (!$menu) {
    header("Location: kelola_menu.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $status = $_POST['status'];

    $update = mysqli_prepare($koneksi, "
        UPDATE menu
        SET nama_menu = ?, harga = ?, status = ?
        WHERE id_menu = ?
    ");

    mysqli_stmt_bind_param(
        $update,
        "sdsi",
        $nama_menu,
        $harga,
        $status,
        $id
    );

    mysqli_stmt_execute($update);

    header("Location: kelola_menu.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Menu - Kafe Filosofi</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="form-page">

        <div class="form-box">

            <h2>Edit Menu</h2>

            <form method="POST">

                <label>Nama Menu</label>

                <input type="text" name="nama_menu" value="<?php echo htmlspecialchars($menu['nama_menu']); ?>"
                    required>

                <label>Harga</label>

                <input type="number" name="harga" value="<?php echo $menu['harga']; ?>" required>

                <label>Status</label>

                <select name="status">

                    <option value="tersedia" <?php echo $menu['status'] == 'tersedia' ? 'selected' : ''; ?>>
                        Tersedia
                    </option>

                    <option value="tidak tersedia" <?php echo $menu['status'] == 'tidak tersedia' ? 'selected' : ''; ?>>
                        Tidak Tersedia
                    </option>

                </select>

                <button type="submit">
                    Simpan Perubahan
                </button>

            </form>

            <a href="kelola_menu.php">
                ← Kembali
            </a>

        </div>

    </div>

</body>

</html>