<?php
$servername = "localhost: 3306";
$username = "root";
$password = "";
$dbname = "jasa_pengantaran_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>