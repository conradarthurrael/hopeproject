<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
try {
    if (file_exists("html/mental-health-db.php")) {
        include_once("html/mental-health-db.php");
    }
} catch (Throwable $e) {
    $conn = null;
}

$error = "";

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['loginbutton'])) {
    if (isset($conn) && $conn) {
        $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, trim($_POST['username'])) : '';
        $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, trim($_POST['password'])) : '';

        if (!empty($username) && !empty($password)) {
            $sql = "SELECT * FROM users WHERE (email = '$username' OR name = '$username') AND password = '$password'";
            $result = mysqli_query($conn, $sql);

            if ($result && mysqli_num_rows($result) === 1) {
                $row = mysqli_fetch_assoc($result);
                $_SESSION["user_id"] = $row['user_id'];
                $_SESSION["email"] = $row['email'];
                $_SESSION["password"] = $row['password'];
                $_SESSION["user_type"] = $row['user_type'];
                $_SESSION["name"] = $row['name'];
                $_SESSION["user_status"] = $row['user_status'];

                $user_id = $row['user_id'];
                $update_sql = "UPDATE users SET last_activity = NOW() WHERE user_id = '$user_id'";
                @mysqli_query($conn, $update_sql);

                if ($row['user_type'] === 'Manager' && $row['user_status'] === 'Approved') {
                    header("Location: html/manager-homepage.php");
                    exit();
                } else if ($row['user_type'] === 'Student' && $row['user_status'] === 'Approved') {
                    header("Location: html/student-homepage.php");
                    exit();
                } else if ($row['user_type'] === 'Teacher' && $row['user_status'] === 'Approved') {
                    header("Location: html/teacher-homepage.php");
                    exit();
                } else if ($row['user_type'] === 'Guidancecounsellor' && $row['user_status'] === 'Approved') {
                    header("Location: html/guidance-counsellor-homepage.php");
                    exit();
                } else {
                    $status_msg = !empty($row['user_status']) ? $row['user_status'] : 'Pending';
                    $error = "Login failed: Account is currently " . htmlspecialchars($status_msg) . ". Please wait for approval.";
                }
            } else {
                $error = "Login failed: Invalid username/email or password.";
            }
        } else {
            $error = "Please enter both username/email and password.";
        }
    } else {
        $error = "Database connection error. Please verify the database server is running.";
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - H ♡ P E</title>
    <link rel="stylesheet" href="css/login-page.css" />
  </head>
  <body>
    <nav class="navbar">
      <div class="nav-container">
        <a href="index.php"><img src="images/hope-logo.png" alt="Logo" class="logo" /></a>
        <ul>
          <li><a href="index.php">Home</a></li>
        </ul>
      </div>
    </nav>

    <div class="login-container">
      <h1>Welcome back</h1>
      <p class="login-intro">Log in to continue to your H.O.P.E. account.</p>

      <?php if (!empty($error)): ?>
        <div style="background-color: #fdf2f2; color: #991b1b; border: 1px solid #f8b4b4; padding: 12px 16px; border-radius: 8px; margin: 0 10% 20px 10%; font-size: 0.9rem; line-height: 1.45; font-weight: 500; text-align: center; box-shadow: 0 2px 6px rgba(153, 27, 27, 0.06);">
          <span style="font-weight: 700; margin-right: 4px;">Notice:</span> <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <?php if (isset($_GET['registered'])): ?>
        <div style="background-color: #edfcf2; color: #065f46; border: 1px solid #a7f3d0; padding: 12px 16px; border-radius: 8px; margin: 0 10% 20px 10%; font-size: 0.9rem; line-height: 1.45; font-weight: 500; text-align: center; box-shadow: 0 2px 6px rgba(6, 95, 70, 0.06);">
          <span style="font-weight: 700; margin-right: 4px;">Success:</span> Registration completed! Your account is pending manager review.
        </div>
      <?php endif; ?>

      <form action="login.php" method="post">
        <div class="form-group">
          <label for="username">Username or Email</label>
          <input type="text" id="username" name="username" placeholder="Enter username or email" required />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter password" required />
        </div>
        <button type="submit" name="loginbutton">Login</button>
      </form>
      <p class="register-link">
        Don't have an account? <a href="register.php">Register here</a>.
      </p>
    </div>
  </body>
</html>
