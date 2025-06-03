<?php
session_start();
require 'config.php';

function hapusPengiriman($conn, $id) {
    $sql = "DELETE FROM shipments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

function editPengiriman($conn, $id, $user_id, $tracking_number, $status, $shipment_date, $sender, $receiver, $sender_address, $receiver_address, $service) {
    $sql = "UPDATE shipments SET user_id = ?, tracking_number = ?, status = ?, shipment_date = ?, sender = ?, receiver = ?, sender_address = ?, receiver_address = ?, service = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssssi", $user_id, $tracking_number, $status, $shipment_date, $sender, $receiver, $sender_address, $receiver_address, $service, $id);
    $stmt->execute();
}

function tambahPengiriman($conn, $user_id, $tracking_number, $status, $shipment_date, $sender, $receiver, $sender_address, $receiver_address, $service) {
    $sql = "INSERT INTO shipments (user_id, tracking_number, status, shipment_date, sender, receiver, sender_address, receiver_address, service) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issssssss", $user_id, $tracking_number, $status, $shipment_date, $sender, $receiver, $sender_address, $receiver_address, $service);
    $stmt->execute();
}

if(isset($_POST['hapus'])) {
    $id_to_delete = $_POST['id_to_delete'];
    hapusPengiriman($conn, $id_to_delete);
    header("Location: manajemen_pengiriman.php");
}

if(isset($_POST['edit'])) {
    $id_to_edit = $_POST['id'];
    $user_id = $_POST['user_id'];
    $tracking_number = $_POST['tracking_number'];
    $status = $_POST['status'];
    $shipment_date = $_POST['shipment_date'];
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $sender_address = $_POST['sender_address'];
    $receiver_address = $_POST['receiver_address'];
    $service = $_POST['service'];
    editPengiriman($conn, $id_to_edit, $user_id, $tracking_number, $status, $shipment_date, $sender, $receiver, $sender_address, $receiver_address, $service);
    header("Location: manajemen_pengiriman.php");
}

if(isset($_POST['tambah'])) {
    $user_id = $_POST['user_id'];
    $tracking_number = $_POST['tracking_number'];
    $status = $_POST['status'];
    $shipment_date = $_POST['shipment_date'];
    $sender = $_POST['sender'];
    $receiver = $_POST['receiver'];
    $sender_address = $_POST['sender_address'];
    $receiver_address = $_POST['receiver_address'];
    $service = $_POST['service'];
    tambahPengiriman($conn, $user_id, $tracking_number, $status, $shipment_date, $sender, $receiver, $sender_address, $receiver_address, $service);
    header("Location: manajemen_pengiriman.php");
}

$sql = "SELECT * FROM shipments";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengiriman</title>
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body class="konten">
    <header class="main-menu">
        <div class="container">
            <img src="img/capybara.png" alt="Capybara Expedition Logo" class="logo">
        </div>
        <nav>
            <ul>
                <li><a href="home_admin.php">Home</a></li>
                <li><a href="gallery_admin.php">Gallery</a></li>
                <li><a href="manajemen_pengiriman.php">Manajemen</a></li>
                <li><a href="contact.php">About Us</a></li>
                <li><a href="my_account_admin.php">My Account</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="home">
        <h1>Manajemen Pengiriman</h1>
        <div class="form-container">
            <h2>Tambah Pengiriman</h2>
            <form method="post" action="manajemen_pengiriman.php">
                <input type="hidden" name="tambah">
                <label>User ID: <input type="text" name="user_id"label><br>
                <label>Nomor Resi: <input type="text" name="tracking_number"label><br>
                <label>Status: <input type="text" name="status"label><br>
                <label>Tanggal Pengiriman: <input type="date" name="shipment_date"label><br>
                <label>Pengirim: <input type="text" name="sender"label><br>
                <label>Penerima: <input type="text" name="receiver"label><br>
                <label>Alamat Pengirim: <input type="text" name="sender_address"label><br>
                <label>Alamat Penerima: <input type="text" name="receiver_address"label><br>
                <label>Layanan: <input type="text" name="service"label><br>
                <input type="submit" value="Tambah">
            </form>
        </div>

        <h2>Daftar Pengiriman</h2>
        <table class="shipment-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Nomor Resi</th>
                    <th>Status</th>
                    <th>Tanggal Pengiriman</th>
                    <th>Pengirim</th>
                    <th>Penerima</th>
                    <th>Alamat Pengirim</th>
                    <th>Alamat Penerima</th>
                    <th>Layanan</th>
                    <th>Edit</th>
                    <th>Hapus</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['user_id'] . "</td>";
                        echo "<td>" . $row['tracking_number'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>" . $row['shipment_date'] . "</td>";
                        echo "<td>" . $row['sender'] . "</td>";
                        echo "<td>" . $row['receiver'] . "</td>";
                        echo "<td>" . $row['sender_address'] . "</td>";
                        echo "<td>" . $row['receiver_address'] . "</td>";
                        echo "<td>" . $row['service'] . "</td>";
                        echo "<td>
                            <form method='post' action='manajemen_pengiriman.php'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <label>User ID: <input type='text' name='user_id' value='" . $row['user_id'] . "'></label><br>
                                <label>Nomor Resi: <input type='text' name='tracking_number' value='" . $row['tracking_number'] . "'></label><br>
                                <label>Status: <input type='text' name='status' value='" . $row['status'] . "'></label><br>
                                <label>Tanggal Pengiriman: <input type='date' name='shipment_date' value='" . $row['shipment_date'] . "'></label><br>
                                <label>Pengirim: <input type='text' name='sender' value='" . $row['sender'] . "'></label><br>
                                <label>Penerima: <input type='text' name='receiver' value='" . $row['receiver'] . "'></label><br>
                                <label>Alamat Pengirim: <input type='text' name='sender_address' value='" . $row['sender_address'] . "'></label><br>
                                <label>Alamat Penerima: <input type='text' name='receiver_address' value='" . $row['receiver_address'] . "'></label><br>
                                <label>Layanan: <input type='text' name='service' value='" . $row['service'] . "'></label><br>
                                <input type='submit' name='edit' value='Edit'>
                            </form>
                        </td>";
                        echo "<td>
                            <form method='post' action='manajemen_pengiriman.php'>
                                <input type='hidden' name='id_to_delete' value='" . $row['id'] . "'>
                                <input type='submit' name='hapus' value='Hapus'>
                            </form>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='12'>Tidak ada data pengiriman.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        <p>&copy; 2025 Capybara Expedition. All rights reserved.</p>
    </footer>

    <script src="script.js"script>
</body>
</html>
