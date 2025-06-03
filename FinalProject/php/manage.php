<?php
include 'config.php';

$action = isset($_POST['action']) ? $_POST['action'] : '';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tracking_number = $_POST['tracking_number'];

    if ($action == 'search') {
        $sql = "SELECT * FROM shipments WHERE tracking_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $tracking_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $shipment = $result->fetch_assoc();
        } else {
            $error = "Nomor resi tidak ditemukan.";
        }
    } elseif ($action == 'add') {
        $status = $_POST['status'];
        $shipment_date = $_POST['shipment_date'];
        $sender = $_POST['sender'];
        $receiver = $_POST['receiver'];

        $sql = "INSERT INTO shipments (tracking_number, status, shipment_date, sender, receiver) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $tracking_number, $status, $shipment_date, $sender, $receiver);

        if ($stmt->execute()) {
            $message = "Data berhasil ditambahkan.";
        } else {
            $error = "Gagal menambahkan data.";
        }
    } elseif ($action == 'update') {
        $status = $_POST['status'];
        $shipment_date = $_POST['shipment_date'];
        $sender = $_POST['sender'];
        $receiver = $_POST['receiver'];

        $sql = "UPDATE shipments SET status = ?, shipment_date = ?, sender = ?, receiver = ? WHERE tracking_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $status, $shipment_date, $sender, $receiver, $tracking_number);

        if ($stmt->execute()) {
            $message = "Data berhasil diperbarui.";
        } else {
            $error = "Gagal memperbarui data.";
        }
    } elseif ($action == 'delete') {
        $sql = "DELETE FROM shipments WHERE tracking_number = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $tracking_number);

        if ($stmt->execute()) {
            $message = "Data berhasil dihapus.";
        } else {
            $error = "Gagal menghapus data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelican Expedition - Cek Resi</title>
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body>
    <header class="main-menu">
        <div class="container">
            <img src="img/logo.png" alt="Pelican Expedition Logo" class="logo">
        </div>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="delivery.html">Delivery</a></li>
                <li><a href="cek_resi.html">Cek Resi</a></li>
                <li><a href="contact.html">About Us</a></li>
            </ul>
        </nav>
    </header>

    <div id="introduce">
        <h1>Hasil Pencarian Resi</h1>
        <?php if (isset($shipment)) { ?>
            <div class="shipment-details">
                <p><strong>Nomor Resi:</strong> <?php echo $shipment['tracking_number']; ?></p>
                <p><strong>Status:</strong> <?php echo $shipment['status']; ?></p>
                <p><strong>Tanggal Pengiriman:</strong> <?php echo $shipment['shipment_date']; ?></p>
                <p><strong>Pengirim:</strong> <?php echo $shipment['sender']; ?></p>
                <p><strong>Penerima:</strong> <?php echo $shipment['receiver']; ?></p>
            </div>
        <?php } elseif (isset($error)) { ?>
            <p><?php echo $error; ?></p>
        <?php } elseif (isset($message)) { ?>
            <p><?php echo $message; ?></p>
        <?php } ?>
    </div>

    <div id="form">
        <h2>Tambah/Edit/Hapus Data Resi</h2>
        <form method="post" action="">
            <input type="hidden" name="action" value="search">
            <label for="tracking_number">Nomor Resi:</label>
            <input type="text" id="tracking_number" name="tracking_number" required>
            <button type="submit">Cari</button>
        </form>

        <form method="post" action="">
            <input type="hidden" name="action" value="add">
            <label for="tracking_number">Nomor Resi:</label>
            <input type="text" id="tracking_number" name="tracking_number" required>
            <label for="status">Status:</label>
            <input type="text" id="status" name="status" required>
            <label for="shipment_date">Tanggal Pengiriman:</label>
            <input type="date" id="shipment_date" name="shipment_date" required>
            <label for="sender">Pengirim:</label>
            <input type="text" id="sender" name="sender" required>
            <label for="receiver">Penerima:</label>
            <input type="text" id="receiver" name="receiver" required>
            <button type="submit">Tambah</button>
        </form>

        <form method="post" action="">
            <input type="hidden" name="action" value="update">
            <label for="tracking_number">Nomor Resi:</label>
            <input type="text" id="tracking_number" name="tracking_number" required>
            <label for="status">Status:</label>
            <input type="text" id="status" name="status" required>
            <label for="shipment_date">Tanggal Pengiriman:</label>
            <input type="date" id="shipment_date" name="shipment_date" required>
            <label for="sender">Pengirim:</label>
            <input type="text" id="sender" name="sender" required>
            <label for="receiver">Penerima:</label>
            <input type="text" id="receiver" name="receiver" required>
            <button type="submit">Update</button>
        </form>

        <form method="post" action="">
            <input type="hidden" name="action" value="delete">
            <label for="tracking_number">Nomor Resi:</label>
            <input type="text" id="tracking_number" name="tracking_number" required>
            <button type="submit">Hapus</button>
        </form>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
