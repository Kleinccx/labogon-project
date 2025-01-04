<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay_labogon";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $product_name = $_POST['product_name'] ?? '';
    $product_category = $_POST['product_category'] ?? '';
    $description = $_POST['description'] ?? '';
    $seller_name = $_POST['seller_name'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';
    $imageName = "";
    $imagePath = "";
    $userId = $_POST['user_id'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = "/labogon/Pages/ProductImage/";
        $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
        $imagePath = $uploadDir . $imageName;

        // Ensure the upload directory exists
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $uploadDir)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . $uploadDir, 0777, true);
        }

        // Move the uploaded file to the target directory
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $imagePath)) {
            echo "Error uploading image.";
            exit;
        }
    } else {
        echo "No image uploaded or upload error.";
        exit;
    }

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO buy_and_sell (product_name, product_category, description, seller_name, contact_number, email, address, image_path, image_name, status, created_at, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $status = 'Pending'; // Default status is 'Pending'
    $created_at = date('Y-m-d H:i:s'); // Current timestamp
    $stmt->bind_param("ssssssssssss", $product_name, $product_category, $description, $seller_name, $contact_number, $email, $address, $imagePath, $imageName, $status, $created_at, $userId);

    if ($stmt->execute()) {
        echo "Submitted successfully!";
        header("Location: /labogon/Pages/buyandsell.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
