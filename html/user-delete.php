<?php
session_start();
include "mental-health-db.php";

$roleKey = strtolower(str_replace([' ', '-', '_'], '', (string)($_SESSION['user_type'] ?? '')));
$return_to = $_GET['return_to'] ?? 'users.php';
$allowed_return_pages = ['users.php', 'guidance-counsellors.php', 'students.php', 'teachers.php'];
if (!in_array($return_to, $allowed_return_pages, true)) {
    $return_to = 'users.php';
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

if (!in_array($roleKey, ['manager', 'admin'], true)) {
    header("Location: " . ($return_to === 'students.php' ? 'students.php' : 'students.php'));
    exit();
}

$user_id8 = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if ($user_id8 > 0) {
    mysqli_query($conn, "DELETE FROM users WHERE user_id='$user_id8'");
}

header("Location: $return_to");
exit();
?>
