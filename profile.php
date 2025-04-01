<?php
session_start();
include 'connection.php';

// Check login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Get user details
$sql = "SELECT lastname, firstname, middlename, course, level, email, address, session, profile_image FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// If user not found
if (!$user) {
    die("Error: User not found.");
}

// User info variables
$middlename_initial = !empty($user['middlename']) ? strtoupper(substr($user['middlename'], 0, 1)) . '.' : '';
$full_name = trim(($user['firstname'] ?? '') . " " . $middlename_initial . " " . ($user['lastname'] ?? ''));
$course = $user['course'] ?? 'Not Available';
$level = $user['level'] ?? 'Not Available';
$email = $user['email'] ?? 'Not Available';
$address = $user['address'] ?? 'Not Available';
$session = $user['session'] ?? 'Not Available';

// Profile image
$profile_image = !empty($user['profile_image']) && $user['profile_image'] !== 'cat.jpg'
    ? 'images/' . htmlspecialchars($user['profile_image'])
    : 'cat.jpg';

// Reserved session
$reserved_session = $_SESSION['reserved_session'] ?? 'No session reserved';

// Success message
if (isset($_SESSION['success_message'])) {
    echo "<script>alert('" . $_SESSION['success_message'] . "');</script>";
    unset($_SESSION['success_message']);
}
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

	<div class="w3-bar" style="border-bottom-style: solid; border-width: 1px;">
		<h2><b>PROFILE<b></h2>
		<div class="w3-display-topright" style="margin-top:7%; margin-right:5%">
		</div>
	</div>
	
	<button class="w3-red w3-button w3-display-topright" style="font-size:60%; margin-top:12%; margin-right:5%" onclick="window.location.href='edit.php'">EDIT PROFILE</button>
	
	 <div style="margin-left:3%; margin-top:2%" class="w3-container">
        <!-- Profile Information -->
        <div>
			<img src="<?php echo htmlspecialchars($user['profile_image'] ?: 'ID.jpeg'); ?>" alt="Pic" style="width:10%; height:10%; border-radius:50%;" >
            <div class="profile-details">
            <p><strong>Name:</strong> <?php echo htmlspecialchars($full_name); ?></p>
            <p><strong>Course:</strong> <?php echo htmlspecialchars($course); ?></p>
            <p><strong>level:</strong> <?php echo htmlspecialchars($level); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            <p><strong>Address:</strong> <?php echo htmlspecialchars($address); ?></p>
            <p><strong>Reserved Session:</strong> <?php echo htmlspecialchars($reserved_session); ?></p>
        </div>
	 </div>
	 </div>
	 
</div>
     
</body>
</html>

