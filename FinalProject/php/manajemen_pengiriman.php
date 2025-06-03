<?php
session_start();
require 'config.php';

// Fungsi untuk menghapus pengiriman berdasarkan ID
function hapusPengiriman($conn, $id) {
    $sql = "DELETE FROM shipments WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Jika tombol hapus diklik
if(isset($_POST['hapus'])) {
    $id_to_delete = $_POST['id_to_delete'];
    hapusPengiriman($conn, $id_to_delete);
    // Redirect atau refresh halaman setelah penghapusan
    header("Location: manajemen_pengiriman.php");
}

// Ambil data pengiriman dari database
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
    <link rel="stylesheet" href="../css/style.css"/>
    <style>
        /* CSS untuk memberikan garis pada tabel */
        .shipment-table {
            border-collapse: collapse;
            width: 100%;
        }
        .shipment-table th, .shipment-table td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }
        .shipment-table th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body class="konten">
    <header class="main-menu">
        <div class="container">
            <img src="../img/logo.png" alt="Pelican Expedition Logo" class="logo">
        </div>
        <nav>
            <ul>
                <li><a href="../home_admin.html">Home</a></li>
                <li><a href="../gallery_admin.html">Gallery</a></li>
                <li><a href="manajemen_pengiriman.php">Manajemen</a></li>
                <li><a href="../contact_admin.html">About Us</a></li>
                <li><a href="my_account_admin.php">My Account</a></li>
            </ul>
        </nav>
    </header>
    
    <div class="main-content">
        <h1>Manajemen Pengiriman</h1>
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
                    <th>Hapus</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Tampilkan data pengiriman dalam tabel
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
                        // Tambahkan form untuk hapus pengiriman
                        echo "<td>
                            <form method='post' action='manajemen_pengiriman.php'>
                                <input type='hidden' name='id_to_delete' value='" . $row['id'] . "'>
                                <input type='submit' name='hapus' value='Hapus'>
                            </form>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='11'>Tidak ada data pengiriman.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Pelican Expedition. All rights reserved.</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
