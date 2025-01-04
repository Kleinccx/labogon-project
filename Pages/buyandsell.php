<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'barangay_labogon');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waste Marketplace</title>
    <link rel="stylesheet" href="/labogon/style.css">
    <style>


        .logo-text {
            font-size: 24px;
            font-weight: bold;
        }

        .logo-text span {
            color: #FFD700;
        }


        main {
            padding: 20px;
        }

        h2 {
            font-size: 3rem;
            font-weight: 700;
            line-height: 1.1;
            color: #c0c0c0;
            text-align: center;
            padding: 4rem;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        form input, form select, form textarea, form button {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        form button {
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }

        .product-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .product {
            background-color: #f2e8cf;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .product:hover {
            transform: translateY(-5px);
        }

        .product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .product h3 {
            margin: 0 0 10px;
            font-size: 20px;
            color: #4CAF50;
        }

        .product p {
            margin: 5px 0;
            font-size: 14px;
        }

        .product button {
            background-color: #4CAF50;
            color: white;
            padding: 8px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
        }

        .product button:hover {
            background-color: #45a049;
        }

        @media (max-width: 768px) {
            .product {
                width: 90%;
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
         <?php if ($isLoggedIn): ?>
            <ul class="main-nav-list">
                <li><a class="main-nav-link" href="buyandsell.php">Buy & Sell</a></li>
                <li><a class="main-nav-link" href="events.php">Events</a></li>
                <li><a class="main-nav-link" href="newsfeed.php">NewsFeed</a></li>
                <li><a class="main-nav-link" href="appointmentandapplication.php">Appointment</a></li>
            
                <li>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</li>
                <li><a class="main-nav-link nav-cta" href="logout.php">Logout</a></li>
            </ul>
            <?php else: ?>
                <ul class="main-nav-list">
                    <li><a class="main-nav-link" href="buyandsell.php">Buy & Sell</a></li>
                    <li><a class="main-nav-link" href="events.php">Events</a></li>
                    <li><a class="main-nav-link" href="newsfeed.php">NewsFeed</a></li>
                    <li><a class="main-nav-link" href="appointmentandapplication.php">Appointment</a></li>
                
                    <li><a class="main-nav-link nav-cta" href="/labogon/login.php">Sign In</a></li>
                </ul>
            <?php endif; ?>
         </nav>

         <button class="btn-mobile-nav">
            <ion-icon class="icon-mobile-nav" name="menu-outline"></ion-icon>
            <ion-icon class="icon-mobile-nav" name="close-outline"></ion-icon>
         </button>
     </header>

<main>
    <!-- Show form and user products if logged in -->
    <?php if ($isLoggedIn): ?>
        <section id="submitform">
            <h2>Post Your Product</h2>
            <form id="buyAndSellForm" action="/labogon/Handler/buyandsellsubmit.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="user_id" value="<?php echo $userId; ?>">
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required>

                <label for="product_category">Category:</label>
                <select id="product_category" name="product_category">
                    <option value="Appliances">Appliances</option>
                    <option value="Recycle">Recycle</option>
                    <option value="Clothes">Clothes</option>
                </select>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="seller_name">Seller Name:</label>
                <input type="text" id="seller_name" name="seller_name" required>

                <label for="contact_number">Contact Number:</label>
                <input type="text" id="contact_number" name="contact_number" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>

                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required>

                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" required>

                <button type="submit">Submit</button>
            </form>
        </section>

        <section id="userProducts">
            <h2 class="">Your Products</h2>
            <div class="product-list">
                <?php
                $stmt = $conn->prepare("SELECT * FROM buy_and_sell WHERE user_id = ?");
                $stmt->bind_param("i", $userId);
                $stmt->execute();
                $result = $stmt->get_result();

                while ($row = $result->fetch_assoc()) {
                    $imagePath = !empty($row['image_name']) ? '/labogon/Pages/ProductImage/' . $row['image_name'] : '';
                    echo "<div class='product'>";
                    if ($imagePath) {
                        echo "<img src='$imagePath' alt='Product Image'>";
                    }
                    echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
                    echo "<p>Status: " . htmlspecialchars($row['status']) . "</p>";
                    if ($row['status'] !== 'Sold') {
                        echo "<button onclick='changeStatus(" . $row['id'] . ", \"Sold\")'>Mark as Sold</button>";
                    }
                    echo "<button onclick='deleteProduct(" . $row['id'] . ")'>Cancel</button>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>
    <?php endif; ?>

    <!-- Public view of approved products -->
    <section id="products">
        <h2>Available Products</h2>
        <div class="product-list">
            <?php
            $sql = "SELECT * FROM buy_and_sell WHERE status = 'approved'";
            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc()) {
                $imagePath = !empty($row['image_name']) ? '/labogon/Pages/ProductImage/' . $row['image_name'] : '';
                echo "<div class='product'>";
                if ($imagePath) {
                    echo "<img src='$imagePath' alt='Product Image'>";
                }
                echo "<h3>" . htmlspecialchars($row['product_name']) . "</h3>";
                echo "<p>Category: " . htmlspecialchars($row['product_category']) . "</p>";
                echo "<p>Description: " . htmlspecialchars($row['description']) . "</p>";
                echo "<p>Seller: " . htmlspecialchars($row['seller_name']) . "</p>";
                echo "<p>Contact: " . htmlspecialchars($row['contact_number']) . "</p>";
                echo "<p>Email: " . htmlspecialchars($row['email']) . "</p>";
                if ($isLoggedIn && $row['user_id'] === $userId) {
                    echo "<button onclick='changeStatus(" . $row['id'] . ", \"Sold\")'>Mark as Sold</button>";
                    echo "<button onclick='deleteProduct(" . $row['id'] . ")'>Delete</button>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </section>
</main>


<script>

    function changeStatus(productId, status) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/labogon/Handler/updateStatus.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                alert(xhr.responseText);
                location.reload(); // Reload the page to reflect changes
            } else {
                alert("Failed to update status. Please try again.");
            }
        };

        xhr.send(`product_id=${productId}&status=${status}`);
    }

    function deleteProduct(productId) {
        if (!confirm("Are you sure you want to delete this product?")) {
            return;
        }

        const xhr = new XMLHttpRequest();
        xhr.open("POST", "/labogon/Handler/deleteProduct.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onload = function () {
            if (xhr.status === 200) {
                alert(xhr.responseText);
                location.reload(); // Reload the page to reflect changes
            } else {
                alert("Failed to delete product. Please try again.");
            }
        };

        xhr.send(`product_id=${productId}`);
    }
</script>

    <!--ICONS-->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

    <script src="/labogon/script.js"></script>
</body>
</html>

<?php $conn->close(); ?>
