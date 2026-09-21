<?php

$role = $_GET['role'] ?? '';

$role_valid = ['kasir', 'barista', 'manajer'];

if (!in_array($role, $role_valid)) {
    header("Location: index.php");
    exit;
}

$nama_role = ucfirst($role);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login <?php echo $nama_role; ?> - Kafe Filosofi</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="login-container">

        <div class="login-box">

            <div class="login-icon">☕</div>

            <h1>Kafe Filosofi</h1>

            <p>Login Sistem <?php echo $nama_role; ?></p>

            <?php if (isset($_GET['error'])) { ?>

                <div class="error">
                    Username atau password salah!
                </div>

            <?php } ?>

            <?php if (isset($_GET['nonaktif'])) { ?>

                <div class="error">
                    Akun sedang tidak aktif!
                </div>

            <?php } ?>

            <form action="proses_login.php" method="POST">

                <input type="hidden" name="role" value="<?php echo $role; ?>">

                <div class="form-group">
                    <label>Username</label>

                    <input type="text" name="username" placeholder="Masukkan username" required>
                </div>

                <div class="form-group">
                    <label>Password</label>

                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>

                <button type="submit" class="login-button">
                    Login
                </button>

            </form>

            <a href="index.php" class="back-button">
                ← Kembali ke Beranda
            </a>

        </div>

    </div>

</body>

</html>