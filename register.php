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
$success = "";

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['registerbutton'])) {
    if (isset($conn) && $conn) {
        $username = isset($_POST['username']) ? mysqli_real_escape_string($conn, trim($_POST['username'])) : '';
        $email = isset($_POST['email']) ? mysqli_real_escape_string($conn, trim($_POST['email'])) : '';
        $password = isset($_POST['password']) ? mysqli_real_escape_string($conn, trim($_POST['password'])) : '';
        $confirm_password = isset($_POST['confirm-password']) ? mysqli_real_escape_string($conn, trim($_POST['confirm-password'])) : '';
        $birthday = isset($_POST['birthday']) ? mysqli_real_escape_string($conn, trim($_POST['birthday'])) : '';
        $gender = isset($_POST['gender']) ? mysqli_real_escape_string($conn, trim($_POST['gender'])) : '';

        if (empty($username) || empty($email) || empty($password) || empty($birthday) || empty($gender)) {
            $error = "Please fill in all required fields.";
        } elseif ($password !== $confirm_password) {
            $error = "Passwords do not match.";
        } else {
            // Check if email or username already exists
            $check_sql = "SELECT user_id FROM users WHERE email = '$email' OR name = '$username'";
            $check_res = mysqli_query($conn, $check_sql);

            if ($check_res && mysqli_num_rows($check_res) > 0) {
                $error = "An account with that email or username already exists.";
            } else {
                // Calculate age from birthday
                $birthDate = new DateTime($birthday);
                $today = new DateTime();
                $age = $today->diff($birthDate)->y;

                $sex = ucfirst(strtolower($gender));
                $address = 'N/A';
                $contactno = 'N/A';
                $usertype = 'PENDING';
                $user_status = 'Pending';
                $profilepic = '';

                $insert_sql = "INSERT INTO users(name, email, password, age, sex, address, date_of_birth, contactno, user_type, user_status, profilepic)
                               VALUES('$username', '$email', '$password', '$age', '$sex', '$address', '$birthday', '$contactno', '$usertype', '$user_status', '$profilepic')";

                if (mysqli_query($conn, $insert_sql)) {
                    $success = "Registration successful! Your account is pending manager approval. <a href='login.php' style='color:#03543f; font-weight:bold; text-decoration:underline;'>Click here to Login</a>.";
                } else {
                    $error = "Database error during registration: " . mysqli_error($conn);
                }
            }
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
    <title>Register - H ♡ P E</title>
    <link rel="stylesheet" href="css/register-page.css" />
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

    <div class="register-container">
      <h1>Register</h1>
      <p class="register-intro">Create an account to access H.O.P.E. services.</p>

      <?php if (!empty($error)): ?>
        <div style="background-color: #fdf2f2; color: #991b1b; border: 1px solid #f8b4b4; padding: 12px 16px; border-radius: 8px; margin: 0 10% 20px 10%; font-size: 0.9rem; line-height: 1.45; font-weight: 500; text-align: center; box-shadow: 0 2px 6px rgba(153, 27, 27, 0.06);">
          <span style="font-weight: 700; margin-right: 4px;">Notice:</span> <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div style="background-color: #edfcf2; color: #065f46; border: 1px solid #a7f3d0; padding: 12px 16px; border-radius: 8px; margin: 0 10% 20px 10%; font-size: 0.9rem; line-height: 1.45; font-weight: 500; text-align: center; box-shadow: 0 2px 6px rgba(6, 95, 70, 0.06);">
          <span style="font-weight: 700; margin-right: 4px;">Success:</span> <?php echo $success; ?>
        </div>
      <?php endif; ?>

      <form action="register.php" method="post">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Enter username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" />
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Enter email address" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" />
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Create password" required />
        </div>
        <div class="form-group">
          <label for="confirm-password">Confirm Password</label>
          <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm password" required />
        </div>
        <div class="form-group">
          <label for="birthday">Birthday</label>
          <input type="date" id="birthday" name="birthday" required value="<?php echo isset($_POST['birthday']) ? htmlspecialchars($_POST['birthday']) : ''; ?>" />
        </div>
        <div class="form-group">
          <label for="gender">Gender</label>
          <select id="gender" name="gender" required>
            <option value="">Select Gender</option>
            <option value="male" <?php echo (isset($_POST['gender']) && strtolower($_POST['gender']) === 'male') ? 'selected' : ''; ?>>Male</option>
            <option value="female" <?php echo (isset($_POST['gender']) && strtolower($_POST['gender']) === 'female') ? 'selected' : ''; ?>>Female</option>
            <option value="other" <?php echo (isset($_POST['gender']) && strtolower($_POST['gender']) === 'other') ? 'selected' : ''; ?>>Other</option>
          </select>
        </div>
        <button type="submit" name="registerbutton">Register</button>
      </form>
      <p class="login-link">
        Already have an account? <a href="login.php">Login Here</a>.
      </p>
    </div>
  </body>
</html>
