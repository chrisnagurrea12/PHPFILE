<?php
session_start();
include 'connection.php'; // Your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // First, check in the "users" table for normal user role
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // User found
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Set session variables for user
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role; // Save the role in session

            // Redirect based on the role
            if ($role == 'admin') {
                header("Location: admin-dashboard.php"); // Redirect to admin dashboard
            } else {
                header("Location: dashboard.php"); // Redirect to user dashboard
            }
            exit;
        } else {
            echo "<script>alert('Incorrect password'); window.location='login.php';</script>";
        }
    } else {
    $stmt = $conn->prepare("SELECT id FROM admin WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id);
        $stmt->fetch();

        $_SESSION['admin_id'] = $id;
        $_SESSION['admin_username'] = $username;
        header("Location: admin-dashboard.php"); // Redirect to admin dashboard
        exit;
    } else {
        $error_message = "Invalid username or password.";
    }
    $stmt->close();
}
}
?>

<html>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

<body>

<form name="form" action="login.php" onsubmit="return isvalid()" method="POST" class="w3-container w3-display-middle w3-padding-16" style="border: 1px solid black; background-color: 035F95; height:75%; border-radius: 5%;">
	<img src="css logo.jpeg" style="width:15%; height:15%; margin-left:2%; border-radius:50%" >
    <img src="UCLOGO.jpg" style="width:15%; height:15%; margin-left:65%; border-radius:60%" >

    <h2 class="w3-display-topmiddle" style="font-size:19px; margin-top:8%; color: white;">
        <b><center>CCS Sit-in Monitoring System</center></b>
    </h2>
	
	
    <input class="w3-input w3-border" style="width:500px; border-radius: 25px; padding: 10px; margin-top:40px; margin-bottom:5%" type="text" id="username" name="username" placeholder="Username:">
    <input class="w3-input w3-border" style="width:500px; border-radius: 25px; padding: 10px; margin-bottom:5%" type="password" id="password" name="password" placeholder="Password:">

    <button class="w3-btn w3-red" style="width:250px; margin-left: 25%; margin-bottom: -5px; border-radius: 25px; padding: 10px" type="submit" id="btn" name="submit" >LOGIN</button>
	<p style="margin-bottom:5%; text-align: center; color: white;">_________________________________________________</p>
	<a href="Register.php" style="margin-left:37%; padding: 10px 20px; background-color: #4CAF50; color: white; text-align: center; text-decoration: none; border-radius: 5px; font-weight: bold;">REGISTER</a>

</form>

<script>
    function isvalid() {
        var user = document.form.username.value;
        var pass = document.form.password.value;

        if (user.length == 0 && pass.length == 0) {
            alert("Username and Password fields are empty");
            return false;
        }
        else {
            if (user.length == 0) {
                alert("Username is empty!!");
                return false;
            }

            if (pass.length == 0) {
                alert("Password is empty!!");
                return false;
            }
        }
    }
</script>

</body>
</html>
