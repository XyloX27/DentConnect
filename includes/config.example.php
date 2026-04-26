<?php
// Database configuration - Copy this file to config.php and update credentials
$host = 'localhost';
$dbname = 'dentconnect';
$username = 'root'; // change if needed
$password = '';     // change if needed

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$conn->set_charset("utf8");
session_start();
?>