<?php
include "mental-health-db.php";
session_start();
$user_id6 = $_SESSION['user_id'];
$query = $_SESSION["queryunique"];
   $result = mysqli_query($conn, $query);
    while($row=mysqli_fetch_assoc($result)){
    $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
    if ($row['sender_id'] == $user_id6){
        echo "<div id='sender-row'>";
    echo "<div id='chatbubble-sender'>";
    echo "<div>";
    echo htmlspecialchars($row['message']);
    echo "<br><small>";
    echo htmlspecialchars($row['sent_at']);
    echo "</small>";
    echo "</div>";
    echo '<img width="30px" height="30px" id="profilepic2" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';
    echo "</div>";
    echo "</div>";
    } else {
        echo "<div id='sender-row'>";
        echo "<div id='chatbubble-receiver'>";
    echo "<div>";
    echo htmlspecialchars($row['message']);
    echo "<br><small>";
    echo htmlspecialchars($row['sent_at']);
    echo "</small>";
    echo "</div>";
    echo '<img width="30px" height="30px" id="profilepic2" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';
    echo "</div>";
    echo "</div>";
    }
}?>