<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rehab Management System</title>
<link rel="stylesheet" href="../assets/css/mental-health-register.css">
</head>
<body class="auth-page">
<nav class="navbar">
      <div class="nav-container">
        <a class="portal-brand-link" href="../index.php"><img src="../images/hope-logo.png" alt="Logo" class="logo" /></a>
        <div class="nav-container2">
        <ul>
          <li><a href="../index.php#home">Home</a></li>
          <li><a href="../index.php#about">About</a></li>
          <li><a href="../index.php#services">Services</a></li>
          <li><a href="../index.php#contact">Contact</a></li>
          <li><a href="mental-health-login.php">Login</a></li>
          <li><a href="mental-health-register.php" aria-current="page">Sign Up</a></li>
        </ul>
</div>
      </div>
    </nav>
    <div class="registercontainer1">
        <?php include "mental-health-db.php"; ?>
        <div class="auth-layout auth-register-layout">
        <aside class="auth-side-panel">
            <span class="auth-panel-eyebrow">H.O.P.E. • A SUPPORTIVE COMMUNITY</span>
            <h2>Holistic Online Psychological &amp; Emotional Support</h2>
            <p>Take a simple first step toward care that meets you with understanding and respect.</p>
            <ul class="auth-benefits">
                <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3 4.5 6v5.5c0 4.8 3.2 7.8 7.5 9.5 4.3-1.7 7.5-4.7 7.5-9.5V6L12 3Z"/><path d="m9 12 2 2 4-4"/></svg><span>Confidential one-on-one guidance chat</span></li>
                <li><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18M8 14h3"/></svg><span>Easy appointment scheduling</span></li>
                <li><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21s-8-4.5-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6.5-8 11-8 11Z"/><path d="M8 12h2l1-2 2 4 1-2h2"/></svg><span>Personalized wellbeing support</span></li>
            </ul>
            <p class="auth-panel-switch">Already have an account? <a href="mental-health-login.php">Log in</a></p>
        </aside>
        <div class="registercontainer2">
            <div class="registercontainer3">
                <h1>Register</h1>
                <form method="POST" action="mental-health-signup.php" enctype="multipart/form-data">
            <label for="register-name">Name</label>
            <input id="register-name" type="text" name="name" placeholder="Enter Name" required>
            <label for="register-email">Email</label>
            <input id="register-email" type="email" name="email" placeholder="Enter Email" required>
            <label for="register-password">Password</label>
            <input id="register-password" type="password" name="password" placeholder="Enter Password" required>
            <label for="register-age">Age</label>
            <input id="register-age" type="text" name="age" placeholder="Enter Age" required>
            <label for="register-sex">Sex</label>
            <select id="register-sex" name="sex" class="sex" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <label for="register-address">Address</label>
            <input id="register-address" type="text" name="address" placeholder="Enter Address" required>
            <label for="register-contact">Contact No.</label>
            <input id="register-contact" type="text" name="contactno" placeholder="Enter Contact No." required>
            <label for="register-dob">Date of Birth</label>
            <input id="register-dob" type="text" name="dob" placeholder="yyyy/mm/dd" required>
            <label for="register-profile">Profile photo</label>
            <input id="register-profile" type="file" name="profilepic" class="profilepic">
            <button type="submit" name="registerbutton" class="registerbutton">Register</button>
            </form>
            </div>
        </div>
        </div>
    </div>
    <script src="../assets/js/animations.js" defer></script>
</body>
    </html>
