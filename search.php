<?php
include 'connection.php';  // Include the connection file

// Backend logic for searching users from the database
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];

    // Ensure the search term is safe to use in SQL queries
    $searchTerm = htmlspecialchars($searchTerm);
    $searchTerm = $conn->real_escape_string($searchTerm);

    // Query to search for users in the 'users' table
    $sql = "SELECT id, username, email, full_name FROM users WHERE username LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%' OR full_name LIKE '%$searchTerm%'";

    // Execute the query
    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        // Fetch results
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    } else {
        $users = null;
    }
} else {
    $users = null;
}

// Query to fetch all announcements ordered by created_at in descending order (most recent first)
$sql = "SELECT id, title, content, created_at FROM announcements ORDER BY created_at DESC";

// Execute the query
$result = $conn->query($sql);

// Prepare the announcements array
$announcements = [];

// Check if there are any announcements
if ($result->num_rows > 0) {
    // Output each announcement and add it to the announcements array
    while ($row = $result->fetch_assoc()) {
        $announcements[] = [
            'title' => $row["title"],
            'content' => $row["content"],
            'created_at' => $row["created_at"]
        ];
    }
} else {
    $announcements = null; // No announcements
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
/* Add your custom styles here */
body {font-family: "Lato", sans-serif;}

.sidebar {
  height: 100%;
  margin-top:5.4%;
  width: 160px;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: black;
  overflow-x: hidden;
  padding-top: 16px;
}

.sidebar a {
  padding: 6px 8px 6px 16px;
  text-decoration: none;
  font-size: 20px;
  color: #818181;
  display: block;
}

.sidebar a:hover {
  color: #f1f1f1;
}

/* Modal styles */
.modal {
    display: none; /* Initially hidden */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    width: 350px; /* Modal width */
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
}

.modal-content p {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 20px;
}
.modal-content {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.w3-button {
    background-color: #035F95;
    color: white;
    border: none;
    padding: 10px 15px;
    font-size: 20px;
    border-radius: 5px;
    cursor: pointer;
}

.w3-button:hover {
    background-color: #0374C7;
}

.main {
  margin-left: 160px; /* Same as the width of the sidenav */
  padding: 0px 10px;
}

 #pieChart1 {
        width: 200px; /* Set width */
        height: 200px; /* Set height */
    }

@media screen and (max-height: 450px) {
  .sidebar {padding-top: 15px;}
  .sidebar a {font-size: 18px;}
}
</style>
</head>
<body>

<div class="w3-bar" style="background-color: #035F95;">
    <h1 style="margin-left:2%; color: white;"> SITIN MANAGEMENT SYSTEM </h1>
    <div class="w3-display-topright">
        <button class="w3-red w3-button" style="font-size:40%; margin-top:5%" onclick="window.location.href='login.php'">LOGOUT</button>
    </div>
</div>

<div class="sidebar">
  <div style="margin-top:15%;">
        <a href="admin-dashboard.php">Dashboard</a>
        <button onclick="openSearchModal()" class="w3-button w3-black" style="text-align: left;">Search</button>
        <a href="admin-announcement.php">Announcement</a>
        <a href="#contact">Contact</a>
    </div>
</div>

<div class="main">
    <div class="w3-bar" style="border-bottom-style: solid; border-width: 1px; margin-bottom:15px;">
        <h2><b>ADMIN<b></h2>
    </div>

    <div class="w3-row">
        <div class="w3-half w3-container">
            <div class="w3-bar" style="background-color:#035F95;">
                <h2 style="color:white; margin-left:5px;"><b>📢 ANNOUNCEMENT</b></h2>
            </div>
            <div style="margin-right:5px; width:100%; height:400px; overflow-y:auto; background-color: white; border-style: solid;">
                <!-- Centering the canvas -->
                <div class="center-canvas" style="width: 350px; height:350px;">
                    <canvas id="pieChart1"></canvas>
                </div>
            </div>
        </div>

 		<div class="w3-half w3-container">
			<div class="w3-bar" style="background-color:#035F95;">
				<h2 style="color:white; margin-left:5px;"><b>📢 ANNOUNCEMENT</b></h2>
			</div>
		<div style="margin-right:5px; width:100%; height:350px; overflow-y:auto; background-color: white; border-style: solid;">
		<div style="align-items: center; margin-left:30px;">
			<h1 style="font-size:20px; color:black"><b>New Announcement</b></h1>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
				<div style="margin-bottom:3px;">
					<label for="title" style="color:white;"><b>Title:</b></label>
					<input class="w3-input w3-border" style="width:95%; border-radius: 25px; padding: 8px;" type="text" id="title" name="title" required>
				</div>

				<div style="margin-bottom:3px;">
					<label for="content" style="color:white;"><b>Content:</b></label>
					<textarea class="w3-input w3-border" style="width:95%; border-radius: 25px; padding: 8px;" id="content" name="content" rows="5" cols="50" required></textarea>
				</div>

				<button type="submit" class="w3-button w3-green" style=" margin-top: 5px; font-size:10px;">Submit</button>
			</form>
		</div>
		
		<div>
		<h1 style="font-size:20px; color:black; text-align: center;"><b>Posted Announcement</b></h1>
               <?php
                // Check if there are any announcements
                if ($announcements !== null) {
                    // Loop through the announcements and display each one
                    foreach ($announcements as $announcement) {
                        echo '
                        <div class="w3-padding w3-card-4 w3-round-xlarge" style="width:95%; margin-right:2.5%; margin-left:2.5%; margin-top:10px;">
                            <h3>' . htmlspecialchars($announcement['title']) . ' | ' . date("Y-M-d", strtotime($announcement['created_at'])) . '</h3>
                            <p>' . nl2br(htmlspecialchars($announcement['content'])) . '</p>
                        </div>';
                    }
                } else {
                    // If no announcements are available
                    echo "<p>No announcements available.</p>";
                }
                ?>		
		</div>
		
		
		</div>
		</div>
    </div>

    <!-- Search Modal -->
    <div id="searchModal" class="modal">
        <div class="modal-content">
            <h2>Search Users</h2>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <label for="search">Search by Username, Email, or Full Name:</label>
                <input type="text" id="search" name="search" required placeholder="Enter search term...">
                <button type="submit">Search</button>
            </form>

            <!-- Display search results -->
            <?php if ($users !== null): ?>
                <?php if (count($users) > 0): ?>
                    <h3>Search Results</h3>
                    <ul>
                        <?php foreach ($users as $user): ?>
                            <li>
                                <strong><?php echo htmlspecialchars($user['username']); ?></strong><br>
                                Email: <?php echo htmlspecialchars($user['email']); ?><br>
                                Full Name: <?php echo htmlspecialchars($user['full_name']); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No users found matching your search term.</p>
                <?php endif; ?>
            <?php endif; ?>

            <button onclick="closeSearchModal()" class="w3-button w3-red">Close</button>
        </div>
    </div>

    <script>
// Modal functionality for search
function openSearchModal() {
    document.getElementById("searchModal").style.display = "flex";
}

function closeSearchModal() {
    document.getElementById("searchModal").style.display = "none";
}
    </script>

    <!-- Chart.js functionality for the pie chart -->
    <script>
        var ctx = document.getElementById('pieChart1').getContext('2d');
        var pieChart1 = new Chart(ctx, {
            type: 'pie',
            data: {
                 labels: ['C#', 'C', 'Java', 'ASP.Net', 'Php'],
                datasets: [{
                    label: 'Announcement Categories',
                    data: [25, 30, 20, 25],
                    backgroundColor: ['#FF5733', '#33FF57', '#3357FF', '#F1C40F'],
                    borderColor: ['#FFFFFF', '#FFFFFF', '#FFFFFF', '#FFFFFF'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        enabled: true
                    }
                }
            }
        });
    </script>
</div>

</body>
</html>
