<?php
session_start();

// Assuming the admin session is active
// if (!isset($_SESSION['admin_id'])) {
//     header('Location: login.php'); // Redirect if admin is not logged in
//     exit;
// }

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay_labogon";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Fetch the event to be edited
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    
    $sql = "SELECT * FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $event_result = $stmt->get_result();
    
    if ($event_result->num_rows == 0) {
        echo "Event not found!";
        exit;
    }
    
    $event = $event_result->fetch_assoc();
} else {
    echo "No event ID provided!";
    exit;
}

// Handle Edit Event with Image Upload
if (isset($_POST['edit_event'])) {
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $category_id = $_POST['category_id'];
    $location = $_POST['location'];
    $image_name = $event['event_image'];  // Default to existing image

    // Handle Image Upload
    if ($_FILES['event_image']['name']) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["event_image"]["name"]);
        $image_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an image
        $check = getimagesize($_FILES["event_image"]["tmp_name"]);
        if ($check === false) {
            die("File is not an image.");
        }

        // Allow certain file formats
        if ($image_type != "jpg" && $image_type != "jpeg" && $image_type != "png" && $image_type != "gif") {
            die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }

        // Move the uploaded file to the "uploads" directory
        if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
            $image_name = basename($_FILES["event_image"]["name"]);
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    }

    // Update the event in the database
    $sql = "UPDATE events SET event_name = ?, event_description = ?, event_date = ?, event_time = ?, category_id = ?, location = ?, event_image = ?, updated_at = NOW() WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssi', $event_name, $event_description, $event_date, $event_time, $category_id, $location, $image_name, $event_id);
    $stmt->execute();

    header('Location: admin.php'); // Redirect after successful edit
}

// Fetch Categories for Dropdown
$sql = "SELECT * FROM categories";
$categories_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
</head>
    <style>
            /* General Styles */
            body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        h1 {
            text-align: center;
            color: #444;
        }
        a {
            color: #3498db;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        button {
            background-color: #3498db;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        /* Form Styles */
        form {
            background: #fff;
            padding: 20px;
            margin: 20px auto;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
        }
        form input, form textarea, form select {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        form textarea {
            resize: none;
        }
        form img {
            display: block;
            margin: 10px auto;
            border-radius: 5px;
            max-width: 100px;
        }
        form label {
            font-weight: bold;
            display: block;
            margin: 10px 0 5px;
        }
        .back-link {
            position: absolute;
            top: 20px;
            left: 20px;
            font-size: 16px;
        }
    </style>
<body>

    <a href="events.php" class="back-link">&larr; Back to Event Panel</a>

    <h1>Edit Event</h1>
    
    <form action="edit_event.php?id=<?= $event['event_id'] ?>" method="POST" enctype="multipart/form-data">
        <input type="text" name="event_name" value="<?= $event['event_name'] ?>" placeholder="Event Name" required><br>
        <textarea name="event_description" placeholder="Event Description" required><?= $event['event_description'] ?></textarea><br>
        <input type="date" name="event_date" value="<?= $event['event_date'] ?>" required><br>
        <input type="time" name="event_time" value="<?= $event['event_time'] ?>" required><br>
        <select name="category_id" required>
            <?php while ($row = $categories_result->fetch_assoc()): ?>
                <option value="<?= $row['category_id'] ?>" <?= $row['category_id'] == $event['category_id'] ? 'selected' : '' ?>><?= $row['category_name'] ?></option>
            <?php endwhile; ?>
        </select><br>
        <input type="text" name="location" value="<?= $event['location'] ?>" placeholder="Event Location" required><br>

        <!-- Display the current image -->
        <?php if ($event['event_image']): ?>
            <img src="uploads/<?= $event['event_image'] ?>" alt="Event Image" width="100"><br>
            <label>Change Image (Optional):</label>
        <?php else: ?>
            <label>Upload Event Image:</label>
        <?php endif; ?>

        <input type="file" name="event_image" accept="image/*"><br>

        <button type="submit" name="edit_event">Save Changes</button>
    </form>
    
</body>
</html>
