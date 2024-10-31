<?php
// Connect to the database
$servername = "localhost:3306";
$username = "root"; // Your MySQL username
$password = "2812"; // Your MySQL password
$dbname = "house_rental"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $user_type = $_POST['user_type']; // Get the user type (Tenant/Owner)
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($password === $confirm_password) {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (name, email, contact, user_type, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $contact, $user_type, $hashed_password);

        // Execute the query
        if ($stmt->execute()) {
            echo "Registration successful!";
            // Redirect to login page
            header("Location: Login.php");
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Passwords do not match!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link rel="stylesheet"href="SignUp.css">
</head>
<body>
    <div class="signup-box">
        <h2>Sign Up with us!</h2>
        <form action="" method="POST">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="tel" name="contact" placeholder="Contact Number" required>
            <select name="user_type" required>
                <option value="">Are you a Tenant or Owner?</option>
                <option value="Tenant">Tenant</option>
                <option value="Owner">Owner</option>
            </select>
            <input type="password" name="password" placeholder="Password" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            <button type="submit">Signup</button>
        </form>
        <div class="login-link">
            <p>Already have an account? <a href="Login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
