<?php
// db_connection.php
$host = '127.0.0.1';
$dbname = 'universitymagazine_database';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8mb4'");
    return $pdo; // <== Important!
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}