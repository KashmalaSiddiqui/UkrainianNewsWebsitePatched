<?php

// Database configuration
$host = "db";  // Replace with your MySQL server IP
$username = "user";                // Your MySQL username
$password = "password";          // Your MySQL password
$database = "users";   // Your database name


// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    // Connection failed, display the error message
    die("Connection failed: " . $conn->connect_error);
} else {
    // Connection successful
    // echo "Connected successfully to the database to my databse.";
}
