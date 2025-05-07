<?php
$servername = "localhost";  // This should be localhost if you're using a local server
$username = "root";         // Default username for local MySQL installations is root
$password = "";             // Leave it blank if there's no password (for local XAMPP or WAMP servers)
$dbname = "agri";           // Your database name should be the same as the one you're using in MySQL

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully";  // This line helps you verify if connection is established
}
?>
