<?php
include "mhdb.php";
$user_id8 = $_GET['user_id'];
mysqli_query($conn, "DELETE FROM users WHERE user_id='$user_id8'");
header("Location: users.php");
?>