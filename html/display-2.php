<?php
include "mental-health-db.php";
session_start();
$user_id7 = $_SESSION['user_id'];
$query2 = "SELECT * FROM messages INNER JOIN users ON messages.sender_id = users.user_id WHERE receiver_id = $user_id7";
   $result2 = mysqli_query($conn, $query2);
    while($row=mysqli_fetch_assoc($result2)){
    $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
    echo "<div id=chats>";
    echo "<div>";
    echo '<img width="40px" height="40px" id="profilepic2" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';
    echo "</div>";
    echo "</div>";
    }
    ?>