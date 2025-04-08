<?php
// db_connection.php
$host = '127.0.0.1';
$dbname = 'universitymagazine_database';
$username = 'root';
$password = '';

try {
    $pdo_connect = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo_connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo_connect->exec("SET NAMES 'utf8mb4'");
    return $pdo_connect;
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}