<?php
// Start session
session_start();

// Check if the user is logged in as a Tenant
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Tenant') {
    header("Location: Login.php");
    exit();
}

// Get the user's name and ID from session
$name = $_SESSION['name'];
$user_id = $_SESSION['user_id'];

// Database connection
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

// Get the property ID from the URL
if (!isset($_GET['property_id'])) {
    die("Invalid property ID.");
}

$property_id = intval($_GET['property_id']);

// Fetch the property details
$sql = "SELECT Property.*, Users.name AS owner_name, Users.contact AS owner_contact 
        FROM Property 
        JOIN Users ON Property.owner_id = Users.id 
        WHERE Property.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $property_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Property not found.");
}

$property = $result->fetch_assoc();

// Handle the booking request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_date = date('Y-m-d'); // Current date
    $booking_sql = "INSERT INTO Bookings (property_id, user_id, booking_date) VALUES (?, ?, ?)";
    $booking_stmt = $conn->prepare($booking_sql);
    $booking_stmt->bind_param("iis", $property_id, $user_id, $booking_date);

    if ($booking_stmt->execute()) {
        echo "<script>
                alert('Booking successful!');
                window.location.href='Tenant.php'; 
              </script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Details</title>
    <link rel="stylesheet" href="PropertyDetails.css"> <!-- Add your custom CSS here -->
</head>
<body>
<header>
    <h1>Property Details</h1>
</header>

<div class="property-details">
    <div class="property-image">
        <img src="<?php echo htmlspecialchars($property['image']); ?>" alt="Property Image">
    </div>
    <div class="property-info">
        <table>
            <tr><th>Property Type:</th><td><?php echo htmlspecialchars($property['property_type']); ?></td></tr>
            <tr><th>BHK:</th><td><?php echo htmlspecialchars($property['bhk']); ?></td></tr>
            <tr><th>Price:</th><td>₹<?php echo htmlspecialchars($property['price']); ?></td></tr>
            <tr><th>Location:</th><td><?php echo htmlspecialchars($property['address']) . ', ' . htmlspecialchars($property['city']) . ', ' . htmlspecialchars($property['state']); ?></td></tr>
            <tr><th>Owner Name:</th><td><?php echo htmlspecialchars($property['owner_name']); ?></td></tr>
            <tr><th>Owner Contact:</th><td><?php echo htmlspecialchars($property['owner_contact']); ?></td></tr>
        </table>
        <form method="POST">
            <button type="submit" class="book-now-btn">Book Now</button>
        </form>
    </div>
</div>

<footer>
    <p>&copy; 2024 Dream Stays - All rights reserved.</p>
</footer>
</body>
</html>

<?php
// Close the database connection
$stmt->close();
$conn->close();
?>
