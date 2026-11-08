<!DOCTYPE html>
<html lang="en">
    <?php 
    include "mental-health-db.php";
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: ../index.php");
        exit();
    }
    ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Homepage</title>
    <link rel="stylesheet" href="../css/teacher-homepage.css?v=43">
</head>
<body>
<nav class="navbar">
      <div class="nav-container">
        <img src="../images/hope-logo.png" alt="Logo" class="logo" />
        <div class="nav-container2">
        <ul>
        <li class="reportstudent"><a href="report-students.php" class="reportstudent2">Report Students</a></li>
          <li><a href="mental-health-logout.php">Log Out</a></li>
        </ul>
</div>
      </div>
    </nav>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <div class="profcontainer">
        <div class="profcontainer2">
            <div class="welcome">
            <h1 class="mhgsms">Welcome to <img src="../images/hope-logo.png" class="logo"></h1>
            </div>

            <div class="profcontainer3">
                <?php
                $user_id2 = $_SESSION['user_id'];
                $profile = "SELECT name, age, sex, address, profilepic FROM users WHERE user_id='$user_id2'";
                $profile2 = mysqli_query($conn, $profile);
                while($row = mysqli_fetch_assoc($profile2)){
                $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
                ?>
                <table>
                <td class="td"><?php echo '<img width="200" height="200px" class="profilepic2" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
                </table>
              <?php }?>
              <?php
              $selectname = "SELECT name FROM users WHERE user_id='$user_id2'";
              $selectname2 = mysqli_query($conn, $selectname);
              while($row = mysqli_fetch_assoc($selectname2)){
                ?>
              <div class="profdescription">
                <h1 class="welcomeuser">Welcome <?= $row['name'] ?></h1>
                <?php } ?>
                <p>Welcome to Mental Health management system, we are here
                    to help you
                </p>
                <a class="edit" name = "edit" href="user-update-3.php?user_id=<?=$user_id2?>">Edit Profile</a>
            </div>
            </div>
        </div>
        <div class="maincontainerwrap">
        <div class="main-container">
            <div class="slider-section">
                <div class="slides">
                    <img src="../images/asylum1.jpg" class="img2">
                    <img src="../images/asylum2.jpg" class="img2">
                    <img src="../images/psychiatrist.jpg" class="img2">
                    <img src="../images/asylum1.jpg" class="img2">
                </div>
    </div>
    </div>
    </div>
    </div>
</body>
</html>