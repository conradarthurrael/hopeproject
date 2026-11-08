<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rehab Management System</title>
    <link rel="stylesheet" href="../css/transactions.css?v=40">
</head>
<body>
<nav class="navbar">
      <div class="nav-container">
        <img src="../images/hope-logo.png" alt="Logo" class="logo" />
        <div class="nav-container2">
        <ul>
          <li><a href="patienthomepage.php">Home</a></li>
          <li><a href="patientdoctors.php">Doctors</a></li>
          <li><a href="transactions.php">Transactions</a></li>
          <li><a href="#contact">Contact Us</a></li>
          <li><a href="mental-health-logout.php">Log Out</a></li>
        </ul>
</div>
      </div>
    </nav>
    <div class="searchbar">
        <form method="POST">
    <input type="text" name="search1" class="search1" placeholder="Search">
    <button type="submit" class="submit1">Search</button>
</form>
</div>
<div class="transactioncontainer">
    <?php
    include "mental-health-db.php";
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: ../index.php");
        exit();
     }
    $user_id3 = $_SESSION['user_id'];
$limit = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$startingrecord = ($page - 1) * $limit;
    if(isset($_POST['search1'])){
        $search1 = $_POST['search1'];
    $searchtransactions = "SELECT * FROM transactions INNER JOIN users ON transactions.doctor_id = users.user_id WHERE transactions.user_id='$user_id3' AND name LIKE '%$search1%' LIMIT $limit OFFSET $startingrecord";
    $transactions = mysqli_query($conn, $searchtransactions);
    while($row = mysqli_fetch_assoc($transactions)){
        $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
        ?>
    <div class="transactionprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer2">
      <p class="p">Doctor: <?= $row['name'] ?></p>
      <p class="p">Transaction ID: <?= $row['transaction_id'] ?></p>
      <p class="p">Appointment Date: <?= $row['appointmentdate'] ?></p>
      <p class="p">Transaction Date: <?= $row['transaction_date'] ?></p>
    </div>
      <a class="edit" name = "edit" href="user-update-2.php?user_id=<?=$row['user_id']?>">🖋</a>
      <a class="delete" href="user-delete.php?user_id=<?=$row['user_id']?>" onclick
        ="return confirm('Delete this user?')">🗑</a>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM transactions WHERE user_id='$user_id3'";
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
           $defaulttransactions = "SELECT * FROM transactions INNER JOIN users ON users.user_id = transactions.doctor_id WHERE transactions.user_id='$user_id3' LIMIT $limit OFFSET $startingrecord";
           $transactions2 = mysqli_query($conn, $defaulttransactions);
           while($row = mysqli_fetch_assoc($transactions2)){
            $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
            ?>
            <div class="transactionprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer2">
      <p class="p">Doctor: <?= $row['name'] ?></p>
      <p class="p">Transaction ID: <?= $row['transaction_id'] ?></p>
      <p class="p">Appointment Date: <?= $row['appointmentdate'] ?></p>
      <p class="p">Transaction Date: <?= $row['transaction_date'] ?></p>
           </div>
      <a class="edit" name = "edit" href="user-update-2.php?user_id=<?=$row['user_id']?>">🖋</a>
      <a class="delete" href="user-delete.php?user_id=<?=$row['user_id']?>" onclick
        ="return confirm('Delete this user?')">🗑</a></div>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM transactions WHERE user_id='$user_id3'";
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
</div>
</body>
</html>