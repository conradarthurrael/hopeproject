<?php
include "mhdb.php";
$user_id4 = $_POST['user_id'];
$behavior = $_POST['behavior'];
$report = $_POST['report'];
$incidentdate = $_POST['incidentdate'];

$reportinsert = "INSERT INTO reports(user_id, behavior, report, incidentdate)
VALUES('$user_id4','$behavior','$report','$incidentdate');";
mysqli_query($conn, $reportinsert);
header("Location: reportstudents.php");
?>