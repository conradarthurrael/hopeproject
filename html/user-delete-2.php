<?php
session_start();
include "mental-health-db.php";

$roleKey = strtolower(str_replace([' ', '-', '_'], '', (string)($_SESSION['user_type'] ?? '')));
if (!isset($_SESSION['user_id']) || !in_array($roleKey, ['manager', 'admin'], true)) {
    header("Location: guidance-counsellors.php");
    exit();
}

$user_id9 = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if ($user_id9 > 0) {
    mysqli_query($conn, "DELETE FROM users WHERE user_id='$user_id9'");
}

header("Location: guidance-counsellors.php");
exit();
?>