<?php
include "mental-health-db.php";
$sender_id = $_POST['sender_id'];
$receiver_id = $_POST['receiver_id'];
$typemessage = $_POST['typemessage'];
$messageinsert2 = "INSERT INTO messages(sender_id,receiver_id,message)
VALUES('$sender_id','$receiver_id','$typemessage');";
mysqli_query($conn, $messageinsert2);
if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['ok' => mysqli_affected_rows($conn) > 0]);
    exit();
}
header("Location: chat-2.php?user_id=$receiver_id");
?>
