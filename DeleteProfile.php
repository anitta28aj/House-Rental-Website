<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: Login.php");
    exit();
}

$servername = "localhost:3306";
$username = "root";
$password = "2812";
$dbname = "house_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Delete the profile from the database
$sql = "DELETE FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    // Logout and destroy session
    session_unset();
    session_destroy();
    header("Location: HomePage.html?message=Profile deleted successfully");
    exit();
} else {
    echo "Error deleting profile: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
