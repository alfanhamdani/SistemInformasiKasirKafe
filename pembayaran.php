<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'kasir') {
    header("Location: index.php");
    exit;
}

$id_pesanan = $_GET['id_pesanan'] ?? 0;

$id_pesanan = intval($id_pesanan);

$query = mysqli_prepare($koneksi, "
    SELECT 
        pesanan.id_pesanan,
        pesanan.nama_pelanggan,
        pesanan.total,
        pesanan.status,
        users.nama AS nama_kasir
    FROM pesanan
    INNER JOIN users 
        ON pesanan.id_user = users.id_user
    WHERE pesanan.id_pesanan = ?
    LIMIT 1
");

mysqli_stmt_bind_param(
    $query,
    "i",
    $id_pesanan
);

mysqli_stmt_execute($query);

$result = mysqli_stmt_get_result($query);

$pesanan = mysqli_fetch_assoc($result);

if (!$pesanan) {
    header("Location: dashboard_kasir.php");
    exit;
}

$total = $pesanan['total'];

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Proses Pembayaran - Kafe Filosofi</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="payment-container">

        <div class="payment-header">

            <h1>Proses Pembayaran</h1>

        </div>


        <div class="payment-box">

            <div class="payment-total">

                <p>Total Pembayaran</p>

                <h2>
                    Rp <?php echo number_format($total, 0, ',', '.'); ?>
                </h2>

            </div>


            <form action="proses_pembayaran.php" method="POST">

                <input type="hidden" name="id_pesanan" value="<?php echo $id_pesanan; ?>">

                <input type="hidden" name="total" value="<?php echo $total; ?>">


                <label class="payment-label">
                    Metode Pembayaran
                </label>


                <div class="payment-methods">

                    <label class="payment-method">

                        <input type="radio" name="metode_pembayaran" value="cash" onchange="pilihMetode('cash')"
                            required>

                        <span>💵 CASH</span>

                    </label>


                    <label class="payment-method">

                        <input type="radio" name="metode_pembayaran" value="qris" onchange="pilihMetode('qris')">

                        <span>📱 QRIS</span>

                    </label>


                    <label class="payment-method">

                        <input type="radio" name="metode_pembayaran" value="e-wallet"
                            onchange="pilihMetode('e-wallet')">

                        <span>💳 E-Wallet</span>

                    </label>

                </div>


                <div id="cash-section" class="cash-section">

                    <label>
                        Uang Dibayar
                    </label>

                    <input type="number" name="jumlah_bayar" id="jumlah_bayar" placeholder="Masukkan uang pelanggan"
                        min="<?php echo $total; ?>" oninput="hitungKembalian()">

                    <div class="kembalian">

                        <span>Kembalian</span>

                        <strong id="kembalian">
                            Rp 0
                        </strong>

                    </div>

                </div>


                <div class="payment-buttons">

                    <a href="dashboard_kasir.php" class="payment-back">
                        ← Kembali
                    </a>

                    <button type="submit" class="payment-process">
                        Proses Pembayaran
                    </button>

                </div>

            </form>

        </div>

    </div>


    <script>

        const total = <?php echo $total; ?>;

        function pilihMetode(metode) {

            const cashSection =
                document.getElementById("cash-section");

            const jumlahBayar =
                document.getElementById("jumlah_bayar");


            if (metode === "cash") {

                cashSection.style.display = "block";

                jumlahBayar.required = true;

            } else {

                cashSection.style.display = "none";

                jumlahBayar.required = false;

                jumlahBayar.value = "";

                document.getElementById("kembalian").innerText =
                    "Rp 0";

            }

        }


        function hitungKembalian() {

            const bayar =
                parseFloat(
                    document.getElementById("jumlah_bayar").value
                ) || 0;


            let kembalian = bayar - total;


            if (kembalian < 0) {

                kembalian = 0;

            }


            document.getElementById("kembalian").innerText =
                "Rp " + kembalian.toLocaleString("id-ID");

        }

    </script>

</body>

</html>