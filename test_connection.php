<?php
// Include the database connection configuration
include('./includes/config/config.php');

// Check if the connection is successful
if ($conn->ping()) {
    echo "Connection is successful!";
} else {
    echo "Connection failed: " . $conn->error;
}
