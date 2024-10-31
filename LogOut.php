<?php
// Start session
session_start();

// Destroy session variables
session_unset();
session_destroy();

// Redirect to HomePage.php with a success message
header("Location: HomePage.html?message=Successfully Logged Out");
exit();
?>
