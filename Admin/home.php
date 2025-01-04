<?php
// session_start();

// // Check if the user is logged in
// if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
//     header("Location: login.php");
//     exit();
// }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Document</title>
</head>
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

     <?php
        // Database connection
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "barangay_management";

        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
            // Handle delete request
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
            $delete_id = $_POST['delete_id'];
            $deletesql = "DELETE FROM reminders WHERE id = ?";
            $stmt = $conn->prepare($deletesql);
            $stmt->bind_param("i", $delete_id);
        
            if ($stmt->execute()) {
                echo "Reminder deleted successfully!";
            } else {
                echo "Error deleting reminder: " . $conn->error;
            }
        
                $stmt->close();
            }
        // Fetch reminders from the database
        $reminderssql = "SELECT * FROM reminders ORDER BY date ASC";
        $result = $conn->query($reminderssql);


        ?>
        <h1>Reminder Notifications</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="notification">
                    <p><strong>Date:</strong> <?php echo $row['date']; ?></p>
                    <p><strong>Description:</strong> <?php echo $row['description']; ?></p>
                    <p><small>Created at: <?php echo $row['created_at']; ?></small></p>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" class="delete-button">Delete</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No reminders yet!</p>
        <?php endif; ?>

        <?php $conn->close(); ?>
    </section>
</body>
</html>