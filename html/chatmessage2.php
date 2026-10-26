<?php
include "mhdb.php";
$sender_id = $_POST['sender_id'];
$receiver_id = $_POST['receiver_id'];
$typemessage = $_POST['typemessage'];
$messageinsert2 = "INSERT INTO messages(sender_id,receiver_id,message)
VALUES('$sender_id','$receiver_id','$typemessage');";
mysqli_query($conn, $messageinsert2);
header("Location: chat2.php?user_id=$receiver_id");
?>