<?php
session_start();
include("mhdb.php");
$error = "";
if(isset($_POST['loginbutton'])){
$email = $_POST['email'];
$password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION["user_id"] = $row['user_id'];
    $_SESSION["email"] = $email;
    $_SESSION["password"] = $password;
    $_SESSION["user_type"] = $row['user_type'];
    $_SESSION["name"] = $row['name'];
    $_SESSION["user_status"] = $row['user_status'];
    if($row['user_type'] == 'Manager'&&$row['user_status'] =='Approved'){
    header("Location: managerhomepage.php");
    }
    else if($row['user_type'] == 'Student'&&$row['user_status'] =='Approved'){
        header("Location: studenthomepage.php");
    }
    else if($row['user_type'] == 'Teacher'&&$row['user_status'] =='Approved'){
        header("Location: teacherhomepage.php");
    }
    else if($row['user_type'] == 'Guidancecounsellor'&&$row['user_status'] =='Approved'){
        header("Location: gchomepage.php");
    }
    else {
        echo '<script>
        window.location.href = "index.php";
        alert("Login failed, Account not approved")</script>';
    }
    exit();
}
    else {
    echo '<script>
    window.location.href = "mentalhospitalhomepage.php";
    alert("Login failed, Invalid email or password")</script>';
}
}
?>