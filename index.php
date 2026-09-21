<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kafe Filosofi</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container">

        <div class="header">
            <h1>Kafe Filosofi</h1>
            <p>Sistem Pemesanan Cafe</p>
        </div>

        <div class="menu-container">

            <a href="login.php?role=kasir" class="menu-card">
                <div class="icon">👩‍💻</div>
                <h3>Kasir</h3>
                <p>Input pesanan & pembayaran</p>
            </a>

            <a href="login.php?role=barista" class="menu-card">
                <div class="icon">🧑‍🍳</div>
                <h3>Barista</h3>
                <p>Kelola pesanan kopi</p>
            </a>

            <a href="login.php?role=manajer" class="menu-card">
                <div class="icon">💼</div>
                <h3>Manajer</h3>
                <p>Kelola data & laporan</p>
            </a>

        </div>

    </div>

</body>

</html>