<?php
// Start session
session_start();

// Check if the user is logged in, otherwise redirect to the login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Tenant') {
    // Use session variables to display appropriate message before redirect
    echo "User not logged in!";
    header("Location: Login.php");
    exit();
}

// Database connection details
$servername = "localhost:3306";
$username = "root";
$password = "2812";
$dbname = "house_rental";

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in user's ID from session
$user_id = $_SESSION['user_id'];

// Fetch user details from the database
$sql = "SELECT name, email, contact, user_type FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);  // Assuming 'id' is an integer in the database
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User details not found!";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet"href="Profile.css">
</head>

<body>
    <div class="profile-box">
    <!-- Display the Common Profile Icon -->
    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Profile Icon" width="100" height="100">
    
    <!-- Display User Details -->
    <h2><?php echo htmlspecialchars($user['name']); ?>'s Profile</h2>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($user['contact']); ?></p>
    <p><strong>User Type:</strong> <?php echo htmlspecialchars($user['user_type']); ?></p>

    <!-- Action Buttons -->
<div class="action-buttons">
    <a href="UpdateProfile.php" class="button">Update Profile</a><br>
    <a href="DeleteProfile.php" class="button">Delete Profile</a>
</div>
    </div>

</body>
</html>