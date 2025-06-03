<?php
session_start();

include 'config.php';

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true && isset($_SESSION['user']['id'])) {
    // Fetch shipment data from the database
    $sql = "SELECT * FROM shipments WHERE user_id='" . $_SESSION['user']['id'] . "'";
    $result = $conn->query($sql);

    // Periksa apakah ada hasil dari query
    if ($result && $result->num_rows > 0) {
        echo '<div class="my-account">';
        echo '<h2>Riwayat Pengiriman</h2>';
        echo '<div class="account-section">';
        while ($row = $result->fetch_assoc()) {
            echo "<p>No. Resi: " . $row['tracking_number'] . " - Status: " . $row['status'] . "</p>";
        }
        echo '</div>';
        echo '</div>';
    } else {
        echo "<div class='my-account'><p>Tidak ada riwayat pengiriman.</p></div>";
    }
} else {
    echo "<div class='my-account'><p>Session atau ID tidak ditemukan.</p></div>";
}
?>
