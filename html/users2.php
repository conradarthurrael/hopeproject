<?php

session_start();

require "mhdb.php";

header("Content-Type: application/json");

if(!isset($_SESSION['user_id'])){
    http_response_code(401);
    echo json_encode([]);
    exit;
}

$my_id = (int)
$_SESSION['user_id'];

$stmt->bind_param("i", $my_id);
$stmt->execute();

$stmt = $conn->prepare(
    "SELECT user_id, name, profilepic AS is_online FROM users WHERE user_id != ? ORDER BY name"
);

$stmt->bind_param("i", $my_id);
$stmt->execute();

$result = $stmt->get_result();
$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

echo json_encode($users);