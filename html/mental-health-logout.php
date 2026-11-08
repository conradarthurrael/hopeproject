<?php
include "mental-health-db.php";
session_start();
$user_id8 = $_SESSION['user_id'];
if(isset($user_id8)){
$removestatus = "UPDATE users SET last_activity=NULL WHERE user_id = '$user_id8'";
mysqli_query($conn, $removestatus);
}
session_unset();
session_destroy();
header("Location: ../index.php");
exit();
?>