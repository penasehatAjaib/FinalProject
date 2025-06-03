<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Periksa apakah sesi pengguna telah diinisialisasi dengan benar
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['user']['id'])) {
        $user_id = $_SESSION['user']['id'];

        // Periksa kata sandi saat ini
        $sql = "SELECT password FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Periksa apakah baris dikembalikan
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($current_password, $row['password'])) {
                if ($new_password === $confirm_password) {
                    // Hash kata sandi baru
                    $new_password_hashed = password_hash($new_password, PASSWORD_DEFAULT);

                    // Perbarui kata sandi dalam database
                    $sql_update_password = "UPDATE users SET password = ? WHERE id = ?";
                    $stmt_update_password = $conn->prepare($sql_update_password);
                    $stmt_update_password->bind_param("si", $new_password_hashed, $user_id);

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
        $stmt->close();
        if (isset($stmt_update_password)) {
            $stmt_update_password->close();
        }
    } else {
        $error_message = "Sesi pengguna tidak valid.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Kata Sandi</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="konten">
    <div class="my-account">
        <h2>Ubah Kata Sandi</h2>
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
                <button type="submit" class="btn">Ubah Kata Sandi</button>
            </div>
        </form>
    </div>
</body>
</html>
