<?php
// session_start();
// $conn = new mysqli('localhost', 'root', '', 'barangay_labogon');

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// // Get the current month and year
// $month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

// // Fetch schedule data for the selected month
// $query = "SELECT * FROM collection_schedule WHERE DATE_FORMAT(schedule_date, '%Y-%m') = '$month'";
// $result = $conn->query($query);

// $schedule_data = [];
// while ($row = $result->fetch_assoc()) {
//     $schedule_data[] = $row;
// }

// // Return JSON response
// header('Content-Type: application/json');
// echo json_encode($schedule_data);


// Database connection
// $host = 'localhost';
// $user = 'root';
// $password = '';
// $database = 'barangay_labogon';

// $conn = new mysqli($host, $user, $password, $database);

// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }

// // Get the date from the AJAX request
// $date = $_GET['date'] ?? '';

// if ($date) {
//     $stmt = $conn->prepare("SELECT * FROM collection_schedule WHERE schedule_date = ?");
//     $stmt->bind_param("s", $date);
//     $stmt->execute();
//     $result = $stmt->get_result();

//     $schedules = [];
//     while ($row = $result->fetch_assoc()) {
//         $schedules[] = $row;
//     }

//     // Return data as JSON
//     echo json_encode($schedules);
// } else {
//     echo json_encode([]);
// }

// $conn->close();




/////Ito yonng pang 2 na gamit ko talaga 
// Database connection

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'barangay_labogon';

// Enable error reporting (for development only)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$conn = new mysqli($host, $user, $password, $database);

// Check connection
if ($conn->connect_error) {
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}

// Get the date from the AJAX request
$date = $_GET['date'] ?? '';

if ($date) {
    // Prepare and execute SQL query
    $stmt = $conn->prepare("SELECT * FROM collection_schedule WHERE schedule_date = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();

    $schedules = [];
    while ($row = $result->fetch_assoc()) {
        $schedules[] = $row;
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($schedules);
} else {
    // Return an empty array if no date is provided
    header('Content-Type: application/json');
    echo json_encode([]);
}

$conn->close();



// Ito pang 3 

// fetch_schedule.php
// include('db.php');

// $date = $_GET['date']; // Get the date parameter

// // Query to fetch schedule data for the specific date
// $query = "SELECT id, driver_name, area, status, schedule_time FROM collection_schedule WHERE schedule_date = :date";
// $stmt = $pdo->prepare($query);
// $stmt->execute(['date' => $date]);

// // Fetch the schedule details
// $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo json_encode($schedules); // Send the schedules as a JSON response


?>




