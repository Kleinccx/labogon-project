<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barangay Online Services</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-group {
            flex: 1 1 48%;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input, .form-group select {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group input[type="date"] {
            font-family: inherit;
        }

        .form-group input:focus, .form-group select:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 3px rgba(0, 123, 255, 0.5);
        }

        .form-group.full-width {
            flex: 1 1 100%;
        }

        .btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            align-self: flex-start;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Barangay Online Services</h1>
        <form action="insert.php" method="POST">
            <div class="form-group full-width">
                <label for="address">Complete Address*</label>
                <input type="text" id="address" name="address" placeholder="Enter your complete address" required>
            </div>

            <div class="form-group">
                <label for="birth-date">Birth Date*</label>
                <input type="date" id="birth-date" name="birth_date" required>
            </div>

            <div class="form-group">
                <label for="age">Age*</label>
                <input type="number" id="age" name="age" placeholder="Enter your age" required>
            </div>

            <div class="form-group">
                <label for="status">Status*</label>
                <input type="text" id="status" name="status" placeholder="Enter your status" required>
            </div>

            <div class="form-group">
                <label for="mobile">Mobile Number*</label>
                <input type="tel" id="mobile" name="mobile" placeholder="Enter your mobile number" required>
            </div>

            <div class="form-group">
                <label for="years-of-stay">Years of Stay*</label>
                <input type="number" id="years-of-stay" name="years_of_stay" placeholder="Enter years of stay" required>
            </div>

            <div class="form-group">
                <label for="purpose">Purpose*</label>
                <input type="text" id="purpose" name="purpose" placeholder="Enter the purpose" required>
            </div>

            <div class="form-group">
                <label for="student-patient">Name of Student / Patient*</label>
                <input type="text" id="student-patient" name="student_patient" placeholder="Enter name of student or patient" required>
            </div>

            <div class="form-group">
                <label for="specific-address">Address*</label>
                <input type="text" id="specific-address" name="specific_address" placeholder="Enter specific address" required>
            </div>

            <div class="form-group">
                <label for="relationship">Relationship*</label>
                <input type="text" id="relationship" name="relationship" placeholder="Enter your relationship" required>
            </div>

            <div class="form-group">
                <label for="email">Email*</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <!-- <div class="form-group">
                <label for="shipping">Shipping Method*</label>
                <select id="shipping" name="shipping" required>
                    <option value="pickup">PICK UP (Claim within 24 hours upon submission)</option>
                </select>
            </div> -->

            <button type="submit" class="btn">Send Message</button>
        </form>
    </div>



    <?php
    // PHP script to handle email verification during application
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];

        // Connect to the database
        $conn = new mysqli('localhost', 'username', 'password', 'database_name');

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if email exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>
                if (confirm('Email already exists. Do you want to use the existing email?')) {
                    window.location.href = 'use_existing.php?email=$email';
                } else {
                    window.location.href = 'new_application.php';
                }
            </script>";
        } else {
            echo "<script>
                window.location.href = 'new_application.php';
            </script>";
        }

        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
