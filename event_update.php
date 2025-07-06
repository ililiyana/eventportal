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

$id = (int)$_GET['id'];
$sql = "SELECT * FROM Events WHERE id = ?";
$stmt = sqlsrv_query($conn, $sql, [$id]);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

$event = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['event_date'];  // should be in yyyy-mm-dd
    $location = $_POST['location'];
    $capacity = (int)$_POST['capacity'];

    $update_sql = "UPDATE Events SET title = ?, description = ?, event_date = ?, location = ?, capacity = ? WHERE id = ?";
    $params = [$title, $description, $date, $location, $capacity, $id];
    $stmt = sqlsrv_query($conn, $update_sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    header("Location: dashboard.php");
    exit;
}

// Prepare date value for HTML input (yyyy-mm-dd)
$date_value = '';
if ($event && $event['event_date'] instanceof DateTime) {
    $date_value = $event['event_date']->format('Y-m-d');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
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
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }
        button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            display: block;
            margin: 20px auto 0 auto;
        }
        a.btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            color: #fff;
            cursor: pointer;
            display: block;
            width: 20%;
            background-color: #6833ff;
            text-align: center;
            margin: 30px auto 0 auto;
        }
    </style>
</head>
<body>

<h1>Edit Event</h1>

<div class="form-container">
    <form method="POST">
        <table>
            <tr>
                <td>Title:</td>
                <td><input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required></td>
            </tr>
            <tr>
                <td>Description:</td>
                <td><textarea name="description" required><?= htmlspecialchars($event['description']) ?></textarea></td>
            </tr>
            <tr>
                <td>Date:</td>
                <td><input type="date" name="event_date" value="<?= $date_value ?>" required></td>
            </tr>
            <tr>
                <td>Location:</td>
                <td><input type="text" name="location" value="<?= htmlspecialchars($event['location']) ?>" required></td>
            </tr>
            <tr>
                <td>Capacity:</td>
                <td><input type="number" name="capacity" value="<?= $event['capacity'] ?>" required></td>
            </tr>
        </table>
        <button type="submit">Update Event</button>
    </form>
</div>

<a class="btn" href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
