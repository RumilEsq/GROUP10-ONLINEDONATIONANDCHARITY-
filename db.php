<?php
$host = 'localhost';
$port = '3307';
$dbname = 'onlinedonation_charity';
$username = 'root';
$password = ''; // Empty password for XAMPP

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>