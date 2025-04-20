<?php
include 'db_connect.php';

header('Content-Type: application/json');

$openStmt = $pdo_connect->query("SELECT open_date FROM magazine_closure_settings ORDER BY id DESC LIMIT 1");
$openRow = $openStmt->fetch(PDO::FETCH_ASSOC);

$openDate = $openRow['open_date'] ?? null;
$today = date('Y-m-d');

$response = [
    'status' => ($openDate && $today >= $openDate) ? 'open' : 'not_open',
    'open_date' => $openDate
];

echo json_encode($response);

?>