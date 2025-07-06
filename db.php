<?php
$connectionString = getenv("DB_CONNECTION_STRING");

// Parse the connection string
preg_match("/Server=(.*);Initial Catalog=(.*);User ID=(.*);Password=(.*);/", $connectionString, $matches);
$server = $matches[1];
$database = $matches[2];
$user = $matches[3];
$pass = $matches[4];

$connectionOptions = [
    "Database" => $database,
    "UID" => $user,
    "PWD" => $pass,
    "Encrypt" => true
];

$conn = sqlsrv_connect($server, $connectionOptions);
if (!$conn) {
    die(print_r(sqlsrv_errors(), true));
}
?>
