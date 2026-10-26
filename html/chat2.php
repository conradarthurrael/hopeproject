<!DOCTYPE html>
<html lang="en">
<?php 
    include "mhdb.php";
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: ../index.php");
        exit();
     }
          $user_id5 = $_SESSION['user_id'];
    ?>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Homepage</title>
    <link rel="stylesheet" href="../css/chat2.css?v=54">
</head>
<body>
<nav class="navbar">
      <div class="nav-container">
        <img src="../images/hope-logo.png" alt="Logo" class="logo" />
        <div class="nav-container2">
        <ul>
        <li class="chat"><a href="chat2.php" class="chat2">Chat</a></li>
          <li><a href="mhlogout.php">Log Out</a></li>
          <li><a href="studenthomepage.php">Back</a></li>
        </ul>
</div>
      </div>
    </nav>

    <div class="chat-container">
        <div class="chatprofile">
            <?php
            $chatprofileselection = "SELECT user_id,name,profilepic,last_activity FROM users WHERE user_id!='$user_id5' AND user_type='Guidancecounsellor'";
            $chatprofileselection2 = mysqli_query($conn, $chatprofileselection);
            while($row = mysqli_fetch_assoc($chatprofileselection2)) {
                $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
            ?>
            <div class="chatprofile2">
            <a href="chat2.php?user_id=<?=$row['user_id']?>" class="a"><?php echo '<img width="40px" height="40px" id="profilepic2" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></a>

            <?= $row['name']; ?>
            <br>
            <?php
            if($row['last_activity'] !== NULL) {
                echo "<span stye='color:green;'>Active</span>";
            } else {
                echo "<span stye='color:gray;'>Offline</span>";
            }
            ?>
            </div>
            <?php
            }
            $receiver = $_GET['user_id'] ?? "";
         
            $queryunique = "SELECT * FROM messages INNER JOIN users ON messages.sender_id = users.user_id WHERE(users.user_id = '$user_id5' AND messages.receiver_id = '$receiver') OR (users.user_id = '$receiver' AND messages.receiver_id = '$user_id5') ORDER BY sent_at DESC";
            if(isset($queryunique)) {
            $_SESSION["queryunique"] = $queryunique;
            }
            ?>
        </div>
        <div class="chat-container2">
            <div id="chats">
    <?php 
    $query2 = "SELECT DISTINCT receiver_id, name, profilepic, user_id FROM messages INNER JOIN users ON messages.receiver_id = users.user_id WHERE messages.message IS NOT NULL AND messages.sender_id = $user_id5";
    $result2 = mysqli_query($conn, $query2);
    while($row=mysqli_fetch_assoc($result2)){
    $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
    ?>
    <div class="chats-container">
    <a href="chat2.php?user_id=<?=$row['user_id']?>" class="a"><?php echo '<img width="70px" height="70px" id="profilepic2" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></a>
    <div class="namecontainer">
    <?= $row['name']; ?>
    </div>
</div>
    <?php } ?>
            </div>
            <div id="messages" class="messages">
            </div>
        </div>
        <div class="messageform">
            <form id="typemessage-container" method="POST" action="chatmessage2.php">
                <textarea id="typemessage" name="typemessage" placeholder="Enter a message..." maxlength="5000" required></textarea>
                <button type="submit">Send</button>
                <input type="text" name="sender_id" class="hiddeninput" value=<?=$user_id5?>>
                <input type="text" name="receiver_id" class="hiddeninput" value=<?=$receiver?>>
            </form>
        </div>
</div>
    <script>
        function loadMessages(){
            fetch("display.php").then(response => response.text()).then(data => {
                document.getElementById("messages").innerHTML = data;
            });
        }
        loadMessages();
        setInterval(loadMessages, 1000);
        </script>
    </body>
    </html>