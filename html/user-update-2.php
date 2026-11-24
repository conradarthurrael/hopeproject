<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: mental-health-login.php");
    exit();
}
$roleKey = strtolower(str_replace([' ', '-', '_'], '', (string) ($_SESSION['user_type'] ?? '')));
$portalHomePage = [
    'teacher' => 'teacher-homepage.php',
    'student' => 'student-homepage.php',
    'manager' => 'manager-homepage.php',
    'admin' => 'manager-homepage.php',
    'guidancecounsellor' => 'guidance-counsellor-homepage.php',
    'guidancecounselor' => 'guidance-counsellor-homepage.php',
][$roleKey] ?? '../index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - H.O.P.E.</title>
<link rel="stylesheet" href="../assets/css/user-update-2.css">
</head>
<body>
<nav class="navbar">
      <div class="nav-container">
        <a class="portal-brand-link" href="<?= htmlspecialchars($portalHomePage, ENT_QUOTES) ?>"><img src="../images/hope-logo.png" alt="Logo" class="logo" /></a>
        <div class="nav-container2">
        <ul>
          <li><a href="<?= htmlspecialchars($portalHomePage, ENT_QUOTES) ?>">Home</a></li>
          <li><a href="mental-health-logout.php">Log Out</a></li>
        </ul>
</div>
      </div>
    </nav>
    <div class="updatecontainer1">
        <br>
        <br>
        <br>
        <br>
        <div class="updatecontainer2">
            <div class="updatecontainer3">
                <h1>Update User</h1>
                <?php
                include "mental-health-db.php";
                $user_id2 = $_GET['user_id'];
                if(isset($_POST['updatebutton'])){
                $name = $_POST['name'];
                $email = $_POST['email'];
                $age = $_POST['age'];
                $sex = $_POST['sex'];
                $address = $_POST['address'];
                $dob = $_POST['dob'];
                $contactno = $_POST['contactno'];
                $usertype = $_POST['usertype'];
                $tmp = $_FILES['profilepic']['tmp_name'];
                $profilepic = addslashes(file_get_contents($tmp));
                $user_status = "Approved";

                $updatesql = "UPDATE users SET name='$name', email='$email', age='$age'
                , sex='$sex', address='$address', date_of_birth='$dob', contactno='$contactno'
                , user_type='$usertype', user_status='$user_status', profilepic='$profilepic' WHERE user_id='$user_id2'";
                mysqli_query($conn, $updatesql);
                header("Location: manager-homepage.php");
                }
                $result = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$user_id2'");
                $doctor = mysqli_fetch_assoc($result);
                $querydoctor = "SELECT * FROM users WHERE user_id='$user_id2'";
                $querydoctor2 = mysqli_query($conn, $querydoctor);
                while($row = mysqli_fetch_assoc($querydoctor2)){
                ?>
                <form method="POST" enctype="multipart/form-data">
            <label>Name</label><br>
            <input type="text" name="name" placeholder="Enter Name" value="<?= $row['name']?>" required><br>
            <label>Email</label><br>
            <input type="text" name="email" placeholder="Enter Email" value="<?= $row['email']?>" required><br>
            <label>Age</label><br>
            <input type="text" name="age" placeholder="Enter Age" value="<?= $row['age']?>" required><br>
            <label>Sex</label><br>
            <select name="sex" class="sex" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select><br>
            <label>Address</label><br>
            <input type="text" name="address" placeholder="Enter Address" value="<?= $row['address']?>" required><br>
            <label>Contact No.</label><br>
            <input type="text" name="contactno" placeholder="Enter Contact No." value="<?= $row['contactno']?>" required><br>
            <label>Date of Birth</label><br>
            <input type="text" name="dob" placeholder="yyyy/mm/dd" value="<?= $row['date_of_birth']?>" required><br>
            <label>User Type</label><br>
            <select name="usertype" class="usertype" required>
            <option value="Student">Student</option>
                <option value="Guidancecounsellor">Guidance Counsellor</option>
                <option value="Teacher">Teacher</option>
                <option value="Manager">Manager</option>
            </select><br><br>
            <input type="file" name="profilepic" class="profilepic">
            <button type="submit" name="updatebutton" class="updatebutton">Update</button>
            </form>
            <?php } ?>
            </div>
        </div>
    </div>
    </body>
    </html>