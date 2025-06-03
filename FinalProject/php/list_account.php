<?php
session_start();
require 'config.php';

// Fungsi untuk menghapus pengguna berdasarkan ID
function hapusPengguna($conn, $id) {
    $sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

// Jika tombol hapus diklik
if(isset($_POST['hapus'])) {
    $id_to_delete = $_POST['id_to_delete'];
    hapusPengguna($conn, $id_to_delete);
    // Redirect atau refresh halaman setelah penghapusan
    header("Location: list_account.php");
}

// Ambil data pengguna dari database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pengguna</title>
    <link rel="stylesheet" href="../css/style.css"/>
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
        <h1>Daftar Pengguna</h1>
        <table class="user-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>User Type</th>
                    <th>Hapus</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Tampilkan data pengguna dalam tabel
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['password'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>" . $row['user_type'] . "</td>";
                        // Tambahkan form untuk hapus pengguna
                        echo "<td>
                            <form method='post' action='list_account.php'>
                                <input type='hidden' name='id_to_delete' value='" . $row['id'] . "'>
                                <input type='submit' name='hapus' value='Hapus'>
                            </form>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Tidak ada data pengguna.</td></tr>";
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
