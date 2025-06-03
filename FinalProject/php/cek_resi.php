<?php
session_start();
include 'config.php';

$tracking_number = "";
$status = "";
$shipment_date = "";
$sender = "";
$receiver = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tracking_number = $_POST['tracking_number'];

    // Buat SQL untuk mencari data berdasarkan nomor resi
    $sql = "SELECT tracking_number, status, shipment_date, sender, receiver FROM shipments WHERE tracking_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tracking_number);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($tracking_number, $status, $shipment_date, $sender, $receiver);
        $stmt->fetch();
    } else {
        $error_message = "Nomor resi tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelican Expedition - Cek Resi</title>
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
        <div id="introduce">
            <h1>Hasil Pencarian Resi</h1>
            <div class="cek-resi-form">
                <form method="post" action="cek_resi.php">
                    <input type="text" name="tracking_number" placeholder="Masukkan Nomor Resi" required>
                    <button type="submit">Cek Resi</button>
                </form>
            </div>
            <?php
            if (!empty($error_message)) {
                echo "<p class='error-message'>$error_message</p>";
            } else if ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo "<div class='shipment-details'>";
                echo "<p><strong>Nomor Resi:</strong> $tracking_number</p>";
                echo "<p><strong>Status:</strong> $status</p>";
                echo "<p><strong>Tanggal Pengiriman:</strong> $shipment_date</p>";
                echo "<p><strong>Pengirim:</strong> $sender</p>";
                echo "<p><strong>Penerima:</strong> $receiver</p>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Pelican Expedition. All rights reserved.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
