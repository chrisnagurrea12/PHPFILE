<?php
include 'connection.php';

// Fetch data from feedback table
$sql = "SELECT student_id, laboratory, date, message FROM feedback";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Report</title>
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
            margin-left: 160px;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #007BFF;
            color: white;
        }

        .print-btn {
            display: block;
            width: 100px;
            margin: 20px auto;
            padding: 10px;
            background: #28a745;
            color: white;
            text-align: center;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .print-btn:hover {
            background: #218838;
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
        <h2 style="text-align:left;"><b>FEEDBACK REPORT</b></h2>
    </div>
    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Student ID Number</th>
                    <th>Laboratory</th>
                    <th>Date</th>
                    <th>Message</th>
                </tr>
				<tbody>
				 <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['student_id']) . "</td>
                                <td>" . htmlspecialchars($row['laboratory']) . "</td>
                                <td>" . htmlspecialchars($row['date']) . "</td>
                                <td>" . htmlspecialchars($row['message']) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No feedback found</td></tr>";
                }
                $conn->close();
                ?>
				</tbody>
            </thead>
        </table>
        <a href="#" class="print-btn" onclick="window.print();">Print</a>
    </div>
</div>

</body>
</html>