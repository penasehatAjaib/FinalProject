<?php
session_start();
include 'config.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: index.php");
    exit;
}

// Ambil data pengguna dari sesi
$user = isset($_SESSION['user']) ? $_SESSION['user'] : ['name' => '', 'email' => '', 'phone' => ''];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(isset($_POST['update_profile'])){ // Jika form edit profil disubmit
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $user_id = $_SESSION['user_id'];

        // Persiapkan SQL untuk mengupdate nama dan nomor telepon
        $sql = "UPDATE users SET name = ?, phone = ? WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssi", $name, $phone, $user_id);

            if ($stmt->execute()) {
                // Update data di sesi
                $_SESSION['user']['name'] = $name;
                $_SESSION['user']['phone'] = $phone;
                header('Location: my_account_admin.php');
                exit;
            } else {
                echo "Error updating record: " . $conn->error;
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }

    if(isset($_POST['change_password'])){ // Jika form penggantian password disubmit
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $user_id = $_SESSION['user_id'];

        // Periksa kata sandi saat ini
        $sql_check_password = "SELECT password FROM users WHERE id = ?";
        $stmt_check_password = $conn->prepare($sql_check_password);
        $stmt_check_password->bind_param("i", $user_id);
        $stmt_check_password->execute();
        $result_check_password = $stmt_check_password->get_result();

        if ($result_check_password->num_rows == 1) {
            $row = $result_check_password->fetch_assoc();
            if ($current_password === $row['password']) { 
                if ($new_password === $confirm_password) {
                    $sql_update_password = "UPDATE users SET password = ? WHERE id = ?";
                    $stmt_update_password = $conn->prepare($sql_update_password);
                    $stmt_update_password->bind_param("si", $new_password, $user_id);
        
                    if ($stmt_update_password->execute()) {
                        $success_message = "Kata sandi berhasil diubah.";
                    } else {
                        $error_message = "Error updating record: " . $conn->error;
                    }
                } else {
                    $error_message = "Kata sandi baru tidak cocok.";
                }
            } else {
                $error_message = "Kata sandi saat ini salah.";
            }
        } else {
            $error_message = "User tidak ditemukan di database.";
        }        

        // Tutup pernyataan
        $stmt_check_password->close();
        if (isset($stmt_update_password)) {
            $stmt_update_password->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil dan Ubah Kata Sandi</title>
    <link rel="stylesheet" href="../css/style.css">
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
    
    <div class="my-account">
        <h2>Edit Profil</h2>
        <?php
        if (isset($success_message)) {
            echo "<p class='success-message'>$success_message</p>";
        }
        if (isset($error_message)) {
            echo "<p class='error-message'>$error_message</p>";
        }
        ?>
        <form method="post">
            <div class="account-section">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="account-section">
                <label for="phone">Nomor Telepon:</label>
                <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="account-section">
                <button type="submit" class="btn" name="update_profile">Simpan Perubahan</button>
            </div>
        </form>

        <h2>Ubah Kata Sandi</h2>
        <form method="post">
        <div class="account-section">
            <label for="current_password">Kata Sandi Saat Ini:</label>
            <input type="password" id="current_password" name="current_password" required>
        </div>
        <div class="account-section">
            <label for="new_password">Kata Sandi Baru:</label>
            <input type="password" id="new_password" name="new_password" required>
        </div>
        <div class="account-section">
            <label for="confirm_password">Konfirmasi Kata Sandi Baru:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
        </div>
        <div class="account-section">
            <button type="submit" class="btn" name="change_password">Ubah Kata Sandi</button>
        </div>
        </body>
</html>