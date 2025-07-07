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
    header("Location: EventListing.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['event_date'];
    $location = $_POST['location'];
    $capacity = (int)$_POST['capacity'];

    $sql = "INSERT INTO Events (title, description, event_date, location, capacity) VALUES (?, ?, ?, ?, ?)";
    $params = [$title, $description, $date, $location, $capacity];
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header("Location: EventListing.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add New Event</title>
    <style>
        body {
            background: #f5f5f5;
            font-family: Arial, sans-serif;
        }
        h1 {
            text-align: center;
            padding-top: 30px;
            font-size: 28px;
        }
        .form-container {
            width: 60%;
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.08);
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            padding: 12px 10px;
            vertical-align: middle;
        }
        input, textarea {
            width: 90%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            display: block;
            margin: 20px auto 0 auto;
        }
        a {
            text-align: center;
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
            font-size: 15px;
        }
	.btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            color: #fff;
            cursor: pointer;
            margin: 10px 600px;
	    font-size: 13px;
	    background-color: #6833ff;
	    width: 20%;
        }

    </style>
</head>
<body>

<h1>Add New Event</h1>

<div class="form-container">
    <form method="POST">
        <table>
            <tr>
                <td>Title:</td>
                <td><input type="text" name="title" required></td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><textarea name="description" required></textarea></td>
            </tr>
            <tr>
                <td>Date:</td>
                <td><input type="date" name="event_date" required></td>
            </tr>
            <tr>
                <td>Location:</td>
                <td><input type="text" name="location" required></td>
            </tr>
            <tr>
                <td>Capacity:</td>
                <td><input type="number" name="capacity" required></td>
            </tr>
        </table>
        <button type="submit">+ Add Event</button>
    </form>
    
</div>
<a class="btn" href="EventListing.php">← Back to Dashboard</a>

</body>
</html>

