<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: Login.php");
    exit();
}

// Database connection
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

// Get the logged-in user's email from session
$user_email = $_SESSION['email'];

// Retrieve the user's id based on the email
$sql = "SELECT id FROM Users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
} else {
    echo "User details not found!";
    exit();
}
$stmt->close();

// Fetch properties booked by the user
$sql = "SELECT Property.image, Property.property_type, Property.price, Property.bhk, Property.address, Property.city, Property.state 
        FROM Property 
        INNER JOIN Bookings ON Property.id = Bookings.property_id 
        WHERE Bookings.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link rel="stylesheet"href="MyBookings.css">
</head>
<body>
    <h1>My Bookings</h1>
    <div class="property-grid">
        <?php
        if ($result->num_rows > 0) {
            // Output data of each row
            while ($row = $result->fetch_assoc()) {
                echo '<div class="property-box">';
                echo '<img src="' . htmlspecialchars($row['image']) . '" alt="Property Image" style="width: 200px; height: 150px;">';
                echo '<h3>' . htmlspecialchars($row['property_type']) . '</h3>';
                echo '<p>Price: ₹' . htmlspecialchars($row['price']) . '</p>';
                echo '<p>BHK: ' . htmlspecialchars($row['bhk']) . '</p>';
                echo '<p>Location: ' . htmlspecialchars($row['address']) . ', ' . htmlspecialchars($row['city']) . ', ' . htmlspecialchars($row['state']) . '</p>';
                echo '</div>';
            }
        } else {
            echo '<p>No bookings found.</p>';
        }
        ?>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
