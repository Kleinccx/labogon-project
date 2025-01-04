<?php
$host = 'localhost'; // or your database host
$db = 'barangay_labogon';
$user = 'root'; // replace with your DB username
$pass = ''; // replace with your DB password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}
?>
