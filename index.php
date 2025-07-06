<?php
include 'db.php';
$result = $conn->query("SELECT * FROM events");
while ($event = $result->fetch_assoc()) {
    echo "<h3>{$event['name']}</h3><p>{$event['description']}</p><hr>";
}
?>
