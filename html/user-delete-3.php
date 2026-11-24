<?php
session_start();
include "mental-health-db.php";

$roleKey = strtolower(str_replace([' ', '-', '_'], '', (string)($_SESSION['user_type'] ?? '')));
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}
if (!in_array($roleKey, ['manager', 'admin'], true)) {
    header("Location: students.php");
    exit();
}

$user_id10 = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if ($user_id10 > 0) {
    mysqli_query($conn, "DELETE FROM users WHERE user_id='$user_id10'");
}

header("Location: students.php");
exit();
?>