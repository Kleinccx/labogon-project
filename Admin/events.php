<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay_labogon";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);



// Handle Add Event with Image Upload
if (isset($_POST['add_event'])) {
    $event_name = $_POST['event_name'];
    $event_description = $_POST['event_description'];
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $category_id = $_POST['category_id'];
    $location = $_POST['location'];
    $image_name = '';  // Default value if no image is uploaded

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
        if ($image_type != "jpg" && $image_type != "png" && $image_type != "jpeg" && $image_type != "gif") {
            die("Sorry, only JPG, JPEG, PNG & GIF files are allowed.");
        }

        // Move the uploaded file to the "uploads" directory
        if (move_uploaded_file($_FILES["event_image"]["tmp_name"], $target_file)) {
            $image_name = basename($_FILES["event_image"]["name"]);
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    }

    // Insert event into the database
    $sql = "INSERT INTO events (event_name, event_description, event_date, event_time, category_id, location, event_image, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssssi', $event_name, $event_description, $event_date, $event_time, $category_id, $location, $image_name, $_SESSION['admin_id']);
    $stmt->execute();
    header('Location: event.php'); // Redirect after successful insert
}

// Handle Delete Event
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch the event to delete the image
    $sql = "SELECT event_image FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $event = $result->fetch_assoc();

    // Delete image file if it exists
    if ($event && $event['event_image']) {
        $image_path = "uploads/" . $event['event_image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    // Delete the event from the database
    $sql = "DELETE FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $event_id);
    $stmt->execute();

    header('Location: events.php'); // Redirect after deletion
    exit;
}

// Fetch Categories for Dropdown
$sql = "SELECT * FROM categories";
$categories_result = $conn->query($sql);

// Fetch Events for Display
$sql = "SELECT * FROM events JOIN categories ON events.category_id = categories.category_id ORDER BY event_date DESC";
$events_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="/labogon/style.css">
</head>
<style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            line-height: 1.6;
            padding-bottom: 4rem;
        }
        .h1 {
            font-size: 5.2rem;
            font-weight: 700;
            line-height: 1.1;
            text-align: center;
            padding: 4rem;
            letter-spacing: -0.5px;
        }
        .h2{
            font-size: 3rem;
            font-weight: 500;
            line-height: 1;
            text-align: center;
            padding: 4rem 4rem 1rem 4rem;
            letter-spacing: -0.5px;
        }
        a {
            color: #6a994e;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        button {
            background-color: #6a994e;
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
        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        table thead tr {
            background-color: #6a994e;
            color: #fff;
            text-align: left;
        }
        table th, table td {
            padding: 12px;
            border: 1px solid #ddd;
            font-size: 2rem;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table img {
            border-radius: 5px;
        }
        /* Action Links */
        .action-links a {
            margin: 0 5px;
            padding: 5px 10px;
            border-radius: 3px;
        }
        .action-links a.edit {
            background-color: #27ae60;
            color: #fff;
        }
        .action-links a.delete {
            background-color: #e74c3c;
            color: #fff;
        }
        .action-links a:hover {
            opacity: 0.9;
        }
    </style>
<body>
    <header class="header">
        <div class="logo-img">
            <img class="logopic" src="Source/Img logo/logo2.png" alt="" 
            />
            <h1 class="logo-text">LA<span>Bogon</span></h1>
        </div>
         <nav class="main-nav">
            <ul class="main-nav-list">
                <li><a class="main-nav-link" href="garbagecollection.php">Garbage Collection</a></li>
                <li><a class="main-nav-link" href="buyandsell.php">Buy & Sell</a></li>
                <li><a class="main-nav-link" href="events.php">Events</a></li>
                <li><a class="main-nav-link" href="newsfeed.php">NewsFeed</a></li>
                <li><a class="main-nav-link" href="">Appointment</a></li>
            
                
                <li><a class="main-nav-link nav-cta" href="/labogon/Pages/logout.php">Logout</a></li>
            </ul>
         </nav>

         <button class="btn-mobile-nav">
            <ion-icon class="icon-mobile-nav" name="menu-outline"></ion-icon>
            <ion-icon class="icon-mobile-nav" name="close-outline"></ion-icon>
         </button>
     </header>


    <h1 class="h1">Admin Panel - Manage Events</h1>
    
    <h2 class="h2">Add Event</h2>
    <form action="events.php" method="POST" enctype="multipart/form-data">
        <input type="text" name="event_name" placeholder="Event Name" required><br>
        <textarea name="event_description" placeholder="Event Description" required></textarea><br>
        <input type="date" name="event_date" required><br>
        <input type="time" name="event_time" required><br>
        <select name="category_id" required>
            <?php while ($row = $categories_result->fetch_assoc()): ?>
                <option value="<?= $row['category_id'] ?>"><?= $row['category_name'] ?></option>
            <?php endwhile; ?>
        </select><br>
        <input type="text" name="location" placeholder="Event Location" required><br>
        <input type="file" name="event_image" accept="image/*"><br>
        <button type="submit" name="add_event">Add Event</button>
    </form>
    
    <h2 class="h2">All Events</h2>
    <table class="container">
        <thead>
            <tr>
                <th>Event Name</th>
                <th>Category</th>
                <th>Date</th>
                <th>Time</th>
                <th>Location</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($event = $events_result->fetch_assoc()): ?>
                <tr>
                    <td><?= $event['event_name'] ?></td>
                    <td><?= $event['category_name'] ?></td>
                    <td><?= $event['event_date'] ?></td>
                    <td><?= $event['event_time'] ?></td>
                    <td><?= $event['location'] ?></td>
                    <td>
                        <?php if ($event['event_image']): ?>
                            <img src="uploads/<?= $event['event_image'] ?>" alt="Event Image" width="100">
                        <?php else: ?>
                            No image
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit_event.php?id=<?= $event['event_id'] ?>">Edit</a> | 
                        <a href="events.php?action=delete&id=<?= $event['event_id'] ?>" class="delete" onclick="return confirm('Are you sure you want to delete this event?')">Delete</a>

                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
