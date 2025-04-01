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

    $sql = "SELECT u.id, u.username, u.email, s.purpose, s.laboratory, s.session 
            FROM users u
            LEFT JOIN sit_ins s ON u.id = s.user_id
            WHERE u.username LIKE '%$searchTerm%' OR u.email LIKE '%$searchTerm%'";

    $result = $conn->query($sql);

    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($users);
    exit;
}

// Handle sit-in form submission
if (isset($_POST['sit_in_submit'])) {
    // Sanitize and validate form data
    $userId = sanitizeInput($_POST['user_id']);
    $sitInDetails = sanitizeInput($_POST['sit_in_details']);

    $sql = "INSERT INTO sit_ins (user_id, details) VALUES ('$userId', '$sitInDetails')";
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
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}
.modal-content {
    background-color: #fefefe;
    margin: 15% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 50%;
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
  margin-left: 160px;
  padding: 0px 10px;
}
</style>
</head>
<body>

<div class="w3-bar" style="background-color: #035F95;">
    <h1 style="margin-left:2%; color: white;"> SIT-IN MANAGEMENT SYSTEM </h1>
    <div class="w3-display-topright">
        <button class="w3-red w3-button" style="margin-top:15%" onclick="window.location.href='login.php'">LOGOUT</button>
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
    <h2><b>SIT-IN<b></h2>
    <button id="openSearchModal" class="w3-button w3-red">Search User</button>

    <div id="searchModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeSearchModal">&times;</span>
            <h2>Search User</h2>
            <input type="text" id="searchInput" style="padding:10px;" placeholder="Search by username or email">
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
                <label>ID:</label>
                <input type="text" id="user_id_display" readonly><br>
                <label>Name:</label>
                <input type="text" id="username_display" readonly><br>
                <label>Details:</label><br>
                <textarea name="sit_in_details" id="sit_in_details"></textarea><br>
                <input type="submit" name="sit_in_submit" value="Submit">
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $("#openSearchModal").click(function () {
            $("#searchModal").show();
        });

        $(".close").click(function () {
            $(".modal").hide();
        });

        $("#searchButton").click(function () {
            var searchTerm = $("#searchInput").val();
            $.get("sit-in.php?search=" + searchTerm, function (data) {
                var resultsHtml = "<table border='1' width='100%'><tr><th>ID</th><th>Name</th><th>Purpose</th><th>Laboratory</th><th>Session</th><th>Action</th></tr>";
                $.each(data, function (index, user) {
                    resultsHtml += "<tr>" +
                        "<td>" + user.id + "</td>" +
                        "<td>" + user.username + "</td>" +
                        "<td>" + (user.purpose || 'N/A') + "</td>" +
                        "<td>" + (user.laboratory || 'N/A') + "</td>" +
                        "<td>" + (user.session || 'N/A') + "</td>" +
                        "<td><button class='select-user' data-id='" + user.id + "' data-username='" + user.username + "'>Select</button></td>" +
                        "</tr>";
                });
                resultsHtml += "</table>";
                $("#searchResults").html(resultsHtml);
            }, "json");
        });

        $(document).on("click", ".select-user", function () {
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

</body>
</html>
