<?php
// send_reminder.php

header('Content-Type: application/json');
session_start();

$conn = new mysqli('localhost', 'root', '', 'barangay_labogon');

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'purok_leader') {
//     echo json_encode(['success' => false, 'message' => 'Unauthorized user']);
//     exit;
// }

// // Get POST data from the request
// $inputData = json_decode(file_get_contents("php://input"), true);

// if (
//     isset($inputData['schedule_id']) &&
//     isset($inputData['sender_name']) &&
//     isset($inputData['message']) &&
//     isset($inputData['sent_at']) &&
//     isset($inputData['status'])
// ) {
//     // Assuming you have a function to connect to your database
//     // and insert the reminder data into the database

//     $schedule_id = $inputData['schedule_id'];
//     $sender_name = $inputData['sender_name'];
//     $message = $inputData['message'];
//     $sent_at = $inputData['sent_at'];
//     $status = $inputData['status'];

//     // Example of inserting into a database (modify according to your database schema)
//     // Assuming you have a database connection in $db
//     $sql = "INSERT INTO notifications (schedule_id, sender_name, message, sent_at, status) 
//             VALUES (?, ?, ?, ?, ?)";
    
//     $stmt = $db->prepare($sql);
//     $stmt->bind_param('issss', $schedule_id, $sender_name, $message, $sent_at, $status);

//     if ($stmt->execute()) {
//         echo json_encode(['success' => true, 'message' => 'Reminder sent successfully']);
//     } else {
//         echo json_encode(['success' => false, 'message' => 'Failed to send reminder']);
//     }

//     $stmt->close();
// } else {
//     echo json_encode(['success' => false, 'message' => 'Missing required data']);
// }

// Check if the POST data is set
if (isset($_POST['schedule_id']) && isset($_POST['sender_name']) && isset($_POST['message'])) {
    $scheduleId = $_POST['schedule_id'];
    $senderName = $_POST['sender_name'];
    $message = $_POST['message'];

    // Prepare SQL statement to insert reminder
    $stmt = $conn->prepare("INSERT INTO notifications (schedule_id, sender_name, message, sent_at, status) VALUES (?, ?, ?, NOW(), 'unread')");
    $stmt->bind_param("iss", $scheduleId, $senderName, $message); // "iss" stands for Integer, String, String

    // Execute the query
    if ($stmt->execute()) {
        // Return success response
        echo json_encode(['success' => true]);
    } else {
        // Return failure response
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
    }

    // Close the statement
    $stmt->close();
} else {
    // Return failure response if required data is missing
    echo json_encode(['success' => false, 'message' => 'Missing required data']);
}

$conn->close();
?>
