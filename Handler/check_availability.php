<?php
$conn = new mysqli('localhost', 'root', '', 'barangay_system');

// Check for database connection errors
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $appointment_with = filter_input(INPUT_POST, 'appointment_with', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);

    // Check availability
    $stmt = $conn->prepare("SELECT * FROM availability WHERE person = ? AND date = ? AND time = ?");
    $stmt->bind_param('sss', $appointment_with, $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo 'unavailable';
    } else {
        echo 'available';
    }
}
?>
