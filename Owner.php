<?php
// Start session
session_start();

// Check if the user is logged in as an owner
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Owner') {
    header("Location: Login.php");
    exit();
}

// Get the user's name from the session
$name = $_SESSION['name'];

// Database connection
$servername = "localhost:3306";  // Change if different
$username = "root";         // Update with your database username
$password = "2812";         // Update with your database password
$dbname = "house_rental";   // Use your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_sql = "DELETE FROM Property WHERE id = $delete_id AND owner_id = " . $_SESSION['user_id'];
    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Property deleted successfully'); window.location.href='Owner.php';</script>";
    } else {
        echo "Error deleting property: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>House Rental Website</title>
    <link rel="stylesheet" href="Owner.css"> <!-- Use the updated CSS file -->
</head>
<body>
    <header>
        <div class="tenant-welcome">
            <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
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
            <li><a href="Profile2.php">My Profile</a></li>
        </ul>
    </nav>

    <div class="container">
        <!-- Sidebar with buttons -->
        <div class="sidebar">
            <div class="message-item">
                <button class="sidebar-btn" onclick="window.location.href='Post.php'">Add House</button>
            </div>
            <div class="message-item">
                <button class="sidebar-btn active" onclick="window.location.href='Owner.php'">My Properties</button>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <h2>Manage Your Properties</h2>
            <table class="property-table">
                <thead>
                    <tr>
                        <th>Sl No.</th>
                        <th>State</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Contact</th>
                        <th>Property Type</th>
                        <th>BHK</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $owner_id = $_SESSION['user_id'];
                    $sql = "SELECT * FROM Property WHERE owner_id = $owner_id";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $sl_no = 1; // Initialize serial number
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $sl_no . "</td>";
                            echo "<td>" . htmlspecialchars($row['state']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['city']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['address']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['contact']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['property_type']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['bhk']) . "</td>";
                            echo "<td>₹" . htmlspecialchars($row['price']) . "</td>"; // Display price with currency
                            echo "<td>
                                    <a href='Owner.php?delete_id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this property?\")'>
                                        <button>Delete</button>
                                    </a>
                                  </td>";
                            echo "</tr>";
                            $sl_no++;
                        }
                    } else {
                        echo "<tr><td colspan='9'>No properties found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Dream Stays - All rights reserved.</p>
    </footer>

    <?php $conn->close(); ?>
</body>
</html>
