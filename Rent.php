<?php
// Start session
session_start();

// Check if the user is logged in as a Tenant
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Tenant') {
    header("Location: Login.php");
    exit();
}

// Get the user's name from session
$name = $_SESSION['name'];

// Database connection
$servername = "localhost:3306"; // Your database server name
$username = "root"; // Your database username
$password = "2812"; // Your database password
$dbname = "house_rental"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch properties available for "Buy" from the Property table
$sql = "SELECT * FROM Property WHERE property_type = 'Rent'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buy Properties - Dream Stays</title>
    <link rel="stylesheet" href="Buy.css"> <!-- Using Buy page styles -->
</head>
<body>
<header>
    <h1><strong>RENT the REAL</strong></h1>
</header>

<div class="navbar">
    <ul>
        <li><a href="HomePage.html">Home</a></li>
        <li><a href="AboutUs.html">About Us</a></li>
        <li><a href="Policies.html">Policies</a></li>
        <li><a href="ContactUs.html">Contact</a></li>
        <li><a href="Profile.php">My Profile</a></li>
    </ul>
</div>

<!-- Search Section -->
<div class="search-container">
    <select id="location">
        <option value="">Select Location</option>
        <option value="Bengaluru">Bengaluru</option>
        <option value="Chennai">Chennai</option>
        <option value="Kochi">Kochi</option>
        <option value="Hyderabad">Hyderabad</option>
        <option value="Mumbai">Mumbai</option>
    </select>    
    <input type="number" id="min-budget" placeholder="Min Budget">
    <input type="number" id="max-budget" placeholder="Max Budget">
    <button id="search-button">Search</button>
</div>

<!-- Properties Section -->
<div class="property-grid">
    <?php
    if ($result->num_rows > 0) {
        // Output data of each property
        while ($row = $result->fetch_assoc()) {
            echo '<div class="property-box">';
            echo '<img src="' . htmlspecialchars($row['image']) . '" alt="Property Image">';
            echo '<h3>' . htmlspecialchars($row['name']) . '</h3>'; // Name of the property
            echo '<p>Price: ₹' . htmlspecialchars($row['price']) . '</p>';
            echo '<p>BHK: ' . htmlspecialchars($row['bhk']) . '</p>';
            echo '<p>Location: ' . htmlspecialchars($row['address']) . ', ' . htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['state']) . '</p>';
            echo '<a href="PropertyDetails.php?property_id=' . $row['id'] . '"><button class="btn">View Details</button></a>'; // Pass property_id
            echo '</div>';
        }
    } else {
        echo '<p>No properties found.</p>';
    }
    ?>
</div>

<footer>
    <p>&copy; 2024 Dream Stays - All rights reserved.</p>
</footer>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
