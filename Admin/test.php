<?php
$conn = new mysqli('localhost', 'root', '', 'barangay_labogon');

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Function to fetch appointments grouped by date for two weeks
function getTwoWeekAppointments($conn, $appointment_with) {
    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
    $endOfNextWeek = date('Y-m-d', strtotime('sunday next week'));

    $stmt = $conn->prepare("SELECT * FROM appointments 
                            WHERE appointment_with = ? 
                            AND status = 'Confirmed' 
                            AND date BETWEEN ? AND ?
                            ORDER BY date, time ASC");
    $stmt->bind_param('sss', $appointment_with, $startOfWeek, $endOfNextWeek);
    $stmt->execute();
    $result = $stmt->get_result();

    // Group appointments by date
    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $date = $row['date'];
        if (!isset($appointments[$date])) {
            $appointments[$date] = [];
        }
        $appointments[$date][] = $row;
    }
    return $appointments;
}

// Fetch appointments for both Barangay Chairman and SK Chairman
$chairmanAppointments = getTwoWeekAppointments($conn, 'Barangay Chairman');
$skChairmanAppointments = getTwoWeekAppointments($conn, 'SK Chairman');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Schedule</title>
    <style>
        .card {
            border: 1px solid #ccc;
            border-radius: 8px;
            margin: 10px;
            padding: 15px;
            width: 300px;
            display: inline-block;
            vertical-align: top;
        }
        .card h3 {
            margin-top: 0;
        }
        .schedule {
            display: flex;
            flex-wrap: wrap;
        }
        .appointment-list {
            list-style: none;
            padding: 0;
        }
        .appointment-list li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h1>Admin Schedule</h1>

    <h2>Barangay Chairman</h2>
    <div class="schedule">
        <?php foreach ($chairmanAppointments as $date => $appointments): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($date); ?></h3>
                <ul class="appointment-list">
                    <?php foreach ($appointments as $appointment): ?>
                        <li>
                            <strong>Time:</strong> <?php echo htmlspecialchars($appointment['time']); ?><br>
                            <strong>Name:</strong> <?php echo htmlspecialchars($appointment['full_name']); ?><br>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>SK Chairman</h2>
    <div class="schedule">
        <?php foreach ($skChairmanAppointments as $date => $appointments): ?>
            <div class="card">
                <h3><?php echo htmlspecialchars($date); ?></h3>
                <ul class="appointment-list">
                    <?php foreach ($appointments as $appointment): ?>
                        <li>
                            <strong>Time:</strong> <?php echo htmlspecialchars($appointment['time']); ?><br>
                            <strong>Name:</strong> <?php echo htmlspecialchars($appointment['full_name']); ?><br>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>


