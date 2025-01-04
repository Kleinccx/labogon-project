<?php
$host = 'localhost'; 
$db = 'barangay_labogon';
$user = 'root'; 
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
session_start();

// Function to check if the user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Function to check if the user is an admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// if (!isAdmin()) {
//     header('Location: /labogon/Admin/newsfeed.php');
//     exit;
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $action = $_POST['action'];

    if ($action == 'approve') {
        $stmt = $pdo->prepare('UPDATE posts SET is_approved = 1 WHERE id = ?');
        $stmt->execute([$post_id]);
    } elseif ($action == 'reject') {
        $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
        $stmt->execute([$post_id]);
    }

    header('Location: /labogon/Admin/newsfeed.php');
    exit;
}

// Fetch all unapproved posts
$stmt = $pdo->query("SELECT posts.id, posts.content, posts.created_at, users.username 
                     FROM posts
                     JOIN users ON posts.user_id = users.id
                     WHERE posts.is_approved = 0");
$unapproved_posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Newsfeed Admin</title>
    <link rel="stylesheet" href="/labogon/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }


        main {
            max-width: 800px;
            margin: 2rem auto;
            padding: 1rem;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .h2 {
            font-size: 4.6rem;
            font-weight: 700;
            line-height: 1.1;
            text-align: center;
            padding: 4rem;
            letter-spacing: -0.5px;
        }

        .post {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1rem;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .post p {
            margin: 0.5rem 0;
            font-size: 2rem;
        }

        .post strong {
            color: #007bff;
        }

        .post form {
            display: flex;
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .post button {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .post button[name="action"][value="approve"] {
            background-color: #28a745;
            color: white;
        }

        .post button[name="action"][value="approve"]:hover {
            background-color: #218838;
        }

        .post button[name="action"][value="reject"] {
            background-color: #dc3545;
            color: white;
        }

        .post button[name="action"][value="reject"]:hover {
            background-color: #c82333;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background-color: #007bff;
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
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
    <h2 class="h2">Admin Panel - Approve Posts</h2>
    <?php foreach ($unapproved_posts as $post): ?>
        <div class="post container">
            <p><strong><?php echo $post['username']; ?></strong></p>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <form method="POST">
                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                <button type="submit" name="action" value="approve">Approve</button>
                <button type="submit" name="action" value="reject">Reject</button>
            </form>
        </div>
    <?php endforeach; ?>
</body>
</html>
