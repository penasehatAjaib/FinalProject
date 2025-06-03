<?php
session_start();
include 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'];
$sql = "SELECT name, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Pengguna tidak ditemukan.";
    exit;
}

// Ambil data riwayat pengiriman pengguna dari database
$sql_shipments = "SELECT tracking_number, status FROM shipments WHERE user_id = ?";
$stmt_shipments = $conn->prepare($sql_shipments);
$stmt_shipments->bind_param("i", $user_id);
$stmt_shipments->execute();
$result_shipments = $stmt_shipments->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, username, password, user_type FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];
            header("Location: my_account.php");
            exit;
        } else {
            $error_message = "Password salah.";
        }
    } else {
        $error_message = "Username tidak ditemukan.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Saya</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class='konten'>
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
    
    <div class="my-account">
        <h2>Akun Saya</h2>
        <div class="account-section">
            <h3>Profil Pengguna</h3>
            <p>Nama: <?php echo htmlspecialchars($user['name']); ?></p>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <p>Nomor Telepon: <?php echo htmlspecialchars($user['phone']); ?></p>
            <a href="edit_profile_admin.php" class="btn">Edit Profil</a>
        </div>
        <div class="account-section">
            <p><a href="list_account.php" class="btn">Manajemen Akun</a></p>
        </div>
        <div class="account-section">
            <p><a href="index.php" class="btn">Keluar</a></p>
        </div>
        </div>
    </div>

    <footer class="footer">
        <p>&copy; 2024 Pelican Expedition. All rights reserved.</p>
    </footer>
</body>
</html>
