<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = sqlsrv_connect(getenv('DB_SERVER'), [
    "Database" => getenv('DB_NAME'),
    "Uid" => getenv('DB_USER'),
    "PWD" => getenv('DB_PASS'),
    "Encrypt" => 1,
    "TrustServerCertificate" => 0
]);

if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Organizer') {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['confirm_delete'])) {
    $id = (int)$_POST['id'];
    $sql = "DELETE FROM Events WHERE id = ?";
    $stmt = sqlsrv_query($conn, $sql, [$id]);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header("Location: dashboard.php");
    exit;
}

$id = (int)$_GET['id'];
$sql = "SELECT * FROM Events WHERE id = ?";
$stmt = sqlsrv_query($conn, $sql, [$id]);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$event = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if (!$event) {
    echo "Event not found.";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Event</title>
</head>
<body>
<h2>Confirm Delete Event</h2>
<p>Are you sure you want to delete this event?</p>
<strong><?= htmlspecialchars($event['title']) ?></strong><br><br>
<form method="POST">
    <input type="hidden" name="id" value="<?= $event['id'] ?>">
    <button type="submit" name="confirm_delete" style="background-color:red;color:white;padding:8px;">Yes, Delete</button>
    <a href="dashboard.php" style="padding:8px;background-color:gray;color:white;text-decoration:none;">Cancel</a>
</form>
</body>
</html>
