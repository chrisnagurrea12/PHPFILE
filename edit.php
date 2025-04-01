<?php
session_start();
include 'connection.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

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
    $lastname = $_POST['Lastname'] ?? '';
    $firstname = $_POST['Firstname'] ?? '';
    $middlename = $_POST['middlename'] ?? '';
    $course = $_POST['course'] ?? '';
    $level = $_POST['level'] ?? '';
    $email = $_POST['email'] ?? '';
    $address = $_POST['address'] ?? '';

    // Handle profile image upload
    $profile_image = $user['profile_image']; // Default to current image

    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        // Ensure the 'uploads' directory exists
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);  // Create directory if it doesn't exist
        }

        // Handle the file upload
        $uploadFile = $uploadDir . basename($_FILES['profile_image']['name']);
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $uploadFile)) {
            $profile_image = $uploadFile; // Update the profile image with the new path
        } else {
            echo "Error uploading profile image.";
            // Keep the old profile image in case of upload failure
        }
    }

    // Update the user's profile in the database
    $sql = "UPDATE users SET lastname=?, firstname=?, middlename=?, level=?, email=?, course=?, address=?, profile_image=? WHERE username=?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("SQL Error: " . $conn->error);
    }

    $stmt->bind_param("sssssssss", $lastname, $firstname, $middlename, $level, $email, $course, $address, $profile_image, $username);

    if ($stmt->execute()) {
        // Update session profile image with the new image
        $_SESSION['profile_image'] = $profile_image; // Store the new image in the session

        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: profile.php"); // Redirect to profile page
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
    <h1 style="margin-left:2%;  color: white;">SITIN MANAGEMENT SYSTEM</h1>
    
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
        <h2><b>PROFILE</b></h2>
        <div class="w3-display-topright" style="margin-top:7%; margin-right:5%">
            <button class="w3-red w3-button" onclick="window.location.href='profile.php'">← Back</button>
        </div>
    </div>

    <div class="w3-container" style="width: 50%; height: 10%; margin-left:25%; background-color: #035F95; border-radius: 10px;">
        <h2 style="font-size:20px; color:white"><strong>Edit Profile</strong></h2>
        <form method="POST" enctype="multipart/form-data">
            <div>
                <div class="w3-row">
                    <div style="margin-bottom:3px;" class="w3-third">
                        <label style="color:white">LASTNAME:</label>
                        <input class="w3-input w3-border" style="width:180px; border-radius: 25px; padding: 8px;" type="text" name="Lastname" value="<?php echo htmlspecialchars($user['lastname'] ?? ''); ?>" required>
                    </div>
                    <div style="margin-bottom:3px" class="w3-third">
                        <label style="color:white">FIRSTNAME:</label>
                        <input class="w3-input w3-border" style="width:180px; border-radius: 25px; padding: 8px;" type="text" name="Firstname" value="<?php echo htmlspecialchars($user['firstname'] ?? ''); ?>" required>
                    </div>
                    <div style="margin-bottom:3px" class="w3-third">
                        <label style="color:white">MIDDLENAME:</label>
                        <input class="w3-input w3-border" style="width:180px; border-radius: 25px; padding: 8px;" type="text" name="middlename" value="<?php echo htmlspecialchars($user['middlename'] ?? ''); ?>" required>
                    </div>
                </div>
                <div style="margin-bottom:3px">
                    <label style="color:white">EMAIL:</label>
                    <input class="w3-input w3-border" style="width:550px; border-radius: 25px; padding: 8px;" type="email" name="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                </div>
                <div class="w3-row">
                    <div style="margin-bottom:3px" class="w3-half">
                        <label style="color:white">COURSE:</label>
                        <select name="course" required class="w3-input w3-border" style="width:267px; border-radius: 25px; padding: 8px;">
                            <option value="" disabled>Select Course</option>
                            <option value="BSIT" <?php echo ($user['course'] == 'BSIT') ? 'selected' : ''; ?>>BSIT</option>
                            <option value="BSCS" <?php echo ($user['course'] == 'BSCS') ? 'selected' : ''; ?>>BSCS</option>
                            <option value="BSCE" <?php echo ($user['course'] == 'BSCE') ? 'selected' : ''; ?>>BSCE</option>
                            <option value="BSBA" <?php echo ($user['course'] == 'BSBA') ? 'selected' : ''; ?>>BSBA</option>
                            <option value="BSCJ" <?php echo ($user['course'] == 'BSCJ') ? 'selected' : ''; ?>>BSCJ</option>
                        </select>
                    </div>
                    <div style="margin-bottom:3px" class="w3-half">
                        <label style="color:white">LEVEL:</label>
                        <input class="w3-input w3-border" style="width:267px; border-radius: 25px; padding: 8px;" type="text" name="level" value="<?php echo htmlspecialchars($user['level'] ?? ''); ?>" required>
                    </div>
                </div>
                <div style="margin-bottom:3px">
                    <label style="color:white">ADDRESS:</label>
                    <input class="w3-input w3-border" style="width:550px; border-radius: 25px; padding: 8px;" type="text" name="address" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required>
                </div>
                <div style="margin-top:20px">
					<label style="color:white">Profile Image:</label>
					<input type="file" name="profile_image" accept="image/*">
				</div>
                <div style=" margin-bottom:5px">
                    <button type="button" class="w3-red w3-button" style="margin-left:85%" onclick="openSaveModal()">Save</button>
                </div>
            </div>
        </form>
    </div>

    <div id="saveModal" class="modal">
        <div class="modal-content" style="display: flex; justify-content: center; align-items: center; gap: 10px;">
    <p>Are you sure you want to save?</p>
    <div>
        <button id="proceedSave" class="w3-button w3-green">Proceed</button>
        <button id="cancelSave" class="w3-button w3-red">Cancel</button>
    </div>
</div>
    </div>

    <script>
        // Open the save confirmation modal
        function openSaveModal() {
            document.getElementById('saveModal').style.display = 'flex'; // Show modal
        }

        // Close the save confirmation modal
        function closeSaveModal() {
            document.getElementById('saveModal').style.display = 'none'; // Hide modal
        }

        // Proceed with saving action
        document.getElementById('proceedSave').addEventListener('click', function() {
            // Submit the form to update the profile
            document.querySelector('form').submit();
        });

        // Cancel the save action
        document.getElementById('cancelSave').addEventListener('click', closeSaveModal);

        // Show success message on page load
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($_SESSION['success_message'])): ?>
                alert("<?php echo $_SESSION['success_message']; ?>");
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
        });
    </script>
</div>
</body>
</html>
