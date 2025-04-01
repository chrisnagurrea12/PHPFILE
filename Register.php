<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $idno = $_POST['IDNO'];
    $lastname = $_POST['Lastname'];
    $firstname = $_POST['Firstname'];
    $middlename = $_POST['middlename'];
    $course = $_POST['course'];
    $level = $_POST['level'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (idno, lastname, firstname, middlename, course, level, username, password) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $idno, $lastname, $firstname, $middlename, $course, $level, $username, $password);
    
    if ($stmt->execute()) {
        echo "<script>console.log('Hashed Password: " . $password . "');</script>";
        echo "<script>alert('Registration successful!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "'); window.location='Register.php';</script>";
    }
    $stmt->close();

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://www.phptutorial.net/app/css/style.css">
	<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <title>Register</title>
</head>
<!-- <style>
	body {
	background-image: url("UC.jpg");
	
}
</style> -->

<style>
	a:hover {
  background-color: #D52B2B;
  
}
</style>

<body>
<main>
    <form action="register.php" method="post" style="background-color: #035F95; border-radius: 3%;">
        <h1 style="color: white;">REGISTER</h1>
		<div style="margin-bottom:5%;">
            <input type="text" name="IDNO" id="IDNO" placeholder="IDNO:" required>
        </div>
        <div style="margin-bottom:5%;">
            <input type="text" name="Firstname" id="Firstname" placeholder="FIRSTNAME:" required>
        </div>
        <div style="margin-bottom:5%;">
            <input type="middlename" name="middlename" id="middlename" placeholder="MIDDLENAME:">
        </div>
        <div style="margin-bottom:5%;">
            <input type="Lastname" name="Lastname" id="Lastname" placeholder="LASTNAME:" required>
        </div>
        <div class="form-group" style="margin-bottom:5%;">
            <select id="level" name="level" required placeholder="LEVEL:" required>
                <option value="">Select Level</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>

		<div class="form-group" style="margin-bottom:5%;">
            <select id="course" name="course" required placeholder="COURSE:" required>
                <option value="">Select Course</option>
            <option value="BSCPE">BSCPE</option>
            <option value="BSCS">BSCS</option>
            <option value="BSIT">BSIT</option>
            <option value="BSBA">BSBA</option>
            <option value="BSCJ">BSCJ</option>
            </select>
        </div>

		<div style="margin-bottom:5%;">
            <input type="username" name="username" id="username" placeholder="USERNAME:" required>
        </div>
        <div style="margin-bottom:5%;"> 
            <input type="password" name="password" id="password" placeholder="PASSWORD:" required>
        </div>
		
        <button type="submit" value="SignUp" style="margin-top:3%; margin-bottom: 3%; background-color:#4CAF50;">Register</button>
		
        <footer style="color:white;">Already a member? <a href="login.php">Login here.</a></footer>
    </form>
</main>
</body>
</html>