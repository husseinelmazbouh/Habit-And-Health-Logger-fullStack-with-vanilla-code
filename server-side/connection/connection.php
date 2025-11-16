<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json; charset=UTF-8");

$connection = new mysqli("localhost", "root", "", "health_logger");

if ($connection->connect_error) {
    die("connection error: " . $connection->connect_error);
}
echo"it works successfully , connected to db";
?>