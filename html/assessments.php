<?php
include "mental-health-db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: ../index.php");
  exit();
}

$user_id = (int) $_SESSION['user_id'];
$roleKey = strtolower(str_replace([' ', '-', '_'], '', (string) ($_SESSION['user_type'] ?? '')));
$portalHomePage = [
  'teacher' => 'teacher-homepage.php',
  'student' => 'student-homepage.php',
  'manager' => 'manager-homepage.php',
  'admin' => 'manager-homepage.php',
  'guidancecounsellor' => 'guidance-counsellor-homepage.php',
  'guidancecounselor' => 'guidance-counsellor-homepage.php'
][$roleKey] ?? '../index.php';
if (!in_array($roleKey, ['guidancecounsellor', 'guidancecounselor'], true)) {
  header("Location: " . $portalHomePage);
  exit();
}

$result = mysqli_query($conn, "SELECT name, email, age, sex, address, profilepic, user_status, user_type FROM users WHERE user_id='$user_id'");
$user = ($result && mysqli_num_rows($result) > 0) ? mysqli_fetch_assoc($result) : [];
$name = $user['name'] ?? ($_SESSION['name'] ?? 'Guidance Counsellor');
$email = $user['email'] ?? ($_SESSION['email'] ?? '');
$age = $user['age'] ?? 'N/A';
$sex = $user['sex'] ?? 'N/A';
$address = $user['address'] ?? 'Main Campus';
$status = $user['user_status'] ?? 'Approved';
$roleLabel = 'GUIDANCE COUNSELLOR';

$has_photo = false;
$photo = '';
if (!empty($user['profilepic'])) {
  $finfo = finfo_open();
  $mime = $finfo ? @finfo_buffer($finfo, $user['profilepic'], FILEINFO_MIME_TYPE) : '';
  if ($mime && strpos($mime, 'image/') === 0) {
    $has_photo = true;
    $photo = 'data:' . $mime . ';base64,' . base64_encode($user['profilepic']);
  }
  if ($finfo) {
    finfo_close($finfo);
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Guidance Counsellor Dashboard - H.O.P.E.</title>
<link rel="stylesheet" href="../assets/css/assessments.css">
</head>

<body class="dashboard-page counsellor-dashboard">
  <aside class="sidebar">
    <div class="sidebar-brand">
      <a href="guidance-counsellor-homepage.php" class="brand-link">
        <img src="../images/hope-logo.png" alt="H.O.P.E. Logo" class="sidebar-logo">
      </a>
    </div>

    <div class="sidebar-user">
      <div class="user-avatar-sm">
        <?php if ($has_photo): ?>
          <img src="<?= htmlspecialchars($photo, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($name) ?>">
        <?php else: ?>
          <span class="avatar-initials"><?= strtoupper(substr($name, 0, 1)) ?></span>
        <?php endif; ?>
      </div>
      <div class="user-details">
        <h4 class="user-name"><?= htmlspecialchars($name) ?></h4>
        <span class="user-role"><?= htmlspecialchars($roleLabel) ?></span>
      </div>
    </div>

    <nav class="sidebar-nav">
      <span class="nav-section-title">MAIN NAVIGATION</span>
      <ul class="nav-list">
        <li><a href="guidance-counsellor-homepage.php" class="nav-item active"><svg class="nav-icon" width="18"
              height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round">
              <rect x="3" y="3" width="7" height="7"></rect>
              <rect x="14" y="3" width="7" height="7"></rect>
              <rect x="14" y="14" width="7" height="7"></rect>
              <rect x="3" y="14" width="7" height="7"></rect>
            </svg><span>Home</span></a></li>
        <li><a href="students.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
              <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
            </svg><span>Students</span></a></li>
        <li><a href="reports.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <line x1="18" y1="20" x2="18" y2="10"></line>
              <line x1="12" y1="20" x2="12" y2="4"></line>
              <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg><span>Reports</span></a></li>
        <li><a href="appointments.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
              <line x1="16" y1="2" x2="16" y2="6"></line>
              <line x1="8" y1="2" x2="8" y2="6"></line>
              <line x1="3" y1="10" x2="21" y2="10"></line>
            </svg><span>Appointments</span></a></li>
        <li><a href="chat.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg><span>Chat</span></a></li>
      </ul>
        </nav>
        </aside>
        <div class="assessment-container1">
        <div class="assessment-container2">
            <form action="assessmentsinsert.php" method="POST">
            <label>Question 1</label>
            <input type="text" name="question1">
            <label>Question 2</label>
            <input type="text" name="question2">
            <label>Question 3</label>
            <input type="text" name="question3">
            <label>Question 4</label>
            <input type="text" name="question4">
            <label>Question 5</label>
            <input type="text" name="question5">
            <label>Question 6</label>
            <input type="text" name="question6">
            <label>Question 7</label>
            <input type="text" name="question7">
            <label>Question 8</label>
            <input type="text" name="question8">
            <label>Question 9</label>
            <input type="text" name="question9">
            <label>Question 10</label>
            <input type="text" name="question10">
            <button name="createform" class="createform">Create Form</button>
        </Form>
        </div>
        </div>
      
  <script src="../assets/js/animations.js" defer></script>
</body>

</html>