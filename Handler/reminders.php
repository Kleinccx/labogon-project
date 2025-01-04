<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay_labogon";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Insert data into the database
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = date('Y-m-d');
    $description = "Garbage bin is full";

    $sql = "INSERT INTO reminders (date, description) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $date, $description);

    if ($stmt->execute()) {
        echo "<script>
            alert('Appointment request was successfully sent!');
            window.location.href = '/labogon/Pages/garbagecollection.php';
        </script>";
    } else {
        echo "<script>
            alert('Error: " . $stmt->error . "');
            window.history.back();
        </script>";
    }

    $stmt->close();
}

$conn->close();
?>
