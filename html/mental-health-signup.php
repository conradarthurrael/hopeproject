<?php
include "mental-health-db.php";
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$age = $_POST['age'];
$sex = $_POST['sex'];
$address = $_POST['address'];
$dob = $_POST['dob'];
$contactno = $_POST['contactno'];
$usertype = "PENDING";
$tmp = $_FILES['profilepic']['tmp_name'];
$profilepic = addslashes(file_get_contents($tmp));
$user_status = "Pending";

$usersql = "INSERT INTO users(name, email, password, age, sex, address, date_of_birth, contactno, user_type, user_status, profilepic)
VALUES('$name', '$email', '$password', '$age', '$sex', '$address', '$dob', '$contactno', '$usertype', '$user_status', '$profilepic');";
mysqli_query($conn, $usersql);
header("Location: ../index.php");
?>