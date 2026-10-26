<?php
include "mhdb.php";
$guidance_id = $_POST['guidance_id'];
$userid2 = $_POST['user_id2'];
$concern = $_POST['concern'];
$reason = $_POST['reason'];
$appointmentdate = $_POST['appointmentdate'];

$appointmentinsert = "INSERT INTO appointments(guidance_id, user_id, concern, reason, appointmentdate)
VALUES('$guidance_id','$userid2','$concern','$reason','$appointmentdate');";
mysqli_query($conn, $appointmentinsert);
header("Location: studentappointments.php");
?>
