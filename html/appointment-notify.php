<?php
session_start();
include "mental-health-db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: mental-health-login.php");
    exit();
}

$roleKey = strtolower(str_replace([' ', '-', '_'], '', (string)($_SESSION['user_type'] ?? '')));
if ($roleKey !== 'student') {
    $portalHomePage = [
        'teacher' => 'teacher-homepage.php',
        'student' => 'student-homepage.php',
        'manager' => 'manager-homepage.php',
        'admin' => 'manager-homepage.php',
        'guidancecounsellor' => 'guidance-counsellor-homepage.php',
        'guidancecounselor' => 'guidance-counsellor-homepage.php',
    ][$roleKey] ?? '../index.php';
    header("Location: " . $portalHomePage);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: book-an-appointment.php");
    exit();
}

$guidance_id = isset($_POST['guidance_id']) ? (int) $_POST['guidance_id'] : 0;
$userid2 = isset($_POST['user_id2']) ? (int) $_POST['user_id2'] : 0;
$session_user_id = (int) $_SESSION['user_id'];
$concern = trim((string) ($_POST['concern'] ?? ''));
$reason = trim((string) ($_POST['reason'] ?? ''));
$appointmentdate = trim((string) ($_POST['appointmentdate'] ?? ''));

if ($guidance_id <= 0 || $userid2 <= 0 || $userid2 !== $session_user_id || $concern === '' || $reason === '' || $appointmentdate === '') {
    header("Location: book-an-appointment.php");
    exit();
}

$guidanceCheck = $conn->prepare("SELECT user_id FROM users WHERE user_id = ? AND user_type = 'Guidance Counsellor' AND user_status = 'Approved' LIMIT 1");
if (!$guidanceCheck) {
    header("Location: book-an-appointment.php");
    exit();
}
$guidanceCheck->bind_param("i", $guidance_id);
$guidanceCheck->execute();
$guidanceResult = $guidanceCheck->get_result();
$validGuidance = $guidanceResult && $guidanceResult->num_rows === 1;
$guidanceCheck->close();

if (!$validGuidance) {
    header("Location: book-an-appointment.php");
    exit();
}

$appointmentinsert = $conn->prepare("INSERT INTO appointments (guidance_id, user_id, concern, reason, appointmentdate) VALUES (?, ?, ?, ?, ?)");
if ($appointmentinsert) {
    $appointmentinsert->bind_param("iisss", $guidance_id, $userid2, $concern, $reason, $appointmentdate);
    $appointmentinsert->execute();
    $appointmentinsert->close();
}

header("Location: book-an-appointment.php?booked=1");
exit();
?>
