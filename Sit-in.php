<?php
// Assuming you have a database connection established in 'connection.php'
include 'connection.php';

// Function to sanitize input data
function sanitizeInput($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($conn, $data);
}

// Handle search query
if (isset($_GET['search'])) {
    $searchTerm = sanitizeInput($_GET['search']);

    $sql = "SELECT id, username, email FROM users WHERE username LIKE '%$searchTerm%' OR email LIKE '%$searchTerm%'";
    $result = $conn->query($sql);

    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    // Return results as JSON
    header('Content-Type: application/json');
    echo json_encode($users);
    exit;
}

// Handle sit-in form submission
if (isset($_POST['sit_in_submit'])) {
    // Sanitize and validate form data
    $userId = sanitizeInput($_POST['user_id']);
    $sitInDetails = sanitizeInput($_POST['sit_in_details']);
    // ... sanitize other form fields ...

    // Insert sit-in data into database
    $sql = "INSERT INTO sit_ins (user_id, details, ...) VALUES ('$userId', '$sitInDetails', ...)";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Sit-in processed successfully!');</script>";
    } else {
        echo "<script>alert('Error processing sit-in: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

.content {
    width: 90%;
    margin: 20px auto;
	align-items: center;
    background-color: #035F95;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.content h1 {
    margin-top: 0;
}

.controls {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.controls select {
    padding: 5px;
    margin-right: 10px;
}

.controls input[type="text"] {
    padding: 5px;
    margin-left: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}

.pagination {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.page-numbers a {
    border: 1px solid #ddd;
    padding: 5px 10px;
    margin-left: 5px;
    text-decoration: none;
    color: #333;
}

.page-numbers a:hover {
    background-color: #f0f0f0;
}

        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgb(0,0,0); /* Fallback color */
            background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto; /* 15% from the top and centered */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Could be more or less, depending on screen size */
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
.main {
  margin-left: 160px; /* Same as the width of the sidenav */
  padding: 0px 10px;
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
		<button class="w3-red w3-button" style="font-size:100%; margin-top:15%" onclick="window.location.href='login.php'">LOGOUT</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</div>

</div>



<div class="sidebar">
  <div style="margin-top:15%;">
        <a href="admin-dashboard.php">Dashboard</a>
		<a href="Sit-in.php">Sit-in</a>
        <a href="Sit-in Reports.php">Sit-in Reports</a>
        <a href="Feedback_reports.php">Feedback Reports</a>
    </div>
</div>


<div class="main">

	<div class="w3-bar" style="border-bottom-style: solid; border-width: 1px; margin-bottom:15px; ">
		<h2><b>SIT-IN<b></h2>
		<div class="w3-display-topright" style="margin-top:7%; margin-right:5%">
		</div>
	</div>
	
	<div id="searchModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeSearchModal">&times;</span>
        <h2>Search User</h2>
        <input type="text" id="searchInput" style="padding:10px;"placeholder="Search by username or email"><br>
		<br>
        <button id="searchButton" class="w3-button w3-red">Search</button>
        <div id="searchResults"></div>
    </div>
</div>

<div id="sitInModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeSitInModal">&times;</span>
        <h2>Sit-in Form</h2>
        <form method="post">
            <input type="hidden" name="user_id" id="sitInUserId">
            <label for="user_id_display">ID:</label>
            <input type="text" id="user_id_display" readonly><br>
            <label for="username_display">Name:</label>
            <input type="text" id="username_display" readonly><br>
            <label for="sit_in_details">Details:</label><br>
            <textarea name="sit_in_details" id="sit_in_details"></textarea><br>
            <input type="submit" name="sit_in_submit" value="Submit">
        </form>
    </div>
</div>

<button id="openSearchModal" style="margin-left:60px;" class="w3-button w3-red">Search User</button>

<script>
    $(document).ready(function () {
        // Open search modal
        $("#openSearchModal").click(function () {
            $("#searchModal").show();
        });

        // Close search modal
        $("#closeSearchModal").click(function () {
            $("#searchModal").hide();
        });

        // Close sit-in modal
        $("#closeSitInModal").click(function () {
            $("#sitInModal").hide();
        });

        // Search functionality
        $("#searchButton").click(function () {
            var searchTerm = $("#searchInput").val();
            $.get("your_script_name.php?search=" + searchTerm, function (data) {
                var resultsHtml = "<ul>";
                $.each(data, function (index, user) {
                    resultsHtml += "<li><a href='#' class='user-link' data-id='" + user.id + "' data-username='" + user.username + "'>" + user.username + " (" + user.email + ")</a></li>";
                });
                resultsHtml += "</ul>";
                $("#searchResults").html(resultsHtml);
            }, "json");
        });

        // Open sit-in modal and populate user ID and Name
        $(document).on("click", ".user-link", function () {
            var userId = $(this).data("id");
            var username = $(this).data("username");
            $("#sitInUserId").val(userId);
            $("#user_id_display").val(userId);
            $("#username_display").val(username);
            $("#searchModal").hide();
            $("#sitInModal").show();
        });
    });
</script>
	
	
	        <div class="content">
            <h1 style="color:white;">Current Sit-In</h1>
            <div class="controls">
                <select>
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
                <span style="color:white;">entries per page</span>
                <input type="text" placeholder="Search:">
            </div>
            <table>
                <thead>
                    <tr>
                        <th style="background-color: #035F95; color:white;">ID Number</th>
                        <th style="background-color: #035F95; color:white;">Name</th>
                        <th style="background-color: #035F95; color:white;">Sit Purpose</th>
                        <th style="background-color: #035F95; color:white;">Laboratory</th>
                        <th style="background-color: #035F95; color:white;">Login</th>
                        <th style="background-color: #035F95; color:white;">Logout</th>
                        <th style="background-color: #035F95; color:white;">Date</th>
                        <th style="background-color: #035F95; color:white;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8"  style="color:white;">No data available</td>
                    </tr>
                </tbody>
            </table>
            <div class="pagination">
                <span>Showing 1 to 1 of 1 entry</span>
                <div class="page-numbers">
                    <a href="#">1</a>
                </div>
            </div>
        </div>
    </div>
</div>
	


	 
</div>
     
</body>
</html>
