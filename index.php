<?php
session_start();

// SQL Azure connection (ODBC for SQL Server)
$connectionOptions = array(
    "Database" => getenv('DB_NAME'),
    "Uid" => getenv('DB_USER'),
    "PWD" => getenv('DB_PASS'),
    "Encrypt" => 1,
    "TrustServerCertificate" => 0
);

$serverName = getenv('DB_SERVER'); // e.g., your-sqlserver.database.windows.net
$conn = sqlsrv_connect($serverName, $connectionOptions);

if (!$conn) {
    die("❌ Connection failed: " . print_r(sqlsrv_errors(), true));
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $password = $_POST['psw'];

    $sql = "SELECT * FROM users WHERE name = ?";
    $params = array($name);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die("Query error: " . print_r(sqlsrv_errors(), true));
    }

    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user['name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid name or password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Event Horizon Portal - Login</title>
    <style>
        /* Same CSS as your original form */
        body {font-family: Arial;}
        form {border: 3px solid #f1f1f1;}
        input[type=text], input[type=password] {
          width: 100%; padding: 12px 20px; margin: 8px 0;
          display: inline-block; border: 1px solid #ccc; box-sizing: border-box;
        }
        button {
          background-color: #04AA6D; color: white;
          padding: 14px 20px; margin: 8px 0;
          border: none; cursor: pointer; width: 100%;
        }
        .container { padding: 16px; }
        .cancelbtn { background-color: #f44336; }
    </style>
</head>
<body>

<h2>Event Horizon Portal Login</h2>

<?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

<form method="POST" action="">
  <div class="container">
    <label><b>Name</b></label>
    <input type="text" placeholder="Enter Username" name="name" required>

    <label><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="psw" required>

    <button type="submit">Login</button>
  </div>
</form>

</body>
</html>
