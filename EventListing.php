<?php
// Connect to Azure SQL Server
$server = getenv('DB_SERVER');
$connectionOptions = array(
    "Database" => getenv('DB_NAME'),
    "Uid" => getenv('DB_USER'),
    "PWD" => getenv('DB_PASS'),
    "Encrypt" => 1,
    "TrustServerCertificate" => 0
);

$conn = sqlsrv_connect($server, $connectionOptions);
if (!$conn) {
    die("❌ Connection failed:<br>" . print_r(sqlsrv_errors(), true));
}

// Query events
$sql = "SELECT id, name, description, date FROM events";
$stmt = sqlsrv_query($conn, $sql);
if ($stmt === false) {
    die("❌ Query failed:<br>" . print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Event List</title>
  <style>
    table { border-collapse: collapse; width: 80%; margin: 20px auto; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background-color: #f5f5f5; }
    h2 { text-align: center; }
  </style>
</head>
<body>

<h2>List of Events</h2>

<table>
  <tr>
    <th>ID</th>
    <th>Name</th>
    <th>Description</th>
    <th>Date</th>
  </tr>

  <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
    <tr>
      <td><?= htmlspecialchars($row['id']) ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['description']) ?></td>
      <td><?= htmlspecialchars($row['date']->format('Y-m-d')) ?></td>
    </tr>
  <?php } ?>

</table>

</body>
</html>
