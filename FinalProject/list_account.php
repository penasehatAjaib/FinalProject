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

// Fungsi untuk mengedit pengguna
function editPengguna($conn, $id, $username, $password, $name, $email, $phone, $user_type) {
    $sql = "UPDATE users SET username = ?, password = ?, name = ?, email = ?, phone = ?, user_type = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $username, $password, $name, $email, $phone, $user_type, $id);
    $stmt->execute();
}

// Fungsi untuk menambah pengguna baru
function tambahPengguna($conn, $username, $password, $name, $email, $phone, $user_type) {
    $sql = "INSERT INTO users (username, password, name, email, phone, user_type) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $username, $password, $name, $email, $phone, $user_type);
    $stmt->execute();
}

// Jika tombol hapus diklik
if(isset($_POST['hapus'])) {
    $id_to_delete = $_POST['id_to_delete'];
    hapusPengguna($conn, $id_to_delete);
    header("Location: list_account.php");
}

// Jika tombol edit diklik
if(isset($_POST['edit'])) {
    $id_to_edit = $_POST['id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $user_type = $_POST['user_type'];
    editPengguna($conn, $id_to_edit, $username, $password, $name, $email, $phone, $user_type);
    header("Location: list_account.php");
}

// Jika tombol tambah diklik
if(isset($_POST['tambah'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $user_type = $_POST['user_type'];
    tambahPengguna($conn, $username, $password, $name, $email, $phone, $user_type);
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
    <link rel="stylesheet" href="css/style.css"/>
</head>
<body class="konten">
    <header class="main-menu">
        <div class="container">
            <img src="img/logo.png" alt="Pelican Expedition Logo" class="logo">
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
    
    <div class="main-content">
        <h1>Daftar Pengguna</h1>
        <div class="form-container">
            <h2>Tambah Pengguna</h2>
            <form method="post" action="list_account.php">
                <input type="hidden" name="tambah">
                <label>Username: <input type="text" name="username"label><br>
                <label>Password: <input type="text" name="password"label><br>
                <label>Name: <input type="text" name="name"label><br>
                <label>Email: <input type="text" name="email"label><br>
                <label>Phone: <input type="text" name="phone"label><br>
                <label>User Type: <input type="text" name="user_type"label><br>
                <input type="submit" value="Tambah">
            </form>
        </div>

        <h2>Daftar Pengguna</h2>
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
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['password'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>" . $row['user_type'] . "</td>";
                        // Tambahkan form untuk edit pengguna
                        echo "<td>
                            <form method='post' action='list_account.php'>
                                <input type='hidden' name='id' value='" . $row['id'] . "'>
                                <label>Username: <input type='text' name='username' value='" . $row['username'] . "'></label><br>
                                <label>Password: <input type='text' name='password' value='" . $row['password'] . "'></label><br>
                                <label>Name: <input type='text' name='name' value='" . $row['name'] . "'></label><br>
                                <label>Email: <input type='text' name='email' value='" . $row['email'] . "'></label><br>
                                <label>Phone: <input type='text' name='phone' value='" . $row['phone'] . "'></label><br>
                                <label>User Type: <input type='text' name='user_type' value='" . $row['user_type'] . "'></label><br>
                                <input type='submit' name='edit' value='Edit'>
                            </form>
                        </td>";
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
                    echo "<tr><td colspan='9'>Tidak ada data pengguna.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Pelican Expedition. All rights reserved.</p>
    </footer>

    <script src="script.js"script>
</body>
</html>
