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
    <link rel="stylesheet" href="../css/report-students-2.css?v=45">
</head>
<body>
<nav class="navbar">
      <div class="nav-container">
        <img src="../images/hope-logo.png" alt="Logo" class="logo" />
        <div class="nav-container2">
        <ul>
        <li class="reportstudent"><a href="report-students.php" class="reportstudent2">Report Students</a></li>
          <li><a href="mental-health-logout.php">Log Out</a></li>
          <li><a href="report-students.php">Back</a></li>
        </ul>
</div>
      </div>
    </nav>
    <?php
    $user_id3 = $_GET['user_id'];
    ?>
<div class="formcontainer">
    <div class="formcontainer2">
        <?php
        $reportquery = "SELECT * FROM users WHERE user_id='$user_id3'";
        $reportquery2 = mysqli_query($conn, $reportquery);
        while($row = mysqli_fetch_assoc($reportquery2)){
            $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
        ?>
        <div class="studentinfo">
        <table>
        <td class="td"><?php echo '<img width="200" height="200px" class="profilepic2" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        </table>
        <div class="studentinfo2">
        <h2 class="studentinfo3">Name: <?= $row['name'] ?></h2>
        <h2 class="studentinfo3">Age: <?= $row['age'] ?></h2>
        <h2 class="studentinfo3">Sex: <?= $row['sex'] ?></h2>
        </div>
        </div>
        <div>
    <form method="POST" action="report-students-3.php">
        <input type="text" name="user_id" value="<?=$user_id3?>" class="hiddeninput">
        <label>Behavior</label><br>
        <select name="behavior" class="selectbehavior">
            <option value="bullying">Bullying</option>
            <option value="depression">Depression</option>
            <option value="emotional">Emotional</option>
            <option value="academic">Academic</option>
            <option value="others">Others</option>
        </select><br>
        <label>Description</label><br>
        <textarea name="report" rows="10" cols="40"></textarea><br>
        <label>Date of Incident</label><br>
        <input type="datetime-local" name="incidentdate" class="reportdate"><br><br>
        <button type="submit" name="submit10" class="reportbutton">Submit</button>
    </form>
</div>
</div>
</div>
<?php } ?>
</body>
</html>