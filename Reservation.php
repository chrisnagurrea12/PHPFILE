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
		<h2><b>RESERVATION<b></h2>
		<div class="w3-display-topright" style="margin-top:7%; margin-right:5%">
		</div>
	</div>
	


	 
</div>
     
</body>
</html>
