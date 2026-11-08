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
    <title>Guidance Counsellor Homepage</title>
    <link rel="stylesheet" href="../css/appointments.css?v=57">
</head>
<body>
<nav class="navbar">
      <div class="nav-container">
        <img src="../images/hope-logo.png" alt="Logo" class="logo" />
        <div class="nav-container2">
        <ul>
          <li><a href="guidance-counsellor-homepage.php">Home</a></li>
          <li><a href="students-2.php">Students</a></li>
          <li><a href="reports.php">Reports</a></li>
          <li><a href="appointments.php">Appointments</a></li>
          <li><a href="chat.php">Chat</a></li>
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
<div class="appointmentdata">
        <div class="appointmentdata2">

        <?php
    include "mental-health-db.php";
    ?>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
<div class="searchcontainer">
    <div class="searchbar">
        <form method="POST">
    <input type="text" name="search1" class="search1" placeholder="Search">
    <button type="submit" class="submit1">Search</button>
</form>
</div>
<br>
<br>
<div class="appointmentcontainer">
<?php
$limit = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$startingrecord = ($page - 1) * $limit;
    if(isset($_POST['search1'])){
        $search1 = $_POST['search1'];
    $searchappointments = "SELECT * FROM appointments INNER JOIN users ON users.user_id = appointments.user_id WHERE name LIKE '%$search1%' LIMIT $limit OFFSET $startingrecord";
    $appointments = mysqli_query($conn, $searchappointments);
    while($row = mysqli_fetch_assoc($appointments)){
        $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
        ?>
    <div class="appointmentprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer2">
      <p class="p">Name: <?= $row['name'] ?></p>
      <p class="p">Age: <?= $row['age'] ?></p>
      <p class="p">Sex: <?= $row['sex'] ?></p>
      <p class="p">Concern: <?= $row['concern'] ?></p>
      <div class="reportcontainer3">
      <p class="p2">Reason: <?= $row['reason'] ?></p>
    </div>
      <p class="p">Appointment Date: <?= $row['appointmentdate'] ?></p>
      <p class="p">Date Created: <?= $row['date_created'] ?></p>
    </div>
      <a class="chatreport" name = "edit" href="report-chat.php?user_id=<?=$row['user_id']?>">Chat</a>
      <a class="delete" href="appointmentdelete.php?appointment_id=<?=$row['appointment_id']?>" onclick
        ="return confirm('Delete this report?')">🗑</a>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM appointments WHERE name LIKE '%$search1%'";
              $count_result = $conn-> query($count_sql);
              $count_row = $count_result->fetch_assoc();
              $total = $count_row['total'];
              $total_pages = ceil($total / $limit);
              for($i = 1; $i <= $total_pages; $i++) {
        ?>
        <a id="pages2" href="?page=<?php echo $i; ?>"><?php echo $i ?></a>
        <?php } ?>
              </div>
    </div>
        <?php } }
        else if(empty($_POST['search1'])){
           $defaultappointments = "SELECT * FROM appointments INNER JOIN users ON users.user_id = appointments.user_id LIMIT $limit OFFSET $startingrecord";
           $appointments2 = mysqli_query($conn, $defaultappointments);
           while($row = mysqli_fetch_assoc($appointments2)){
            $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
            ?>
            <div class="appointmentprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer2">
        <p class="p">Name: <?= $row['name'] ?></p>
      <p class="p">Age: <?= $row['age'] ?></p>
      <p class="p">Sex: <?= $row['sex'] ?></p>
      <p class="p">Concern: <?= $row['concern'] ?></p>
      <div class="reportcontainer3">
      <p class="p2">Reason: <?= $row['reason'] ?></p>
    </div>
      <p class="p">Appointment Date: <?= $row['appointmentdate'] ?></p>
      <p class="p">Date Created: <?= $row['date_created'] ?></p>
           </div>
           <a class="chatreport" name = "edit" href="report-chat.php?user_id=<?=$row['user_id']?>">Chat</a>
      <a class="delete" href="appointmentdelete.php?appointment_id=<?=$row['appointment_id']?>" onclick
        ="return confirm('Delete this report?')">🗑</a>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM appointments";
              $count_result = $conn-> query($count_sql);
              $count_row = $count_result->fetch_assoc();
              $total = $count_row['total'];
              $total_pages = ceil($total / $limit);
              for($i = 1; $i <= $total_pages; $i++) {
        ?>
        <a id="pages2" href="?page=<?php echo $i; ?>"><?php echo $i ?></a>
        <?php } ?>
              </div>
        <?php } } ?>
</div>
    </body>
    </html>