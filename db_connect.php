<?php
// db_connect.php

$servername = "localhost";  // or your database server (e.g., "127.0.0.1" or the host provided by your hosting provider)
$username = "root";         // your MySQL database username
$password = "";             // your MySQL database password
$dbname = "edu";  // the name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>