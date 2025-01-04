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

// Handle form submissions for Add/Edit/Delete schedule operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Add a new schedule
    if (isset($_POST['add_schedule'])) {
        $date = $_POST['schedule_date'];
        $time = $_POST['schedule_time'];
        $driver = $_POST['driver_name'];
        $area = $_POST['area'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("INSERT INTO collection_schedule (schedule_date, schedule_time, driver_name, area, status) 
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$date, $time, $driver, $area, $status]);
    }

    // Edit an existing schedule
    if (isset($_POST['edit_schedule'])) {
        $id = $_POST['id'];
        $date = $_POST['schedule_date'];
        $time = $_POST['schedule_time'];
        $driver = $_POST['driver_name'];
        $area = $_POST['area'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE collection_schedule 
                               SET schedule_date = ?, schedule_time = ?, driver_name = ?, area = ?, status = ? 
                               WHERE id = ?");
        $stmt->execute([$date, $time, $driver, $area, $status, $id]);
    }

    // Delete a schedule
    if (isset($_POST['delete_schedule'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM collection_schedule WHERE id = ?");
        $stmt->execute([$id]);
    }

    // Handle Performance Updates
    if (isset($_POST['update_performance'])) {
        $schedule_id = $_POST['schedule_id'];
        $bins_collected = $_POST['bins_collected'];
        $plastic_bottles_collected = $_POST['plastic_bottles_collected'];
        $remarks = $_POST['remarks'];

        // Check if performance entry exists for the selected schedule
        $stmt = $pdo->prepare("SELECT * FROM collection_performance WHERE schedule_id = ?");
        $stmt->execute([$schedule_id]);
        if ($stmt->rowCount() > 0) {
            // Update existing entry
            $stmt = $pdo->prepare("UPDATE collection_performance 
                                   SET bins_collected = ?, plastic_bottles_collected = ?, remarks = ?, updated_at = NOW() 
                                   WHERE schedule_id = ?");
            $stmt->execute([$bins_collected, $plastic_bottles_collected, $remarks, $schedule_id]);
        } else {
            // Insert new entry
            $stmt = $pdo->prepare("INSERT INTO collection_performance (schedule_id, bins_collected, plastic_bottles_collected, remarks) 
                                   VALUES (?, ?, ?, ?)");
            $stmt->execute([$schedule_id, $bins_collected, $plastic_bottles_collected, $remarks]);
        }
    }
}

// Fetch data for display
$schedules = $pdo->query("SELECT * FROM collection_schedule")->fetchAll(PDO::FETCH_ASSOC);
$notifications = $pdo->query("SELECT * FROM notifications ORDER BY sent_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$performances = $pdo->query("
    SELECT cp.id AS performance_id, cs.id AS schedule_id, cs.schedule_date, cs.schedule_time, cs.driver_name, cs.area, 
           cp.bins_collected, cp.plastic_bottles_collected, cp.remarks 
    FROM collection_performance cp 
    JOIN collection_schedule cs ON cp.schedule_id = cs.id
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table, th, td { border: 1px solid #ddd; text-align: center; }
        th, td { padding: 10px; }
        form { margin-bottom: 20px; }
    </style>
</head>
<body>

<h1>Garbage Collection Schedule - Admin</h1>

<!-- Form to Add/Edit Schedules -->
<form method="POST">
    <h2>Add / Edit Schedule</h2>
    <input type="hidden" name="id" id="schedule_id">
    <label>Schedule Date:</label><br>
    <input type="date" name="schedule_date" id="schedule_date" required><br>
    <label>Schedule Time:</label><br>
    <input type="time" name="schedule_time" id="schedule_time" required><br>
    <label>Driver Name:</label><br>
    <input type="text" name="driver_name" id="driver_name" required><br>
    <label>Area:</label><br>
    <input type="text" name="area" id="area" required><br>
    <label>Status:</label><br>
    <select name="status" id="status" required>
        <option value="To Pick Up">To Pick Up</option>
        <option value="Done">Done</option>
    </select><br><br>
    <button type="submit" name="add_schedule">Add Schedule</button>
    <button type="submit" name="edit_schedule">Edit Schedule</button>
</form>

<!-- Display Schedule Table -->
<h2>Schedule List</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Time</th>
            <th>Driver</th>
            <th>Area</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($schedules as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['schedule_date'] ?></td>
            <td><?= $row['schedule_time'] ?></td>
            <td><?= $row['driver_name'] ?></td>
            <td><?= $row['area'] ?></td>
            <td><?= $row['status'] ?></td>
            <td>
                <!-- Edit Button -->
                <button type="button" onclick="editSchedule(
                    '<?= $row['id'] ?>',
                    '<?= $row['schedule_date'] ?>',
                    '<?= $row['schedule_time'] ?>',
                    '<?= $row['driver_name'] ?>',
                    '<?= $row['area'] ?>',
                    '<?= $row['status'] ?>'
                )">Edit</button>
                <!-- Delete Form -->
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $row['id'] ?>">
                    <button type="submit" name="delete_schedule">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- Add Performance Tracking Section and Notifications -->

<!-- Form to Add/Edit Performance -->
<form method="POST">
    <h2>Add / Edit Schedule</h2>
    <input type="hidden" name="id" id="schedule_id">
    <input type="hidden" name="id" id="schedule_id">
    <label>Schedule Date:</label><br>
    <input type="date" name="schedule_date" id="schedule_date" required><br>
    <label>Schedule Time:</label><br>
    <input type="time" name="schedule_time" id="schedule_time" required><br>
    <label>Driver Name:</label><br>
    <input type="text" name="driver_name" id="driver_name" required><br>
    <label>Area:</label><br>
    <input type="text" name="area" id="area" required><br>
    <label>Status:</label><br>
    <select name="status" id="status" required>
        <option value="To Pick Up">To Pick Up</option>
        <option value="Done">Done</option>
    </select><br><br>
    <button type="submit" name="add_schedule">Add Schedule</button>
    <button type="submit" name="edit_schedule">Edit Schedule</button>
</form>


<!-- Display Performance Table -->


<!-- Display Notifications -->
<h2>Notifications</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Sender</th>
            <th>Message</th>
            <th>Sent At</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach  ($notifications as $row): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['sender_name'] ?></td>
            <td><?= $row['message'] ?></td>
            <td><?= $row['sent_at'] ?></td>
            <td><?= $row['status'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    <script>
        // Function to fill the form fields with the selected row's data
        function editSchedule(id, date, time, driver, area, status) {
            document.getElementById('schedule_id').value = id;
            document.getElementById('schedule_date').value = date;
            document.getElementById('schedule_time').value = time;
            document.getElementById('driver_name').value = driver;
            document.getElementById('area').value = area;
            document.getElementById('status').value = status;
        }
    </script>

</body>
</html>
