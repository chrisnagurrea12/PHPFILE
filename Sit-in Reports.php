<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Reports</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
    margin-left: 160px; /* Match sidebar width */
    padding: 20px; /* Add uniform spacing */
    width: calc(100% - 160px); /* Prevent overflow */
}


@media screen and (max-height: 450px) {
  .sidebar {padding-top: 15px;}
  .sidebar a {font-size: 18px;}
}
    </style>
</head>
<body>

<div class="w3-bar" style="background-color: #035F95; padding:6px;">
    <h1 style="margin-left:1.5%; color: white;"> SITIN MANAGEMENT SYSTEM </h1>
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
        <h2 style="text-align:left;">GENERATE REPORT</b></h2>
    </div>

    <div class="">
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="date" class="form-control" id="dateFilter">
            </div>
            <div class="col-md-2">
                <button class="btn btn-primary">Search</button>
                <button class="btn btn-secondary">Reset</button>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-success">CSV</button>
                <button class="btn btn-warning">Excel</button>
                <button class="btn btn-danger">PDF</button>
                <button class="btn btn-info">Print</button>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>Purpose</th>
                    <th>Laboratory</th>
                    <th>Login</th>
                    <th>Logout</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>123</td>
                    <td>John Doe</td>
                    <td>Java Programming</td>
                    <td>547</td>
                    <td>11:32 AM</td>
                    <td>12:32 PM</td>
                    <td>2024-03-21</td>
                </tr>
                <tr>
                    <td>2000</td>
                    <td>Jane Smith</td>
                    <td>C Programming</td>
                    <td>524</td>
                    <td>10:48 AM</td>
                    <td>11:48 AM</td>
                    <td>2024-03-20</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
