<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay_labogon";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Update product status to 'approved'
    $stmt = $conn->prepare("UPDATE buy_and_sell SET status='approved' WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Product approved successfully.";
    } else {
        echo "Error approving product: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();

header("Location: /labogon/Admin/buyandsell.php"); // Redirect back
exit;

?>
