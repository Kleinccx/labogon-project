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

if (!isLoggedIn()) {
    header('Location: /labogon/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $_POST['content'];
    $is_anonymous = 1;

    // Insert post into the database
    $stmt = $pdo->prepare('INSERT INTO posts (user_id, content, is_approved, is_anonymous) VALUES (?, ?, 0, ?)');
    $stmt->execute([$_SESSION['user_id'], $content, $is_anonymous]);

    header('Location: /labogon/Pages/newsfeed.php');
}
?>
