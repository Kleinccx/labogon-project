<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli('localhost', 'root', '', 'barangay_labogon');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    $user_type = $_POST['user_type'] ?? 'user';

    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, user_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $passwordHash, $user_type);


    if ($stmt->execute()) {
        echo "Registration successful! <a href='login.php'>Login</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form action="registration.php" method="POST">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required><br>
    
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required><br>
    
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required><br>

    <label for="user_type">User Type:</label>
    <select name="user_type" id="user_type">
        <option value="user">User</option>
        <option value="purok_leader">Purok Leader</option>
    </select><br>
    
    <button type="submit">Register</button>
</form>


</body>
</html>
