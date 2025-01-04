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
//log in checker
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;

// Function to check if the user is an admin
// function isAdmin() {
//     return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
// }

// Fetch all approved posts
$stmt = $pdo->query("SELECT posts.id, posts.content, posts.is_approved, posts.is_anonymous, posts.created_at, users.username 
                     FROM posts
                     JOIN users ON posts.user_id = users.id
                     WHERE posts.is_approved = 1
                     ORDER BY posts.created_at DESC");

$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Newsfeed</title>
    <link rel="stylesheet" href="/labogon/style.css">
    <style>
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f0f2f5;
    margin: 0;
    padding: 0;
}

header {
    background-color: #4267B2;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

header h1 {
    margin: 0;
    font-size: 1.5em;
}

header nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 15px;
}

header nav ul li {
    display: inline;
}

header nav ul li a {
    color: white;
    text-decoration: none;
    font-weight: bold;
}

header nav ul li a:hover {
    text-decoration: underline;
}

/* Newsfeed Container */
main {
    max-width: 800px;
    margin: 20px auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

h2 {
    color: #333;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
}

/* Post Form */
form {
    margin-bottom: 20px;
}

form textarea {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 10px;
    font-size: 1em;
    resize: none;
    height: 80px;
}

form label {
    display: block;
    margin: 10px 0;
    color: #555;
}

form button {
    background-color: #4267B2;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 1em;
}

form button:hover {
    background-color: #365899;
}

/* Post Styling */
.post {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.post p {
    margin: 5px 0;
    color: #333;
}

.post strong {
    color: #4267B2;
    font-size: 1.1em;
}

.post small {
    color: #888;
    font-size: 0.9em;
}

/* Comment Section */
.post form {
    margin-top: 10px;
}

.post form textarea {
    height: 50px;
}

.post form button {
    margin-top: 5px;
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
                <li><a class="main-nav-link" href="">Buy & Sell</a></li>
                <li><a class="main-nav-link" href="">Events</a></li>
                <li><a class="main-nav-link" href="">NewsFeed</a></li>
                <li><a class="main-nav-link" href="">Appointment</a></li>
            
                <li>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</li>
                <li><a class="main-nav-link nav-cta" href="/labogon/Pages/logout.php">Logout</a></li>
            </ul>
            <?php else: ?>
                <ul class="main-nav-list">
                    <li><a class="main-nav-link" href="">Buy & Sell</a></li>
                    <li><a class="main-nav-link" href="">Events</a></li>
                    <li><a class="main-nav-link" href="">NewsFeed</a></li>
                    <li><a class="main-nav-link" href="">Appointment</a></li>
                
                    <li><a class="main-nav-link nav-cta" href="/labogon/login.php">Sign In</a></li>
                </ul>
            <?php endif; ?>
         </nav>

         <button class="btn-mobile-nav">
            <ion-icon class="icon-mobile-nav" name="menu-outline"></ion-icon>
            <ion-icon class="icon-mobile-nav" name="close-outline"></ion-icon>
         </button>
     </header>
    <h2>Newsfeed</h2>
    <!-- Only show post form if the user is logged in (Purok Leader or Resident) -->
    <?php if (isLoggedIn() && ($_SESSION['user_type'] == 'user' || $_SESSION['user_type'] == 'purok_leader')): ?>
        <form method="POST" action="/labogon/Handler/post.php">
            <textarea name="content" placeholder="Write a post..." required></textarea><br>

            <button type="submit">Post</button>
        </form>
    <?php endif; ?>

    <h3>Posts</h3>
    <?php foreach ($posts as $post): ?>
        <div class="post">
            <p><strong><?php echo $post['is_anonymous'] ? 'Anonymous' : $post['username']; ?></strong></p>
            <p><?php echo htmlspecialchars($post['content']); ?></p>
            <small>Posted on: <?php echo $post['created_at']; ?></small>

            <!-- Comment section (only for logged-in users) -->
            <?php if (isLoggedIn()): ?>
                <form method="POST" action="/labongon/Handler/comment.php">
                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                    <textarea name="comment" placeholder="Write a comment..." required></textarea><br>
                    <button type="submit">Comment</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>
