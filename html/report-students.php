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
    <link rel="stylesheet" href="../css/report-students.css?v=43">
</head>
<body>
<nav class="navbar">
      <div class="nav-container">
        <img src="../images/hope-logo.png" alt="Logo" class="logo" />
        <div class="nav-container2">
        <ul>
        <li class="reportstudent"><a href="report-students.php" class="reportstudent2">Report Students</a></li>
          <li><a href="mental-health-logout.php">Log Out</a></li>
          <li><a href="teacher-homepage.php">Back</a></li>
        </ul>
</div>
      </div>
    </nav>

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
<div class="studentcontainer">
<?php
$limit = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$startingrecord = ($page - 1) * $limit;
    if(isset($_POST['search1'])){
        $search1 = $_POST['search1'];
    $searchstudents = "SELECT * FROM users WHERE user_type='Student' AND name LIKE '%$search1%' LIMIT $limit OFFSET $startingrecord";
    $students = mysqli_query($conn, $searchstudents);
    while($row = mysqli_fetch_assoc($students)){
        $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
        ?>
    <div class="studentprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer2">
      <p class="p">Name: <?= $row['name'] ?></p>
      <p class="p">Age: <?= $row['age'] ?></p>
      <p class="p">Sex: <?= $row['sex'] ?></p>
      <p class="p">Type: <?= $row['user_type'] ?></p>
    </div>
      <a class="edit" name = "edit" href="report-students-2.php?user_id=<?=$row['user_id']?>">Report Student</a>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM users WHERE user_type='Student' AND name LIKE '%$search1%'";
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
           $defaultpatients = "SELECT * FROM users WHERE user_type='Student' LIMIT $limit OFFSET $startingrecord";
           $patients2 = mysqli_query($conn, $defaultpatients);
           while($row = mysqli_fetch_assoc($patients2)){
            $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
            ?>
            <div class="studentprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer2">
      <p class="p">Name: <?= $row['name'] ?></p>
      <p class="p">Age: <?= $row['age'] ?></p>
      <p class="p">Sex: <?= $row['sex'] ?></p>
      <p class="p">Type: <?= $row['user_type'] ?></p>
      <a class="edit" name = "edit" href="report-students-2.php?user_id=<?=$row['user_id']?>">Report Student</a>
           </div>
           </div>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM users WHERE user_type='Student'";
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
</body>
</html>