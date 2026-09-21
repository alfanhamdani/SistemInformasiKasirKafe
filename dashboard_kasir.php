<?php

session_start();

include "config/koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] != 'kasir') {
    header("Location: index.php");
    exit;
}

$query_menu = mysqli_query($koneksi, "
    SELECT * FROM menu
    WHERE status = 'tersedia'
    ORDER BY id_menu ASC
");

$query_kasir = mysqli_query($koneksi, "
    SELECT id_user, nama
    FROM users
    WHERE role = 'kasir'
    AND status = 'aktif'
    ORDER BY nama ASC
");

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kafe Filosofi - Kasir</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="kasir-container">

    <div class="kasir-header">

        <h1>Kafe Filosofi - Kasir</h1>

        <a href="index.php" class="kembali">
            ← Kembali
        </a>

    </div>


    <div class="kasir-content">


        <div class="kasir-left">


            <!-- DATA PESANAN -->

            <div class="kasir-box">

                <h3>Data Pesanan</h3>

                <div class="form-row">

                    <div class="form-item">

                        <label>Nama Kasir</label>

                        <select id="kasir">

                            <?php while ($kasir = mysqli_fetch_assoc($query_kasir)) { ?>

                                <option 
                                    value="<?php echo $kasir['id_user']; ?>"
                                    <?php echo ($kasir['id_user'] == $_SESSION['id_user']) ? 'selected' : ''; ?>
                                >
                                    <?php echo htmlspecialchars($kasir['nama']); ?>
                                </option>

                            <?php } ?>

                        </select>

                    </div>


                    <div class="form-item">

                        <label>Nama Pelanggan</label>

                        <input 
                            type="text"
                            id="nama_pelanggan"
                            placeholder="Masukkan nama pelanggan"
                        >

                    </div>

                </div>

            </div>


            <!-- DAFTAR MENU -->

            <div class="kasir-box menu-box">

                <h3>Daftar Menu</h3>

                <div class="menu-list">

                    <?php while ($menu = mysqli_fetch_assoc($query_menu)) { ?>

                        <button
                            type="button"
                            class="menu-item"
                            onclick="tambahMenu(
                                <?php echo $menu['id_menu']; ?>,
                                '<?php echo htmlspecialchars($menu['nama_menu'], ENT_QUOTES); ?>',
                                <?php echo $menu['harga']; ?>
                            )"
                        >

                            <span class="nama-menu">
                                <?php echo htmlspecialchars($menu['nama_menu']); ?>
                            </span>

                            <span class="harga-menu">
                                Rp <?php echo number_format($menu['harga'], 0, ',', '.'); ?>
                            </span>

                        </button>

                    <?php } ?>

                </div>

            </div>


        </div>


        <!-- PESANAN SAAT INI -->

        <div class="pesanan-box">

            <h3>Pesanan Saat Ini</h3>

            <div id="daftar-pesanan">

                <div class="kosong">
                    Belum ada item
                </div>

            </div>


            <div class="total-box">

                <div class="total-row">

                    <strong>TOTAL</strong>

                    <strong id="total">
                        Rp 0
                    </strong>

                </div>

                <button 
                    type="button"
                    class="konfirmasi"
                    onclick="konfirmasiPesanan()"
                >
                    Konfirmasi Pesanan
                </button>

            </div>

        </div>

    </div>

</div>


<script>

let pesanan = [];


function tambahMenu(id, nama, harga) {

    let item = pesanan.find(item => item.id == id);

    if (item) {

        item.jumlah++;

    } else {

        pesanan.push({
            id: id,
            nama: nama,
            harga: harga,
            jumlah: 1
        });

    }

    tampilkanPesanan();

}


function kurangiMenu(id) {

    let item = pesanan.find(item => item.id == id);

    if (!item) {
        return;
    }

    item.jumlah--;

    if (item.jumlah <= 0) {

        pesanan = pesanan.filter(item => item.id != id);

    }

    tampilkanPesanan();

}


function tambahJumlah(id) {

    let item = pesanan.find(item => item.id == id);

    if (item) {

        item.jumlah++;

    }

    tampilkanPesanan();

}


function tampilkanPesanan() {

    const daftar = document.getElementById("daftar-pesanan");

    if (pesanan.length === 0) {

        daftar.innerHTML = `
            <div class="kosong">
                Belum ada item
            </div>
        `;

        document.getElementById("total").innerText = "Rp 0";

        return;
    }


    let html = "";
    let total = 0;


    pesanan.forEach(item => {

        let subtotal = item.harga * item.jumlah;

        total += subtotal;


        html += `

            <div class="pesanan-item">

                <div class="pesanan-info">

                    <strong>${item.nama}</strong>

                    <small>
                        Rp ${item.harga.toLocaleString('id-ID')}
                    </small>

                </div>


                <div class="jumlah-control">

                    <button onclick="kurangiMenu(${item.id})">
                        −
                    </button>

                    <span>
                        ${item.jumlah}
                    </span>

                    <button onclick="tambahJumlah(${item.id})">
                        +
                    </button>

                </div>


                <strong class="subtotal">
                    Rp ${subtotal.toLocaleString('id-ID')}
                </strong>

            </div>

        `;

    });


    daftar.innerHTML = html;


    document.getElementById("total").innerText =
        "Rp " + total.toLocaleString('id-ID');

}


function konfirmasiPesanan() {

    const namaPelanggan =
        document.getElementById("nama_pelanggan").value.trim();


    if (namaPelanggan === "") {

        alert("Nama pelanggan harus diisi!");

        return;

    }


    if (pesanan.length === 0) {

        alert("Silakan pilih menu terlebih dahulu!");

        return;

    }


    let form = document.createElement("form");

    form.method = "POST";

    form.action = "proses_pesanan.php";


    let pelanggan = document.createElement("input");

    pelanggan.type = "hidden";

    pelanggan.name = "nama_pelanggan";

    pelanggan.value = namaPelanggan;

    form.appendChild(pelanggan);


    let dataPesanan = document.createElement("input");

    dataPesanan.type = "hidden";

    dataPesanan.name = "pesanan";

    dataPesanan.value = JSON.stringify(pesanan);

    form.appendChild(dataPesanan);


    document.body.appendChild(form);

    form.submit();

}

</script>

</body>

</html>