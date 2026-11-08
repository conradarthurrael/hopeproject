<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guidance Counsellors</title>
    <link rel="stylesheet" href="../css/book-an-appointment.css?v=55">
</head>
<body>
<nav class="navbar">
      <div class="nav-container">
        <img src="../images/hope-logo.png" alt="Logo" class="logo" />
        <div class="nav-container2">
        <ul>
        <li class="chat"><a href="chat-2.php" class="chat2">Chat</a></li>
        <li><a href="book-an-appointment.php">Book an appointment</a></li>
        <li><a href="student-homepage.php">Back</a></li>
          <li><a href="mental-health-logout.php">Log Out</a></li>
        </ul>
</div>
      </div>
    </nav>
    <div class="guidancecounsellordata">
        <div class="guidancecounsellordata2">

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
<div class="counsellorcontainer">
<?php
$limit = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
$startingrecord = ($page - 1) * $limit;
    if(isset($_POST['search1'])){
        $search1 = $_POST['search1'];
    $searchcounsellors = "SELECT * FROM users WHERE user_type='Guidancecounsellor' AND name LIKE '%$search1%' LIMIT $limit OFFSET $startingrecord";
    $counsellors = mysqli_query($conn, $searchcounsellors);
    while($row = mysqli_fetch_assoc($counsellors)){
        $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
        ?>
    <div class="counsellorprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer3">
      <p class="p">Name: <?= $row['name'] ?></p>
      <p class="p">Age: <?= $row['age'] ?></p>
      <p class="p">Sex: <?= $row['sex'] ?></p>
      <p class="p">Type: <?= $row['user_type'] ?></p>
    </div>
    <a class="bookanappointment" name = "edit" href="appointment.php?user_id=<?=$row['user_id']?>">Book an Appointment</a>
        <?php $count_sql = "SELECT COUNT(*) AS total FROM users WHERE user_type='Guidancecounsellor' AND name LIKE '%$search1%'";
              $count_result = $conn-> query($count_sql);
              $count_row = $count_result->fetch_assoc();
              $total = $count_row['total'];
              $total_pages = ceil($total / $limit);
              for($i = 1; $i <= $total_pages; $i++) {
        ?>
        <a id="pages3" href="?page=<?php echo $i; ?>"><?php echo $i ?></a>
        <?php } ?>
              </div>

        <?php } }
        else if(empty($_POST['search1'])){
           $defaultcounsellors = "SELECT * FROM users WHERE user_type='Guidancecounsellor' LIMIT $limit OFFSET $startingrecord";
           $counsellors2 = mysqli_query($conn, $defaultcounsellors);
           while($row = mysqli_fetch_assoc($counsellors2)){
            $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
            ?>
            <div class="counsellorprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer3">
      <p class="p">Name: <?= $row['name'] ?></p>
      <p class="p">Age: <?= $row['age'] ?></p>
      <p class="p">Sex: <?= $row['sex'] ?></p>
      <p class="p">Type: <?= $row['user_type'] ?></p>
           </div>
           <a class="bookanappointment" name = "edit" href="appointment.php?user_id=<?=$row['user_id']?>">Book an Appointment</a>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM users WHERE user_type='Guidancecounsellor'";
              $count_result = $conn-> query($count_sql);
              $count_row = $count_result->fetch_assoc();
              $total = $count_row['total'];
              $total_pages = ceil($total / $limit);
              for($i = 1; $i <= $total_pages; $i++) {
        ?>
        <a id="pages3" href="?page=<?php echo $i; ?>"><?php echo $i ?></a>
        <?php } ?>
              </div>
              </div>
        <?php } } ?>
</div>
</body>
</html>