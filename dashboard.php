<?php
// Include the connection file
include 'connection.php';

// Query to fetch all announcements ordered by created_at in descending order (most recent first)
$sql = "SELECT id, title, content, created_at FROM announcements ORDER BY created_at DESC";

// Execute the query
$result = $conn->query($sql);

// Prepare the announcements array
$announcements = [];

// Check if there are any announcements
if ($result->num_rows > 0) {
    // Output each announcement and add it to the announcements array
    while($row = $result->fetch_assoc()) {
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
<style>
body {font-family: "Lato", sans-serif;}

.sidebar {
  height: 100%;
  margin-top:5.4%;
  width: 160px;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #111;
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

.modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
        }

        /* Modal Content */
        .modal-content {
            background-color: #035F95;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            max-width: 500px;
            border-radius: 8px;
            text-align: center;
        }

        /* Close button */
        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        /* Button to trigger modal */
        .open-btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        .open-btn:hover {
            background-color: #45a049;
        }


.main {
  margin-left: 160px; /* Same as the width of the sidenav */
  padding: 0px 10px;
}

@media screen and (max-height: 490px) {
  .sidebar {padding-top: 15px;}
  .sidebar a {font-size: 18px;}
}
</style>
</head>
<body>

<div class="w3-bar" style="background-color: #035F95;">
	<h1 style="margin-left:2%;  color: white;"> SITIN MANAGEMENT SYSTEM <h1>
	
	<div class="w3-display-topright">
		<button class="w3-red w3-button" style="font-size:40%; margin-top:5%" onclick="window.location.href='login.php'">LOGOUT</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</div>

</div>


<div class="sidebar">
<div style="margin-top:15%;">
        <a href="profile.php">Profile</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="Reservation.php"> Reservation</a>
        <a href="History.php">History</a>
        <a href="#contact">Contact</a>
    </div>
</div>

<div class="main">

	<div class="w3-bar" style="border-bottom-style: solid; border-width: 1px;">
		<h2><b>DASHBOARD<b></h2>
		<div class="w3-display-topright" style="margin-top:7%; margin-right:5%">
		</div>
	</div>
	<p> </p>
	
    <div class="w3-row">
        <div class="w3-half w3-container">
            <div class="w3-bar" style="background-color:#035F95;">
                <h2 style="color:white; margin-left:5px;"><b>📢 ANNOUNCEMENT</b></h2>
            </div>
            <div style="margin-right:5px; width:100%; height:350px; overflow-y:auto; background-color: white; border-style: solid;">

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
		
    <div class="w3-half w3-container">
    <div class="w3-bar" style="background-color:#035F95;">
      <h2 style="color:white; margin-left:5px;"><b>RULES AND REGULATIONS</b></h2>
    </div>
	<div  style="margin-right:5px; width:100%; height:350px; overflow-y:auto; background-color: white; border-style: solid;">
    <h1 style="text-align:center;"><b>University of Cebu</b></h1>
    <h2 style="text-align:center;"><b>COLLEGE OF INFORMATION & COMPUTER STUDIES</b></h2>
    <h3 style="text-align:center;"><b>LABORATORY RULES AND REGULATIONS</b></h3>
	
    <p>To avoid embarrassment and maintain camaraderie with your friends and superiors at our laboratories, please observe the following:</p>
    <ol>
      <li>Maintain silence, proper decorum, and discipline inside the laboratory. Mobile phones, walkmans and other personal pieces of equipment must be switched off.</li>
      <li>Games are not allowed inside the lab. This includes computer-related games, card games and other games that may disturb the operation of the lab.</li>
      <li>Surfing the Internet is allowed only with the permission of the instructor. Downloading and installing of software are strictly prohibited.</li>
      <li>Getting access to other websites not related to the course (especially pornographic and illicit sites) is strictly prohibited.</li>
      <li>Deleting computer files and changing the set-up of the computer is a major offense.</li>
      <li>Observe computer time usage carefully. A fifteen-minute allowance is given for each use. Otherwise, the unit will be given to those who wish to "sit-in".</li>
      <li>Observe proper decorum while inside the laboratory.
        <ul>
          <li>Do not get inside the lab unless the instructor is present.</li>
          <li>All bags, knapsacks, and the likes must be deposited at the counter.</li>
          <li>Follow the seating arrangement of your instructor.</li>
          <li>At the end of class, all software programs must be closed.</li>
          <li>Return all chairs to their proper places after using.</li>
        </ul>
      </li>
      <li>Chewing gum, eating, drinking, smoking, and other forms of vandalism are prohibited inside the lab.</li>
      <li>Anyone causing a continual disturbance will be asked to leave the lab. Acts or gestures offensive to the members of the community, including public display of physical intimacy, are not tolerated.</li>
      <li>Persons exhibiting hostile or threatening behavior such as yelling, swearing, or disregarding requests made by lab personnel will be asked to leave the lab.</li>
      <li>For serious offense, the lab personnel may call the Civil Security Office (CSU) for assistance.</li>
      <li>Any technical problem or difficulty must be addressed to the laboratory supervisor, student assistant or instructor immediately.</li>
    </ol>
    <h3 style="text-align:center;"><b>DISCIPLINARY ACTION</b></h3>
    <ul>
      <li>First Offense - The Head or the Dean or OIC recommends to the Guidance Center for a suspension from classes for each offender.</li>
      <li>Second and Subsequent Offenses - A recommendation for a heavier sanction will be endorsed to the Guidance Center.</li>
    </ul>
	</div>
  </div>
</div>
	
</div>
     
</body>
</html> 
