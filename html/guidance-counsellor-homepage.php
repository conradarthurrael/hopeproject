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
<link rel="stylesheet" href="../assets/css/guidance-counsellor-homepage.css">
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

      <span class="nav-section-title">ACCOUNT</span>
      <ul class="nav-list">
        <li><a href="user-update.php?user_id=<?= $user_id ?>" class="nav-item"><svg class="nav-icon" width="18"
              height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round">
              <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
              <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
            </svg><span>Edit Profile</span></a></li>
        <li><a href="mental-health-logout.php" class="nav-item nav-logout"><svg class="nav-icon" width="18" height="18"
              viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round">
              <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
              <polyline points="16 17 21 12 16 7"></polyline>
              <line x1="21" y1="12" x2="9" y2="12"></line>
            </svg><span>Log Out</span></a></li>
      </ul>
    </nav>
  </aside>

  <main class="main-content">
    <header class="dashboard-header">
      <div class="header-titles">
        <span class="header-badge">GUIDANCE COUNSELLOR PORTAL</span>
        <h1 class="header-title">Guidance Counsellor Dashboard</h1>
        <p class="header-subtitle">Welcome back, <?= htmlspecialchars($name) ?>. Support students through appointments,
          reports, and secure conversations.</p>
      </div>
      <div class="header-actions">
        <div class="status-indicator"><span class="status-dot"></span><span>Account Active</span></div>
        <a href="user-update.php?user_id=<?= $user_id ?>" class="btn-profile-quick">Edit Profile</a>
      </div>
    </header>

    <section class="stats-grid">
      <a href="appointments.php" class="stat-card dashboard-card stat-card-interactive">
        <div class="stat-icon-wrapper icon-counsellor"><svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
            <line x1="16" y1="2" x2="16" y2="6"></line>
            <line x1="8" y1="2" x2="8" y2="6"></line>
            <line x1="3" y1="10" x2="21" y2="10"></line>
          </svg></div>
        <div class="stat-data"><span class="stat-label">APPOINTMENTS</span>
          <h3 class="stat-number">&rarr;</h3><span class="stat-subtext">Review and manage appointments</span>
        </div>
      </a>
      <a href="reports.php" class="stat-card dashboard-card stat-card-interactive">
        <div class="stat-icon-wrapper icon-student"><svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="18" y1="20" x2="18" y2="10"></line>
            <line x1="12" y1="20" x2="12" y2="4"></line>
            <line x1="6" y1="20" x2="6" y2="14"></line>
          </svg></div>
        <div class="stat-data"><span class="stat-label">REPORTS</span>
          <h3 class="stat-number">&rarr;</h3><span class="stat-subtext">View student reports</span>
        </div>
      </a>
      <a href="chat.php" class="stat-card dashboard-card stat-card-interactive">
        <div class="stat-icon-wrapper icon-teacher"><svg class="nav-icon" width="20" height="20" viewBox="0 0 24 24"
            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
          </svg></div>
        <div class="stat-data"><span class="stat-label">CHAT</span>
          <h3 class="stat-number">&rarr;</h3><span class="stat-subtext">Connect with students securely</span>
        </div>
      </a>
    </section>

    <section class="dashboard-grid">
      <div class="card dashboard-card profile-card">
        <div class="card-header"><span class="card-tag">COUNSELLOR PROFILE</span>
          <h2 class="card-title">Profile Overview</h2>
        </div>
        <div class="profile-card-body">
          <div class="profile-avatar-wrap">
            <?php if ($has_photo): ?>
              <img src="<?= htmlspecialchars($photo, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($name) ?>"
                class="profile-avatar-lg">
            <?php else: ?>
              <div class="avatar-lg-placeholder"><span><?= strtoupper(substr($name, 0, 1)) ?></span></div>
            <?php endif; ?>
            <div class="profile-badge-pill"><span class="dot-online"></span><?= htmlspecialchars($status) ?></div>
          </div>
          <div class="profile-info-content">
            <h3 class="profile-heading"><?= htmlspecialchars($name) ?></h3>
            <p class="profile-role-sub">Guidance Counsellor &bull; Student Support</p>
            <div class="profile-meta-grid">
              <div class="meta-item"><span class="meta-label">EMAIL</span><span
                  class="meta-value"><?= htmlspecialchars($email) ?></span></div>
              <div class="meta-item"><span class="meta-label">CAMPUS</span><span
                  class="meta-value"><?= htmlspecialchars($address) ?></span></div>
              <div class="meta-item"><span class="meta-label">AGE / GENDER</span><span
                  class="meta-value"><?= htmlspecialchars($age) ?> &bull; <?= htmlspecialchars($sex) ?></span></div>
              <div class="meta-item"><span class="meta-label">ROLE TYPE</span><span class="meta-value">Guidance
                  Counsellor</span></div>
            </div>
            <p class="profile-quote">Use your H.O.P.E. portal to review appointments, follow up on reports, and provide
              timely guidance to students in need.</p>
            <div class="profile-actions">
              <a href="appointments.php" class="btn-primary dashboard-button">View Appointments</a>
              <a href="user-update.php?user_id=<?= $user_id ?>" class="btn-secondary dashboard-button">Edit Profile
                &rarr;</a>
            </div>
          </div>
        </div>
      </div>

      <div class="card dashboard-card showcase-card">
        <div class="card-header"><span class="card-tag">CARE & SUPPORT</span>
          <h2 class="card-title">H.O.P.E. Resources</h2>
        </div>
        <div class="slider-container">
          <div class="slider-track">
            <img src="../images/asylum1.jpg" alt="Care facility" class="slide-img">
            <img src="../images/asylum2.jpg" alt="Support facility" class="slide-img">
            <img src="../images/psychiatrist.jpg" alt="Counseling room" class="slide-img">
            <img src="../images/asylum1.jpg" alt="Care facility" class="slide-img">
          </div>
        </div>
        <div class="quick-nav-block">
          <span class="quick-nav-title">QUICK ACCESS</span>
          <div class="quick-nav-buttons">
            <a href="appointments.php" class="quick-btn dashboard-button"><svg class="nav-icon" width="18" height="18"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                <line x1="16" y1="2" x2="16" y2="6"></line>
                <line x1="8" y1="2" x2="8" y2="6"></line>
                <line x1="3" y1="10" x2="21" y2="10"></line>
              </svg><span class="qbtn-label">Appointments</span></a>
            <a href="reports.php" class="quick-btn dashboard-button"><svg class="nav-icon" width="18" height="18"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
              </svg><span class="qbtn-label">Reports</span></a>
            <a href="chat.php" class="quick-btn dashboard-button"><svg class="nav-icon" width="18" height="18"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
              </svg><span class="qbtn-label">Chat</span></a>
              <a href="assessments.php" class="quick-btn dashboard-button"><svg class="nav-icon" width="18" height="18"
                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
              </svg><span class="qbtn-label">Assessments</span></a>
          </div>
        </div>
      </div>
    </section>
  </main>
  <script src="../assets/js/animations.js" defer></script>
</body>

</html>