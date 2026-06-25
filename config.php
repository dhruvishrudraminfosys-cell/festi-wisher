<?php
session_start();

define('BASE_URL', '/festi-wisher/');

$host = "localhost";
$user = "root";
$pass = "";
$db   = "festi_wisher";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("DB Connection Failed");
}
?>