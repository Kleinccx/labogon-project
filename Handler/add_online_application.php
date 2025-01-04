<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay_services";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $address = $_POST['address'];
    $birth_date = $_POST['birth_date'];
    $age = $_POST['age'];
    $status = $_POST['status'];
    $mobile = $_POST['mobile'];
    $years_of_stay = $_POST['years_of_stay'];
    $purpose = $_POST['purpose'];
    $student_patient = $_POST['student_patient'];
    $specific_address = $_POST['specific_address'];
    $relationship = $_POST['relationship'];
    $email = $_POST['email'];
    $shipping = $_POST['shipping'];

    // SQL query to insert data
    $sql = "INSERT INTO applications (address, birth_date, age, status, mobile, years_of_stay, purpose, student_patient, specific_address, relationship, email, shipping)
            VALUES ('$address', '$birth_date', '$age', '$status', '$mobile', '$years_of_stay', '$purpose', '$student_patient', '$specific_address', '$relationship', '$email', '$shipping')";

    if ($conn->query($sql) === TRUE) {
        echo "Application submitted successfully.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
