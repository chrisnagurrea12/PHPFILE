<?php
session_start();
include 'connection.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Enable MySQL error reporting

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo "User not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$idno = $_POST['IDNO'];
    $lastname = $_POST['Lastname'] ?? '';
    $firstname = $_POST['Firstname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $course = $_POST['course'] ?? '';
    $level = $_POST['level'] ?? '';
    $email = $_POST['email'] ?? '';
    $course = $_POST['course'] ?? '';
    $address = $_POST['address'] ?? '';

    $sql = "UPDATE users SET idno=?, lastname=?, firstname=?, middlename=?, level=?, email=?, course=?, address=? WHERE username=?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Error: " . $conn->error); // Debugging SQL error
    }

    $stmt->bind_param("sssssssss", $idno, $lastname, $firstname, $middlename, $level, $email, $course, $address, $username);
    
    if ($stmt->execute()) {
        // Set success message before redirecting
        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: dashboard.php"); // Redirect to dashboard after successful update
        exit();
    } else {
        echo "Error updating profile.";
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
<style>
body {font-family: "Lato", sans-serif;}

body {
    font-family: sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f4f4f4;
}

.nav {
    background-color: #333;
    color: white;
    padding: 10px 20px;
    display: flex;
    justify-content: flex-end;
}

nav a {
    color: white;
    text-decoration: none;
    margin-left: 15px;
}

nav a.logout {
    background-color: #d9534f;
    padding: 5px 10px;
    border-radius: 5px;
}

.content {
    width: 90%;
    margin: 20px auto;
    background-color: white;
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

	<div class="w3-bar" style="border-bottom-style: solid; border-width: 1px; margin-bottom:15px; ">
		<h2><b>HISTORY<b></h2>
		<div class="w3-display-topright" style="margin-top:7%; margin-right:5%">
		</div>
	</div>

        <div class="content">
            <h1>History Information</h1>
            <div class="controls">
                <select>
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
                <span>entries per page</span>
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
                        <td colspan="8">No data available</td>
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
     
</body>
</html>
