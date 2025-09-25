<?php
session_start();        // Start the session
session_unset();        // Clear all session variables
session_destroy();      // Destroy the session completely

header("Location: HOMEPAGE.php"); // Redirect to the homepage
exit();
?>