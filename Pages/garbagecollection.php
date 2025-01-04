<?php

session_start();


// Check if the user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userId = $isLoggedIn ? $_SESSION['user_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Calendar with Role-Based Actions</title>
    <link rel="stylesheet" href="/labogon/style.css">
    <style>
        
        body {
        font-family: 'Roboto', Arial, sans-serif;
        margin: 0;
        padding: 0;

        background: linear-gradient(135deg, #f6d365, #fda085);
        color: #333;
    }
    .calendar-wrapper{
        display: flex;
        justify-content: center;
        align-items: center;
    }
    #calendar-container {
        width: 90%;
        max-width: 900px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        padding: 30px;
        overflow: hidden;
    }

    #month-year {
        text-align: center;
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #444;
    }

    .nav-buttons {
        text-align: center;
        margin-bottom: 20px;
    }

    .nav-buttons button {
        padding: 10px 20px;
        margin: 5px;
        font-size: 16px;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        transition: transform 0.2s, background 0.3s;
    }

    .nav-buttons button:hover {
        transform: scale(1.1);
        background: linear-gradient(135deg, #764ba2, #667eea);
    }

    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 10px;
        padding: 10px;
    }

    .day {
        background: #f9f9f9;
        border-radius: 10px;
        padding: 15px;
        text-align: center;
        position: relative;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.3s;
    }

    .day:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
    }

    .day span {
        font-size: 16px;
        font-weight: bold;
        color: #444;
    }

    .day.red {
        background: linear-gradient(135deg, #ff6b6b, #ee5253);
        color: white;
    }

    .day.green {
        background: linear-gradient(135deg, #1dd1a1, #10ac84);
        color: white;
    }

    /* Modal styles */
    #schedule-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        justify-content: center;
        align-items: center;
        z-index: 10;
    }

    .modal-content {
        background-color: white;
        padding: 25px;
        border-radius: 15px;
        text-align: left;
        width: 80%;
        max-width: 400px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.3s ease-in-out;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        margin: 0;
        color: #444;
    }

    #close-modal {
        cursor: pointer;
        background: none;
        border: none;
        font-size: 24px;
        color: #444;
        transition: color 0.2s;
    }

    #close-modal:hover {
        color: #ff6b6b;
    }

    .modal-body {
        margin-top: 15px;
    }

    .send-reminder-btn {
        display: inline-block;
        padding: 10px 15px;
        font-size: 16px;
        color: white;
        background: linear-gradient(135deg, #ff9f43, #ff6b6b);
        border: none;
        border-radius: 25px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .send-reminder-btn:hover {
        background: linear-gradient(135deg, #ff6b6b, #ff9f43);
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        #calendar-container {
            width: 100%;
            padding: 15px;
        }

        .day {
            padding: 10px;
        }

        .modal-content {
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
                <li><a class="main-nav-link" href="">Buy & Sell</a></li>
                <li><a class="main-nav-link" href="">Events</a></li>
                <li><a class="main-nav-link" href="">NewsFeed</a></li>
                <li><a class="main-nav-link" href="">Appointment</a></li>
            
                <li>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</li>
                <li><a class="main-nav-link nav-cta" href="logout.php">Logout</a></li>
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

    <div class="calendar-wrapper">
        <div id="calendar-container">
            <div class="nav-buttons">
                <button id="prev">&lt; Previous</button>
                <button id="next">Next &gt;</button>
            </div>
            <div id="month-year"></div>
            <div class="calendar-grid"></div>
        </div>
    </div>
    



    <!-- Modal -->
    <div id="schedule-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Schedule Details</h3>
                <button id="close-modal">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>Location:</strong> <span id="modal-location"></span></p>
                <p><strong>Category:</strong> <span id="modal-category"></span></p>
                <p><strong>Date:</strong> <span id="modal-date"></span></p>
                <p><strong>Status:</strong> <span id="modal-status"></span></p>
                <!-- Render Send Reminder button if the user is a Purok Leader -->
                <?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'purok_leader'): ?>
                    <form class="reminder-form" method="POST" action="/labogon/Handlers/reminders.php">
                   
                        <button id="send-reminder" class="send-reminder-btn">Send Reminder</button>
                    </form>
                    
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    // JavaScript for Calendar with Color Coding
    const calendarGrid = document.querySelector(".calendar-grid");
    const monthYear = document.getElementById("month-year");
    const modal = document.getElementById("schedule-modal");
    const modalBody = document.querySelector(".modal-body");
    const closeModal = document.getElementById("close-modal");

    let currentDate = new Date();

    function renderCalendar() {
        // Clear previous days
        calendarGrid.innerHTML = "";

        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();

        // Update the month and year display
        monthYear.textContent = currentDate.toLocaleDateString("en-US", {
            year: "numeric",
            month: "long",
        });

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    // Fill in empty spaces for days of the previous month
    for (let i = 0; i < firstDay; i++) {
        const emptyDiv = document.createElement("div");
        calendarGrid.appendChild(emptyDiv);
    }

    // Fill in the actual days
    for (let day = 1; day <= daysInMonth; day++) {
        const dayDiv = document.createElement("div");
        dayDiv.className = "day";

        // Add date number
        const dayLabel = document.createElement("span");
        dayLabel.textContent = day;
        dayDiv.appendChild(dayLabel);

        const formattedDate = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

        // Fetch schedule data for the date
        fetch(`/labogon/Handler/fetch_schedule.php?date=${formattedDate}`)
            .then((response) => {
            if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
            .then((data) => {
                if (data.length > 0) {
                    const schedule = data[0]; // Assuming one schedule per day
                    

                    // Set color based on status
                    if (schedule.status === "To Pick Up") {
                        dayDiv.style.backgroundColor = "red";
                    } else if (schedule.status === "Done") {
                        dayDiv.style.backgroundColor = "green";
                    }

                    dayDiv.addEventListener("click", () => {
                    let modalContent = `<h3>Schedule Details for ${formattedDate}</h3>`;
                    modalContent += `<ul>`;
                    data.forEach((schedule) => {
                        modalContent += `
                            <li>
                                <strong>Driver:</strong> ${schedule.driver_name}<br>
                                <strong>Area:</strong> ${schedule.area}<br>
                                <strong>Status:</strong> ${schedule.status}<br>
                                <strong>Time:</strong> ${schedule.schedule_time}
                            </li>
                            <hr>
                        `;
                    });
                    modalContent += `</ul>`;

                    // Set modal body content, without overwriting the "Send Reminder" button
                    modalBody.innerHTML = modalContent;

                    // Check if the user is a Purok Leader and show the reminder button
                    if (<?php echo isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'purok_leader' ? 'true' : 'false'; ?>) {
                        const sendReminderButton = document.createElement('button');
                        sendReminderButton.id = 'send-reminder';
                        sendReminderButton.classList.add('send-reminder-btn');
                        sendReminderButton.textContent = 'Send Reminder';

                        sendReminderButton.addEventListener('click', () => {
                            // Implement reminder sending logic here
                            alert('Reminder Sent!');
                        });

                        modalBody.appendChild(sendReminderButton);
                    }

                    modal.style.display = "block";
                });
                }
            })
            // .catch((error) => console.error("Error fetching schedule:", error));

        calendarGrid.appendChild(dayDiv);
    }
}

// Add navigation functionality
document.getElementById("prev").addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
});

document.getElementById("next").addEventListener("click", () => {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
});

// Close the modal
closeModal.addEventListener("click", () => {
    modal.style.display = "none";
});

// Close the modal if the user clicks outside of it
window.addEventListener("click", (event) => {
    if (event.target === modal) {
        modal.style.display = "none";
    }
});

// Initial render
renderCalendar();


    </script>
 
</body>
</html>
