<?php
// Start session
session_start();


// Check if the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Tenant') {
    header("Location: Login.php");
    exit();
}

// Get the user's name and user ID from session
$name = $_SESSION['name'];
$user_id = $_SESSION['user_id'];

// Database connection
$servername = "localhost:3306";
$username = "root";
$password = "2812"; // Use your actual database password here
$dbname = "house_rental"; // Use your actual database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch the user's bookings
$sql = "SELECT Property.name, Property.address, Bookings.booking_date 
        FROM Bookings 
        JOIN Property ON Bookings.property_id = Property.id 
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
    <title>House Rental Website</title>
    <link rel="stylesheet" href="Tenant.css">
</head>
<body>
    <header>
        <div class="tenant-welcome">
        <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
        <p>Explore the properties available.</p>
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

    <section class="main-content">
        <h1>Our Services</h1>
        <div class="buttons-section">
            <div class="button-container">
                <a href="Buy.php">
                    <img src="https://media.zenfs.com/en/smartasset_475/542daa8c9d36f2433b18cbf38e2227b0" alt="Buy" class="button-image" />
                    <div class="button-text">BUY</div>
                </a>
            </div>
            <div class="button-container">
                <a href="Rent.php">
                    <img src="https://m.economictimes.com/thumb/msid-84498905,width-1200,height-900,resizemode-4,imgsize-179374/home-buying.jpg" alt="Rent" class="button-image" />
                    <div class="button-text">RENT</div>
                </a>
            </div>
            <div class="button-container">
                <a href="Stay.php">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ96j6fFc-BFu3sExdHHNjMfk2WrQKu6b0bEA&s" alt="Stay" class="button-image" />
                    <div class="button-text">STAY</div>
                </a>
            </div>
        </div>
    </section>

    <!--  "My Bookings"  -->
    <section class="my-bookings">
        <h2>My Bookings</h2>
        <?php if ($result->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Property Name</th>
                    <th>Address</th>
                    <th>Booking Date</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['address']); ?></td>
                        <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>You have no bookings at the moment.</p>
        <?php endif; ?>
    </section>

    <!-- Footer -->
    <footer>
        &copy; 2024 Dream Stays - All Rights Reserved
    </footer>
</body>
</html>