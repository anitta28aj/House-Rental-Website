<?php
session_start();

// Check if the user is logged in
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

// Fetch current profile details
$sql = "SELECT name, email, contact FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $contact);
$stmt->fetch();
$stmt->close();

// Handle profile update form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $updated_name = $_POST['name'];
    $updated_email = $_POST['email'];
    $updated_contact = $_POST['contact'];

    // Update profile in the database
    $update_sql = "UPDATE users SET name = ?, email = ?, contact = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssi", $updated_name, $updated_email, $updated_contact, $user_id);

    if ($update_stmt->execute()) {
        // Redirect to tenant page after successful update
        header("Location: Tenant.php");
        exit(); // Always call exit after header redirection
    } else {
        echo "Error updating profile: " . $update_stmt->error;
    }

    $update_stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet"href="SignUp.css">
</head>
<body>
<div class="signup-box">
        <h2>Update Profile</h2>
        <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>
        <label for="contact">Contact:</label>
        <input type="text" name="contact" value="<?php echo htmlspecialchars($contact); ?>" required><br>
        <button type="submit">Update</button>
    </form>
    </div>
</body>
</html>
