<?php
// Database configuration
$servername = "localhost:3306";
$username = "root";
$password = "2812";
$dbname = "house_rental";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];
    $applying_position = $_POST['applying_position'];
    $message = $_POST['message'];

    // File uploads for Cover Letter and Resume
    $resume = addslashes(file_get_contents($_FILES['resume']['tmp_name']));

    // Insert data into the careers table
    $sql = "INSERT INTO careers (full_name, email, phone_number, address, applying_position, resume, message) 
            VALUES ('$full_name', '$email', '$phone_number', '$address', '$applying_position', '$resume', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Application</title>
    <link rel="stylesheet" href="Careers.css">
</head>
<body>
    <h1>Employee Application</h1>
    <form action="careers.php" method="POST" enctype="multipart/form-data">
        <label for="full_name">Full Name *</label><br>
        <input type="text" name="full_name" required><br><br>

        <label for="email">E-mail *</label><br>
        <input type="email" name="email" required><br><br>

        <label for="phone_number">Phone Number *</label><br>
        <input type="text" name="phone_number" required><br><br>

        <label for="address">Address</label><br>
        <textarea name="address"></textarea><br><br>

        <label for="applying_position">Applying for Position: *</label><br>
        <input type="text" name="applying_position" required><br><br>

        <label for="resume">Upload Resume</label><br>
        <input type="file" name="resume"><br><br>

        <label for="message">Enter a message *</label><br>
        <textarea name="message" required></textarea><br><br>

        <input type="submit" value="Submit">
        <input type="reset" value="Clear form">
    </form>
</body>
</html>
