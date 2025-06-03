<?php
session_start();

include 'config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $user_type = 'member'; 

    $sql_check = "SELECT username, email, phone FROM users WHERE username = ? OR email = ? OR phone = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("sss", $username, $email, $phone);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows > 0) {
        $row = $result_check->fetch_assoc();
        if ($username === $row['username']) {
            $error_message = "Username sudah digunakan.";
        } elseif ($email === $row['email']) {
            $error_message = "Email sudah digunakan.";
        } elseif ($phone === $row['phone']) {
            $error_message = "Nomor telepon sudah digunakan.";
        }
    } else {
        if ($password === $confirm_password) {
            $sql = "INSERT INTO users (username, password, name, email, phone, user_type) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $username, $password, $name, $email, $phone, $user_type);

            if ($stmt->execute()) {
                header("Location: home.html");
                exit;
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        } else {
            $error_message = "Password dan Konfirmasi Password tidak cocok.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>Register</h2>
        <?php if (isset($error_message)) { ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php } ?>
        <form method="post" action="register.php">
            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone">Nomor Telepon:</label>
                <input type="text" id="phone" name="phone" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn">Register</button>
            </div>
        </form>
        <p>Sudah punya akun? <a href="index.php">Login di sini</a>.</p>
    </div>
</body>
</html>
