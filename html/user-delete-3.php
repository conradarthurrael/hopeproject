<?php
include "mental-health-db.php";
$user_id10 = $_GET['user_id'];
mysqli_query($conn, "DELETE FROM users WHERE user_id='$user_id10'");
header("Location: students.php");
?>