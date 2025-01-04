<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barangay_labogon";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

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
    <title>Public Events</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h1>Public Events</h1>
    
    <h2>Filter by Category</h2>
    <form action="events.php" method="GET">
        <select name="category_id">
            <option value="">Select Category</option>
            <?php while ($row = $categories_result->fetch_assoc()): ?>
                <option value="<?= $row['category_id'] ?>"><?= $row['category_name'] ?></option>
            <?php endwhile; ?>
        </select>
        <button type="submit">Filter</button>
    </form>
    
    <h2>Event Carousel</h2>
    <div id="eventCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <?php
            $first_event = true;
            while ($event = $events_result->fetch_assoc()):
            ?>
                <div class="carousel-item <?php echo $first_event ? 'active' : ''; ?>">
                    <img src="/labogon/Admin/uploads/<?=$event['event_image'] ?>" class="d-block w-100" alt="<?= $event['event_name'] ?>">
                    <div class="carousel-caption d-none d-md-block">
                        <h5><?= $event['event_name'] ?></h5>
                        <p><?= $event['event_description'] ?></p>
                    </div>
                </div>
                <?php $first_event = false; ?>
            <?php endwhile; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
