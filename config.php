<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = ''; // Default XAMPP MySQL password is empty
$database = 'blog';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
