<?php
session_start();
include 'connection.php';

// Check if admin is logged in (replace with your actual authentication logic)
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php"); // Redirect if not logged in
    exit;
}

include 'connection.php'; // Your database connection

$message = ""; // Initialize message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = date("Y-m-d H:i:s"); // Current date and time

    try {
        $stmt = $conn->prepare("INSERT INTO announcements (title, content, date) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $date]);
        $message = "<p style='color: green;'>Announcement posted successfully!</p>";
    } catch (PDOException $e) {
        $message = "<p style='color: red;'>Error posting announcement: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Announcement</title>
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
</head>
<body>

<div class="w3-container w3-display-middle w3-padding-16" style="border: 1px solid black; background-color: #f0f0f0; width: 500px;">
    <h2>Create Announcement</h2>

    <?php echo $message; ?>

    <form method="post" action="announcement_form.php">
        <label for="title">Title:</label>
        <input class="w3-input w3-border" type="text" name="title" id="title" required><br>

        <label for="content">Content:</label>
        <textarea class="w3-input w3-border" name="content" id="content" rows="5" required></textarea><br>

        <button class="w3-btn w3-blue" type="submit">Post Announcement</button>
    </form>
</div>

</body>
</html>