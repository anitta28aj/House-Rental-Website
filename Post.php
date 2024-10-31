<?php
session_start();

// Check if the user is logged in as an owner
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Owner') {
    header("Location: Login.php");
    exit();
}

// Database Configuration and Connection
$servername = "localhost";
$username = "root";
$password = "2812";
$dbname = "house_rental";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $name = $_POST['name'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $property_type = $_POST['property_type'];
    $bhk = $_POST['bhk'];
    $price = $_POST['price'];
    
    // File upload logic
    $image = $_FILES['image']['name'];
    $target = "images/" . basename($image);

    // Insert the property data into the Property table
    $query = $conn->prepare("INSERT INTO Property (owner_id, name, state, city, address, contact, property_type, bhk, price, image) 
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $query->bind_param("issssssiis", $_SESSION['user_id'], $name, $state, $city, $address, $contact, $property_type, $bhk, $price, $image);

    if ($query->execute()) {
        // Move the uploaded image to the server directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo "<script>alert('Property posted successfully!');</script>";
        }
    } else {
        echo "<script>alert('Failed to add property. Please try again.');</script>";
    }
    $query->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Property</title>
    <link rel="stylesheet" href="Post.css">
</head>
<body>
    <header>
        <div class="tenant-welcome">
            <h1>My House</h1>
            <p>Manage your properties.</p>
        </div>
        <div class="container">
            <h5>DREAM STAYS</h5>
        </div>
        <div class="auth-buttons">
            <button onclick="window.location.href='Logout.php';">Log Out</button>
        </div>
    </header>

    <nav class="navbar">
        <ul>
            <li><a href="HomePage.html">Home</a></li>
            <li><a href="AboutUs.html">About Us</a></li>
            <li><a href="Policies.html">Policies</a></li>
            <li><a href="ContactUs.html">Contact Us</a></li>
            <li><a href="Profile.php">My Profile</a></li>
        </ul>
    </nav>

    <div class="container">
        <div class="sidebar">
            <div class="message-item">
                <button class="sidebar-btn" onclick="window.location.href='Post.php'">Add House</button>
            </div>
            <div class="message-item">
                <button class="sidebar-btn active" onclick="window.location.href='Owner.php'">My Properties</button>
            </div>
        </div>

        <!-- Main Content Area for Property Posting -->
        <div class="main-content">
            <h2>Post Your Property</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required><br><br>

                <label for="state">State:</label>
                <input type="text" name="state" id="state" required><br><br>

                <label for="city">City:</label>
                <input type="text" name="city" id="city" required><br><br>

                <label for="address">Address:</label>
                <input type="text" name="address" id="address" required><br><br>

                <label for="contact">Contact Number:</label>
                <input type="tel" name="contact" id="contact" pattern="[0-9]{10}" required><br><br>

                <label for="property_type">Property Type:</label>
                <select name="property_type" id="property_type" required>
                    <option value="Buy">Buy</option>
                    <option value="Rent">Rent</option>
                    <option value="Stay">Stay</option>
                </select><br><br>

                <label for="bhk">Number of BHKs:</label>
                <select name="bhk" id="bhk" required>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                </select><br><br>

                <label for="price">Price:</label>
                <input type="number" name="price" id="price" required><br><br>

                <label for="image">Property Image:</label>
                <input type="file" name="image" id="image" required><br><br>

                <button type="submit">Post Property</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Dream Stays - All rights reserved.</p>
    </footer>
</body>
</html>
