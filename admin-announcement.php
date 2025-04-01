<?php
session_start();
include 'connection.php'; // Include your database connection file

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the title and content from the form
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Validate inputs to ensure they're not empty
    if (empty($title) || empty($content)) {
        echo "<script>alert('Title and Content cannot be empty.'); window.location='admin-announcement.php';</script>";
        exit;
    }

    // Prepare the SQL statement to insert the announcement into the database
    $stmt = $conn->prepare("INSERT INTO announcements (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content); // Bind the parameters

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        // Redirect the admin to a page with a success message
        echo "<script>alert('Announcement added successfully!'); window.location='admin-dashboard.php';</script>";
    } else {
        // Show error message if the query failed
        echo "<script>alert('Failed to add announcement. Please try again.'); window.location='admin-announcement.php';</script>";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
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
        <a href="admin-dashboard.php">Dashboard</a>
		<a href="sit-in.php">Sit-in</a>
        <a href="admin-announcement.php">Announcement</a>
		<a href="#contact">Contact</a>
		<a href="#contact">Contact</a>
        <a href="#contact">Contact</a>
    </div>
</div>

<div class="main">
	<div class="w3-bar" style="border-bottom-style: solid; border-width: 1px; margin-bottom:15px;">
		<h2><b>ANNOUNCEMENT</b></h2>
	</div>

	<div class="w3-container w3-padding-16" style="width: 50%; height: 100%; margin-left:25%; background-color: #035F95; border-radius: 10px;">
		<h1 style="font-size:20px; color:white"><b>Create an Announcement</b></h1>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
			<div style="margin-bottom:3px;">
				<label for="title" style="color:white;"><b>Title:</b></label>
				<input class="w3-input w3-border" style="width:100%; border-radius: 25px; padding: 8px;" type="text" id="title" name="title" required>
			</div>

			<div style="margin-bottom:3px;">
				<label for="content" style="color:white;"><b>Content:</b></label>
				<textarea class="w3-input w3-border" style="width:100%; border-radius: 25px; padding: 8px;" id="content" name="content" rows="5" cols="50" required></textarea>
			</div>

			<button type="submit" class="w3-button w3-red" style=" margin-top: 5px;">Save Announcement</button>
		</form>
	</div>
</div>

</body>
</html>
