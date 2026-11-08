<?php
include "mental-health-db.php";
$user_id9 = $_GET['user_id'];
mysqli_query($conn, "DELETE FROM users WHERE user_id='$user_id9'");
header("Location: guidance-counsellors.php");
?>