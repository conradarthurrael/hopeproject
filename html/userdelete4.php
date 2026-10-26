<?php
include "mhdb.php";
$user_id11 = $_GET['user_id'];
mysqli_query($conn, "DELETE FROM users WHERE user_id='$user_id11'");
header("Location: teachers.php");
?>