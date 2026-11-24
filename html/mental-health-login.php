<?php
session_start();
include "mental-health-db.php";
$error = "";
if(isset($_POST['loginbutton'])){
$email = $_POST['email'];
$password = $_POST['password'];
$query = "SELECT email,password FROM users WHERE email = '$email'";
$query2 = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($query2);
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    if(mysqli_num_rows($result) == 1&&password_verify($password, $row['password'])) {
        $row = mysqli_fetch_assoc($result);
        $_SESSION["user_id"] = $row['user_id'];
    $_SESSION["email"] = $email;
    $_SESSION["user_type"] = $row['user_type'];
    $_SESSION["name"] = $row['name'];
    $_SESSION["user_status"] = $row['user_status'];
    if($row['user_type'] == 'Manager'&&$row['user_status'] =='Approved'){
    header("Location: manager-homepage.php");
    }
    else if($row['user_type'] == 'Student'&&$row['user_status'] =='Approved'){
        header("Location: student-homepage.php");
    }
    else if($row['user_type'] == 'Teacher'&&$row['user_status'] =='Approved'){
        header("Location: teacher-homepage.php");
    }
    else if($row['user_type'] == 'Guidancecounsellor'&&$row['user_status'] =='Approved'){
        header("Location: guidance-counsellor-homepage.php");
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
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - H.O.P.E.</title>
<link rel="stylesheet" href="../assets/css/mental-health-login.css">
</head>
<body class="auth-page">
    <nav class="navbar">
        <div class="nav-container">
            <a class="portal-brand-link" href="../index.php"><img src="../images/hope-logo.png" alt="H.O.P.E." class="logo"></a>
            <div class="nav-container2"><ul>
                <li><a href="../index.php#home">Home</a></li>
                <li><a href="mental-health-login.php" aria-current="page">Login</a></li>
                <li><a href="mental-health-signup.php">Sign Up</a></li>
            </ul></div>
        </div>
    </nav>
    <div class="auth-layout">
      <aside class="auth-side-panel">
        <span class="auth-panel-eyebrow">H.O.P.E. • YOUR WELLBEING MATTERS</span>
        <h2>Holistic Online Psychological &amp; Emotional Support</h2>
        <p>A welcoming place to find guidance, practical support, and a path forward at your pace.</p>
        <ul class="auth-benefits">
          <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 4.5 6v5.5c0 4.8 3.2 7.8 7.5 9.5 4.3-1.7 7.5-4.7 7.5-9.5V6L12 3Z"/><path d="m9 12 2 2 4-4"/></svg><span>Confidential one-on-one guidance chat</span></li>
          <li><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18M8 14h3"/></svg><span>Easy appointment scheduling</span></li>
          <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6.5-8 11-8 11Z"/><path d="M8 12h2l1-2 2 4 1-2h2"/></svg><span>Personalized wellbeing support</span></li>
        </ul>
        <p class="auth-panel-switch">New to H.O.P.E.? <a href="mental-health-signup.php">Create an account</a></p>
      </aside>
      <main class="auth-card auth-form-card">
        <h1>Welcome back</h1>
        <p>Log in to continue to your H.O.P.E. account.</p>
        <form method="POST" action="mental-health-login.php">
            <label for="login-email">Email</label>
            <div class="field-with-icon">
                <span class="field-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="m3 7 9 6 9-6"></path></svg></span>
                <input type="email" id="login-email" name="email" placeholder="Enter your email" autocomplete="username" required>
            </div>
            <label for="login-password">Password</label>
            <div class="field-with-icon">
                <span class="field-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><rect x="4" y="10" width="16" height="11" rx="2"></rect><path d="M8 10V7a4 4 0 0 1 8 0v3"></path></svg></span>
                <input type="password" id="login-password" name="password" placeholder="Enter your password" autocomplete="current-password" required>
            </div>
            <button type="submit" name="loginbutton">Log In</button>
        </form>
      </main>
    </div>
    <script src="../assets/js/animations.js" defer></script>
</body>
</html>
