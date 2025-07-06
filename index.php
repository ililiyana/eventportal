<?php
session_start();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Users WHERE name = ? AND password = ?";
    $params = array($name, $password);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $user = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    if ($user) {
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div style="background-color: #ffffff; padding: 40px; padding-top: 20px; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.1); width: 400px; margin-top: -60px;">
        <h1 style="text-align: center; margin-bottom: 30px;">Login</h1>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 10px 0;"><label for="name">Name :</label></td>
                    <td><input type="text" id="name" name="name" placeholder="Enter name" required
                        style="width: 90%; padding: 12px; border: 1px solid #ccc; border-radius: 6px;"></td>
                </tr>
                <tr>
                    <td style="padding: 10px 0;"><label for="password">Password :</label></td>
                    <td><input type="password" id="password" name="password" placeholder="Password" required
                        style="width: 90%; padding: 12px; border: 1px solid #ccc; border-radius: 6px;"></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center; padding-top: 30px;"><button type="submit" 
                        style="width: 30%; padding: 15px 20px; font-size: 13px; background-color: #007bff; color: #fff; border: none; border-radius: 6px; cursor: pointer;">
                        Login</button></td>
                </tr>
            </table>
        </form>
    </div>
</body>
</html>
