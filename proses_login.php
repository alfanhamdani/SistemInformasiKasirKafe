<?php

session_start();

include "config/koneksi.php";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

$sql = "SELECT * FROM users 
        WHERE username = ? 
        AND password = MD5(?) 
        AND role = ? 
        LIMIT 1";

$stmt = mysqli_prepare($koneksi, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "sss",
    $username,
    $password,
    $role
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {

    $user = mysqli_fetch_assoc($result);

    if ($user['status'] != 'aktif') {
        header("Location: login.php?role=$role&nonaktif=1");
        exit;
    }

    $_SESSION['login'] = true;
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['nama'] = $user['nama'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['role'] = $user['role'];

    if ($role == 'kasir') {
        header("Location: dashboard_kasir.php");
        exit;
    }

    if ($role == 'barista') {
        header("Location: dashboard_barista.php");
        exit;
    }

    if ($role == 'manajer') {
        header("Location: dashboard_manajer.php");
        exit;
    }

} else {

    header("Location: login.php?role=$role&error=1");
    exit;

}
?>