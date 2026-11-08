<?php
include "mental-health-db.php";
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../index.php"); exit(); }
$user_id = $_SESSION['user_id'];
$result = mysqli_query($conn, "SELECT name, email, age, sex, address, profilepic, user_status FROM users WHERE user_id='$user_id'");
$user = ($result && mysqli_num_rows($result)) ? mysqli_fetch_assoc($result) : [];
$name = $user['name'] ?? ($_SESSION['name'] ?? 'Student');
$email = $user['email'] ?? ($_SESSION['email'] ?? '');
$age = $user['age'] ?? 'N/A'; $sex = $user['sex'] ?? 'N/A';
$address = $user['address'] ?? 'Main Campus'; $status = $user['user_status'] ?? 'Approved';
$has_photo = false; $photo = '';
if (!empty($user['profilepic'])) { $finfo = finfo_open(); $mime = @finfo_buffer($finfo, $user['profilepic'], FILEINFO_MIME_TYPE); if ($mime && strpos($mime, 'image/') === 0) { $has_photo = true; $photo = 'data:' . $mime . ';base64,' . base64_encode($user['profilepic']); } if ($finfo) finfo_close($finfo); }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Dashboard - H.O.P.E.</title>
  <link rel="stylesheet" href="../css/manager-homepage.css?v=50">
</head>
<body>
  <aside class="sidebar">
    <div class="sidebar-brand"><a href="student-homepage.php" class="brand-link"><img src="../images/hope-logo.png" alt="H.O.P.E. Logo" class="sidebar-logo"></a></div>
    <div class="sidebar-user"><div class="user-avatar-sm"><?php if ($has_photo): ?><img src="<?= $photo ?>" alt="<?= htmlspecialchars($name) ?>"><?php else: ?><span class="avatar-initials"><?= strtoupper(substr($name, 0, 1)) ?></span><?php endif; ?></div><div class="user-details"><h4 class="user-name"><?= htmlspecialchars($name) ?></h4><span class="user-role">Student</span></div></div>
    <nav class="sidebar-nav"><span class="nav-section-title">MAIN NAVIGATION</span><ul class="nav-list">
      <li><a href="student-homepage.php" class="nav-item active"><span class="nav-icon">▦</span><span>Dashboard</span></a></li>
      <li><a href="chat-2.php" class="nav-item"><span class="nav-icon">◌</span><span>Chat Support</span></a></li>
      <li><a href="book-an-appointment.php" class="nav-item"><span class="nav-icon">□</span><span>Appointments</span></a></li>
    </ul><span class="nav-section-title">ACCOUNT</span><ul class="nav-list">
      <li><a href="user-update-3.php?user_id=<?= $user_id ?>" class="nav-item"><span class="nav-icon">✎</span><span>Edit Profile</span></a></li>
      <li><a href="mental-health-logout.php" class="nav-item nav-logout"><span class="nav-icon">↪</span><span>Log Out</span></a></li>
    </ul></nav>
  </aside>
  <main class="main-content">
    <header class="dashboard-header"><div class="header-titles"><span class="header-badge">STUDENT PORTAL</span><h1 class="header-title">Student Dashboard</h1><p class="header-subtitle">Welcome back, <?= htmlspecialchars($name) ?>. Your mental health and wellbeing are important to us.</p></div><div class="header-actions"><div class="status-indicator"><span class="status-dot"></span><span>Account Active</span></div><a href="user-update-3.php?user_id=<?= $user_id ?>" class="btn-profile-quick">Edit Profile</a></div></header>
    <section class="stats-grid">
      <a href="chat-2.php" class="stat-card stat-card-interactive"><div class="stat-icon-wrapper icon-counsellor"><span>◌</span></div><div class="stat-data"><span class="stat-label">CHAT SUPPORT</span><h3 class="stat-number">&rarr;</h3><span class="stat-subtext">Connect with support</span></div></a>
      <a href="book-an-appointment.php" class="stat-card stat-card-interactive"><div class="stat-icon-wrapper icon-student"><span>□</span></div><div class="stat-data"><span class="stat-label">APPOINTMENTS</span><h3 class="stat-number">&rarr;</h3><span class="stat-subtext">Book a session</span></div></a>
      <div class="stat-card"><div class="stat-icon-wrapper icon-teacher"><span>✓</span></div><div class="stat-data"><span class="stat-label">ACCOUNT STATUS</span><h3 class="stat-number">Active</h3><span class="stat-subtext">Student access enabled</span></div></div>
    </section>
    <section class="dashboard-grid">
      <div class="card profile-card"><div class="card-header"><span class="card-tag">STUDENT PROFILE</span><h2 class="card-title">Profile Overview</h2></div><div class="profile-card-body"><div class="profile-avatar-wrap"><?php if ($has_photo): ?><img src="<?= $photo ?>" alt="<?= htmlspecialchars($name) ?>" class="profile-avatar-lg"><?php else: ?><div class="avatar-lg-placeholder"><span><?= strtoupper(substr($name, 0, 1)) ?></span></div><?php endif; ?><div class="profile-badge-pill"><span class="dot-online"></span><?= htmlspecialchars($status) ?></div></div><div class="profile-info-content"><h3 class="profile-heading"><?= htmlspecialchars($name) ?></h3><p class="profile-role-sub">Student &bull; Wellness Community</p><div class="profile-meta-grid"><div class="meta-item"><span class="meta-label">EMAIL</span><span class="meta-value"><?= htmlspecialchars($email) ?></span></div><div class="meta-item"><span class="meta-label">CAMPUS</span><span class="meta-value"><?= htmlspecialchars($address) ?></span></div><div class="meta-item"><span class="meta-label">AGE / GENDER</span><span class="meta-value"><?= htmlspecialchars($age) ?> &bull; <?= htmlspecialchars($sex) ?></span></div><div class="meta-item"><span class="meta-label">ROLE TYPE</span><span class="meta-value">Student</span></div></div><p class="profile-quote">You do not have to face challenges alone. Explore support resources, connect with the guidance team, and take the next step in your wellness journey.</p><div class="profile-actions"><a href="chat-2.php" class="btn-primary">Open Chat Support</a><a href="book-an-appointment.php" class="btn-secondary">Book Appointment &rarr;</a></div></div></div></div>
      <div class="card showcase-card"><div class="card-header"><span class="card-tag">CARE & SUPPORT</span><h2 class="card-title">Your H.O.P.E. Space</h2></div><div class="slider-container"><div class="slider-track"><img src="../images/asylum1.jpg" alt="Care facility" class="slide-img"><img src="../images/asylum2.jpg" alt="Support facility" class="slide-img"><img src="../images/psychiatrist.jpg" alt="Counseling room" class="slide-img"><img src="../images/asylum1.jpg" alt="Care facility" class="slide-img"></div></div><div class="quick-nav-block"><span class="quick-nav-title">QUICK ACCESS</span><div class="quick-nav-buttons"><a href="chat-2.php" class="quick-btn"><span class="qbtn-icon">◌</span><span class="qbtn-label">Chat Support</span></a><a href="book-an-appointment.php" class="quick-btn"><span class="qbtn-icon">□</span><span class="qbtn-label">Appointments</span></a></div></div></div>
    </section>
  </main>
</body>
</html>
