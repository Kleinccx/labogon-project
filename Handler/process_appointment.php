<?php
// $conn = new mysqli('localhost', 'root', '', 'barangay_labogon');

// // Check for database connection errors
// if ($conn->connect_error) {
//     die("Database connection failed: " . $conn->connect_error);
// }

// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // Sanitize and validate inputs
//     $contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING);
//     $appointment_with = filter_input(INPUT_POST, 'appointment_with', FILTER_SANITIZE_STRING);
//     $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
//     $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);

//     if (!preg_match('/^[0-9]{10,15}$/', $contact_number)) {
//         die("Invalid contact number format.");
//     }

//     // Check availability
//     $stmt = $conn->prepare("SELECT * FROM availability WHERE person = ? AND date = ? AND time = ?");
//     $stmt->bind_param('sss', $appointment_with, $date, $time);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     if ($result->num_rows > 0) {
//         die("The selected date and time are unavailable. Please choose another.");
//     }

//     // Insert appointment
//     $stmt = $conn->prepare("INSERT INTO appointments (contact_number, appointment_with, date, time, status) VALUES (?, ?, ?, ?, 'Pending')");
//     $stmt->bind_param('ssss', $contact_number, $appointment_with, $date, $time);

//     if ($stmt->execute()) {
//         echo "Appointment set successfully!";
//     } else {
//         echo "Error: " . $stmt->error;
//     }
// }

// Ito ang bago 


$conn = new mysqli('localhost', 'root', '', 'barangay_labogon');

// Check for database connection errors
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs 
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $contact_number = filter_input(INPUT_POST, 'contact_number', FILTER_SANITIZE_STRING);
    $appointment_with = filter_input(INPUT_POST, 'appointment_with', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $time = filter_input(INPUT_POST, 'time', FILTER_SANITIZE_STRING);

    // Validate required fields
    if (empty($full_name) || empty($address)) {
        die("Full Name and Address are required.");
    }

    // Validate contact number format
    if (!preg_match('/^[0-9]{10,15}$/', $contact_number)) {
        die("Invalid contact number format.");
    }

    // Check if the selected date and time are available
    $stmt = $conn->prepare("SELECT * FROM appointments 
                            WHERE appointment_with = ? AND date = ? AND time = ? AND (status = 'Pending' OR status = 'Confirmed')");
    $stmt->bind_param('sss', $appointment_with, $date, $time);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("The selected date and time are unavailable. Please choose another.");
    }

    // Insert the appointment
    $stmt = $conn->prepare("INSERT INTO appointments (full_name, address, contact_number, appointment_with, date, time, status) VALUES (?, ?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param('ssssss', $full_name, $address, $contact_number, $appointment_with, $date, $time);

    if ($stmt->execute()) {
        echo "<script>
            alert('Appointment request was successfully sent!');
            window.location.href = '/labogon/Pages/appointmentandapplication.php';
        </script>";
    } else {
        echo "<script>
            alert('Error: " . $stmt->error . "');
            window.history.back();
        </script>";
    }
}





?>
