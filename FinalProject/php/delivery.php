<?php
session_start();
include 'config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $nama_pengirim = $_POST['nama_pengirim'];
    $alamat_asal = $_POST['alamat_asal'];
    $nama_penerima = $_POST['nama_penerima'];
    $alamat_tujuan = $_POST['alamat_tujuan'];
    $layanan = $_POST['layanan'];

    $user_id = $_SESSION['user_id'];
    $tracking_number = uniqid('TRACK'); 
    $status = 'Pending'; 
    $shipment_date = date('Y-m-d H:i:s'); 

    $sql = "INSERT INTO shipments (user_id, tracking_number, status, shipment_date, sender, sender_address, receiver, receiver_address, service) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssss", $user_id, $tracking_number, $status, $shipment_date, $nama_pengirim, $alamat_asal, $nama_penerima, $alamat_tujuan, $layanan);

    if ($stmt->execute()) {
        $success_message = "Data pengiriman berhasil disimpan.";
    } else {
        $error_message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery - Pelican Expedition</title>
    <link rel="stylesheet" href="../css/style.css"/>
</head>
<body class="konten">
    <header class="main-menu">
        <div class="container">
            <img src="../img/logo.png" alt="Pelican Expedition Logo" class="logo">
        </div>
        <nav>
            <ul>
                <li><a href="../home.html">Home</a></li>
                <li><a href="../gallery.html">Gallery</a></li>
                <li><a href="../delivery.html">Delivery</a></li>
                <li><a href="cek_resi.php">Cek Resi</a></li>
                <li><a href="../contact.html">About Us</a></li>
                <li><a href="my_account.php">My Account</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-content">
        <h1>Masukkan pengiriman yang ingin dilakukan</h1>
        <div class="form-container">
            <?php
            if (isset($success_message)) {
                echo "<p class='success-message'>$success_message</p>";
            }
            if (isset($error_message)) {
                echo "<p class='error-message'>$error_message</p>";
            }
            ?>
            <form action="delivery.php" method="POST">
                <label for="nama_pengirim">Nama Pengirim</label><br>
                <input type="text" id="nama_pengirim" name="nama_pengirim" required><br>
                <label for="alamat_asal">Alamat Asal</label><br>
                <input type="text" id="alamat_asal" name="alamat_asal" required><br>
                <label for="nama_penerima">Nama Penerima</label><br>
                <input type="text" id="nama_penerima" name="nama_penerima" required><br>
                <label for="alamat_tujuan">Alamat Tujuan</label><br>
                <input type="text" id="alamat_tujuan" name="alamat_tujuan" required><br>
                <label for="layanan">Pilihan Layanan</label><br>
                <select id="layanan" name="layanan" required>
                    <option value="">~ Jasa Pelayanan ~</option>
                    <option value="Biasa">Biasa</option>
                    <option value="Express">Express</option>
                    <option value="Kilat">Kilat</option>
                </select>
                <br><br>
                <button type="submit" class="submit">Selesai</button>
            </form>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Pelican Expedition. All rights reserved.</p>
    </footer>    
</body>
</html>