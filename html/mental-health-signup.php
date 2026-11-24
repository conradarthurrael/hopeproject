<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "mental-health-db.php";
    $address_column = $conn->query("SHOW COLUMNS FROM users LIKE 'address'");
    if ($address_column && $address_column->num_rows === 0) {
        $conn->query("ALTER TABLE users ADD COLUMN IF NOT EXISTS address VARCHAR(255) NULL");
    }
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$age = $_POST['age'];
$sex = $_POST['sex'];
$address = $_POST['address'];
$dob = $_POST['dob'];
$contactno = $_POST['contactno'];
$usertype = "Pending";
$tmp = $_FILES['profilepic']['tmp_name'];
$profilepic = addslashes(file_get_contents($tmp));
$user_status = "Approved";
$hashedpassword = password_hash($password, PASSWORD_DEFAULT);

$usersql = "INSERT INTO users(name, email, password, age, sex, address, date_of_birth, contactno, user_type, user_status, profilepic)
VALUES('$name', '$email', '$hashedpassword', '$age', '$sex', '$address', '$dob', '$contactno', '$usertype', '$user_status', '$profilepic');";
mysqli_query($conn, $usersql);
header("Location: ../index.php");
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - H.O.P.E.</title>
<link rel="stylesheet" href="../assets/css/mental-health-signup.css">
</head><body class="auth-page">
<nav class="navbar"><div class="nav-container"><a class="portal-brand-link" href="../index.php"><img src="../images/hope-logo.png" alt="H.O.P.E." class="logo"></a><div class="nav-container2"><ul><li><a href="../index.php#home">Home</a></li><li><a href="mental-health-login.php">Login</a></li><li><a href="mental-health-signup.php" aria-current="page">Sign Up</a></li></ul></div></div></nav>
<div class="registercontainer1"><div class="auth-layout auth-register-layout"><aside class="auth-side-panel"><span class="auth-panel-eyebrow">H.O.P.E. • GET STARTED</span><h2>Holistic Online Psychological &amp; Emotional Support</h2><p>Care that meets you with understanding and respect.</p><p class="auth-panel-switch">Already have an account? <a href="mental-health-login.php">Log in</a></p></aside><div class="registercontainer2"><div class="registercontainer3"><h1>Register</h1><form method="POST" action="mental-health-signup.php" enctype="multipart/form-data"><label for="register-name">Name</label><input id="register-name" type="text" name="name" placeholder="Enter Name" required><label for="register-email">Email</label><input id="register-email" type="email" name="email" placeholder="Enter Email" required><label for="register-password">Password</label><input id="register-password" type="password" name="password" placeholder="Enter Password" required><label for="register-age">Age</label><input id="register-age" type="text" name="age" placeholder="Enter Age" required><label for="register-sex">Sex</label><select id="register-sex" name="sex" class="sex" required><option value="Male">Male</option><option value="Female">Female</option></select><label for="register-address">Address</label><input id="register-address" type="text" name="address" placeholder="Enter Address" required><label for="register-contact">Contact No.</label><input id="register-contact" type="text" name="contactno" placeholder="Enter Contact No." required><label for="register-dob">Date of Birth</label><input id="register-dob" type="text" name="dob" placeholder="yyyy/mm/dd" required><label for="register-profile">Profile photo</label><input id="register-profile" type="file" name="profilepic" class="profilepic"><button type="submit" name="registerbutton" class="registerbutton">Register</button></form></div></div></div></div>
<script src="../assets/js/animations.js" defer></script></body></html>
