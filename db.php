<?php
$host = "eventhorizonmysql.mysql.database.azure.com";
$username = "mysqladmin@eventhorizonmysql";
$password = "YourPassword";
$dbname = "eventhorizon";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

