<?php
// Start session
session_start();

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

// Initialize variables to store error messages
$error_message = "";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and execute the query to check user credentials
    $stmt = $conn->prepare("SELECT id, name, user_type, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Bind the results
        $stmt->bind_result($user_id, $name, $user_type, $hashed_password);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Store user information in session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $name;
            $_SESSION['user_type'] = $user_type;

            // Redirect to the tenant page
            if ($user_type === 'Tenant') {
                header("Location: Tenant.php");
                exit();
            } else if ($user_type === 'Owner') {
                header("Location: Owner.php");
                exit();
            }
        } else {
            $error_message = "Invalid password!";
        }
    } else {
        $error_message = "No user found with that email!";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="Login.css">
</head>
<body>
    <div class="login-box">
        <h2>Login Now</h2>
        <!-- Error Message -->
        <?php
        if (!empty($error_message)) {
            echo "<div class='error'>$error_message</div>";
        }
        ?>
        <form action="" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <div class="back-link">
            <p>New user? <a href="SignUp.php">Create an Account</a></p>
        </div>
    </div>
</body>
</html>
