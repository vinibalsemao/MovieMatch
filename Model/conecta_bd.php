<?php
try {
    $conn = mysqli_connect("localhost", "root", "", "MovieMatch_BD");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
} catch (Exception $e) {
    header("Location: ../views/pages/home_page.php?error=connectionError");
    exit();
}
