<?php
    include 'db.php';
    $id = $_POST['id'];
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $conn->query("UPDATE events SET name='$name', description='$desc' WHERE id=$id");
    header("Location: dashboard.php");
?>
