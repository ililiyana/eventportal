<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$role = $_SESSION['role'];
echo "Welcome, " . $role;
if ($role == 'organizer') {
    echo '<a href="add_event.php">Add Event</a> | <a href="logout.php">Logout</a>';
} else {
    echo '<a href="index.php">View Events</a> | <a href="logout.php">Logout</a>';
}
?>