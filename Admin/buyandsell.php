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

// Fetch pending sales
$result = $conn->query("SELECT id, product_name, product_category, description, seller_name, contact_number, email, image_path, image_name, created_at FROM buy_and_sell WHERE status='Pending'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Pending Sales</title>
    <link rel="stylesheet" href="/labogon/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }


        .h1 {
            font-size: 5.2rem;
            font-weight: 700;
            line-height: 1.1;
            text-align: center;
            padding: 4rem;
            letter-spacing: -0.5px;
        }

        .container {
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .product-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin: 10px;
            width: 100%;
            max-width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease-in-out;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product-card h3 {
            font-size: 20px;
            margin: 0 0 10px;
        }

        .product-card p {
            margin: 5px 0;
            font-size: 14px;
        }

        .product-card button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 16px;
            font-size: 14px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
            width: 100%;
        }

        .product-card button:hover {
            background-color: #45a049;
        }

        .no-data {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #777;
        }

        @media (max-width: 768px) {
            .product-card {
                max-width: 90%;
            }
        }
    </style>
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
    <h1 class="h1">Pending Sales</h1>
    <div class="container">
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $imagePath = !empty($row['image_name']) ? '/labogon/Pages/ProductImage/' . $row['image_name'] : '';
                echo "<div class='product-card'>";
                if ($imagePath) {
                    echo "<img src='$imagePath' alt='Product Image'>";
                }
                echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
                echo "<p>Category: " . htmlspecialchars($row['product_category']) . "</p>";
                echo "<p>Description: " . htmlspecialchars($row['description']) . "</p>";
                echo "<p>Seller: " . htmlspecialchars($row['seller_name']) . "</p>";
                echo "<p>Contact: " . htmlspecialchars($row['contact_number']) . "</p>";
                echo "<p>Email: " . htmlspecialchars($row['email']) . "</p>";
                echo "<p>Posted on: " . htmlspecialchars($row['created_at']) . "</p>";
                echo "<form action='/labogon/Handler/approve_sale.php' method='POST'>";
                echo "<input type='hidden' name='id' value='" . htmlspecialchars($row['id']) . "'>";
                echo "<button type='submit'>Approve</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "<p>No pending sales to display.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
