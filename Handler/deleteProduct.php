<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'barangay_labogon');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo "Unauthorized action.";
        exit;
    }

    $productId = $_POST['product_id'];
    $userId = $_SESSION['user_id'];

    // Verify that the product belongs to the logged-in user
    $stmt = $conn->prepare("SELECT id FROM buy_and_sell WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $productId, $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo "You can only delete your own products.";
        exit;
    }

    // Delete the product
    $stmt = $conn->prepare("DELETE FROM buy_and_sell WHERE id = ?");
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        echo "Product successfully deleted.";
    } else {
        echo "Failed to delete product.";
    }

    $stmt->close();
}

$conn->close();
?>
