<?php
$servername = "localhost";
$username   = "root";   // default in XAMPP
$password   = "";       // default in XAMPP
$database   = "restaurant_menu_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
// echo "Connected successfully";
?>
