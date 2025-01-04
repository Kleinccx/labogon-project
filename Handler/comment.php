<?php
include 'db.php';
include 'config.php';

if (!isLoggedIn()) {
    header('Location: /labogon/index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post_id = $_POST['post_id'];
    $comment = $_POST['comment'];

    // Insert comment into the database
    $stmt = $pdo->prepare('INSERT INTO comments (post_id, user_id, comment) VALUES (?, ?, ?)');
    $stmt->execute([$post_id, $_SESSION['user_id'], $comment]);

    header("Location: newsfeed.php");
}
?>
