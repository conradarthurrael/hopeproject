<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rehab Management System</title>
    <link rel="stylesheet" href="../css/mhstyle.css?v=33">
</head>
<body>
<nav class="navbar">
      <div class="nav-container">
        <img src="../images/hope-logo.png" alt="Logo" class="logo" />
        <div class="nav-container2">
        <ul>
          <li><a href="../index.php">Home</a></li>
          <li><a href="../index.php">About</a></li>
          <li><a href="../index.php">Services</a></li>
          <li><a href="../index.php">Contact</a></li>
        </ul>
</div>
      </div>
    </nav>
    <div class="registercontainer1">
        <br>
        <br>
        <br>
        <br>
        <?php include "mhdb.php"; ?>
        <div class="registercontainer2">
            <div class="registercontainer3">
                <h1>Register</h1>
                <form method="POST" action="mhsignup.php" enctype="multipart/form-data">
            <label>Name</label><br>
            <input type="text" name="name" placeholder="Enter Name" required><br>
            <label>Email</label><br>
            <input type="text" name="email" placeholder="Enter Email" required><br>
            <label>Password</label><br>
            <input type="text" name="password"  placeholder="Enter Password" required><br>
            <label>Age</label><br>
            <input type="text" name="age" placeholder="Enter Age" required><br>
            <label>Sex</label><br>
            <select name="sex" class="sex" required>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select><br>
            <label>Address</label><br>
            <input type="text" name="address" placeholder="Enter Address" required><br>
            <label>Contact No.</label><br>
            <input type="text" name="contactno" placeholder="Enter Contact No." required><br>
            <label>Date of Birth</label><br>
            <input type="text" name="dob" placeholder="yyyy/mm/dd" required><br>
            <input type="file" name="profilepic" class="profilepic">
            <button type="submit" name="registerbutton" class="registerbutton">Register</button>
            </form>
            </div>
        </div>
    </div>
    </body>
    </html>