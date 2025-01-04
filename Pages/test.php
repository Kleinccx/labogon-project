<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Marketplace</title>
    <link rel="stylesheet" href="/labogon/style.css">
    <style>
        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .product {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 16px;
            width: 300px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .product h3 {
            margin: 0 0 10px;
        }
    </style>
</head>
<body>
     <!--NAVIGATION BAR-->
     <header class="header">
        <div class="logo-img">
            <img class="logopic" src="Source/Img logo/logo2.png" alt="" 
            />
            <h1 class="logo-text">LA<span>Bogon</span></h1>
        </div>
         <nav class="main-nav">
            <ul class="main-nav-list">
                <li><a class="main-nav-link" href="">Buy & Sell</a></li>
                <li><a class="main-nav-link" href="">Events</a></li>
                <li><a class="main-nav-link" href="">NewsFeed</a></li>
                <li><a class="main-nav-link" href="">Appointment</a></li>
            
                <li><a class="main-nav-link nav-cta" href="#">Sign In</a></li>
            </ul>
         </nav>

         <button class="btn-mobile-nav">
            <ion-icon class="icon-mobile-nav" name="menu-outline"></ion-icon>
            <ion-icon class="icon-mobile-nav" name="close-outline"></ion-icon>
         </button>
     </header>
     <!--END NAVIGATION BAR-->


    <main>
        <section id="submitform">
            <h2>Welcome to Waste Marketplace</h2>
            <p>Buy and sell waste products for recycling and reuse.</p>
        

            <form id="buyAndSellForm" action="/labogon/Handler/buyandsellsubmit.php" method="POST" enctype="multipart/form-data">
                <label for="title">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>

                <select id="product_category" name="product_category">
                    <option value="Appliances">Appliances</option>
                    <option value="Recycle">Recycle</option>
                    <option value="Clothes">Clothes</option>
                </select>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="title">Seller Name:</label>
                <input type="text" id="seller_name" name="seller_name" required>

                <label for="title">Contact number:</label>
                <input type="text" id="contact_number" name="contact_number" required>

                <label for="title">Email:</label>
                <input type="email" id="email" name="email" required>

                
                <label for="title">Address:</label>
                <input type="text" id="address" name="address" required>


                <label for="location">Location (Click on the map to set):</label>
                <div id="map"></div>

                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image">

                <button type="submit">Submit</button>
            </form>
        </section>

        <section id="products">
            <h2>Available Products</h2>
            <div class="product-list">
                <!-- PHP code to fetch and display approved products will go here -->
                <?php
                $conn = new mysqli('localhost', 'root', '', 'barangay_labogon');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                $sql = "SELECT * FROM buy_and_sell WHERE status = 'approved'";
                $result = $conn->query($sql);

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
                        echo "</div>";
                    }
                } else {
                    echo "<p>No pending sales to display.</p>";
                }

                $conn->close();
                ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Waste Marketplace</p>
    </footer>
</body>
</html>
