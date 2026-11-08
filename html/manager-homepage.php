<!DOCTYPE html>
<html lang="en">
<?php 
include "mental-health-db.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id2 = $_SESSION['user_id'];

// Retrieve manager profile data
$profile = "SELECT name, email, age, sex, address, profilepic, user_status, user_type FROM users WHERE user_id='$user_id2'";
$profile2 = mysqli_query($conn, $profile);
$user_data = ($profile2 && mysqli_num_rows($profile2) > 0) ? mysqli_fetch_assoc($profile2) : [];

$manager_name = !empty($user_data['name']) ? $user_data['name'] : (!empty($_SESSION['name']) ? $_SESSION['name'] : 'Manager');
$manager_email = !empty($user_data['email']) ? $user_data['email'] : (!empty($_SESSION['email']) ? $_SESSION['email'] : '');
$manager_age = !empty($user_data['age']) ? $user_data['age'] : 'N/A';
$manager_sex = !empty($user_data['sex']) ? $user_data['sex'] : 'N/A';
$manager_address = !empty($user_data['address']) ? $user_data['address'] : 'Main Campus';
$manager_status = !empty($user_data['user_status']) ? $user_data['user_status'] : 'Approved';

// Determine profile picture source
$has_profilepic = false;
$profilepic_src = "";
if (!empty($user_data['profilepic'])) {
    $finfo = finfo_open();
    $mime = @finfo_buffer($finfo, $user_data['profilepic'], FILEINFO_MIME_TYPE);
    if ($mime && strpos($mime, 'image/') === 0) {
        $has_profilepic = true;
        $profilepic_src = 'data: ' . $mime . ';base64,' . base64_encode($user_data['profilepic']);
    }
}

// Fetch live counts for manager dashboard metrics
$total_users_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users");
$total_users = $total_users_q ? mysqli_fetch_assoc($total_users_q)['cnt'] : 0;

$pending_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE user_status = 'Pending' OR user_type = 'PENDING'");
$pending_count = $pending_q ? mysqli_fetch_assoc($pending_q)['cnt'] : 0;

$gc_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE user_type = 'Guidancecounsellor'");
$gc_count = $gc_q ? mysqli_fetch_assoc($gc_q)['cnt'] : 0;

$students_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE user_type = 'Student'");
$student_count = $students_q ? mysqli_fetch_assoc($students_q)['cnt'] : 0;

$teachers_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE user_type = 'Teacher'");
$teacher_count = $teachers_q ? mysqli_fetch_assoc($teachers_q)['cnt'] : 0;
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - H ♡ P E</title>
    <link rel="stylesheet" href="../css/manager-homepage.css?v=50">
</head>
<body>
    <!-- Left-Side Navigation Bar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="manager-homepage.php" class="brand-link">
                <img src="../images/hope-logo.png" alt="H.O.P.E. Logo" class="sidebar-logo">
            </a>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar-sm">
                <?php if ($has_profilepic): ?>
                    <img src="<?= $profilepic_src ?>" alt="<?= htmlspecialchars($manager_name) ?>">
                <?php else: ?>
                    <span class="avatar-initials"><?= strtoupper(substr($manager_name, 0, 1)) ?></span>
                <?php endif; ?>
            </div>
            <div class="user-details">
                <h4 class="user-name"><?= htmlspecialchars($manager_name) ?></h4>
                <span class="user-role">Administrator</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-section-title">MAIN NAVIGATION</span>
            <ul class="nav-list">
                <li>
                    <a href="manager-homepage.php" class="nav-item active">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="users.php" class="nav-item">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Users & Approvals</span>
                        <?php if ($pending_count > 0): ?>
                            <span class="nav-counter"><?= $pending_count ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li>
                    <a href="guidance-counsellors.php" class="nav-item">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Guidance Counsellors</span>
                    </a>
                </li>
                <li>
                    <a href="students.php" class="nav-item">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        <span>Students</span>
                    </a>
                </li>
                <li>
                    <a href="teachers.php" class="nav-item">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        </svg>
                        <span>Teachers</span>
                    </a>
                </li>
            </ul>

            <span class="nav-section-title">ACCOUNT</span>
            <ul class="nav-list">
                <li>
                    <a href="user-update.php?user_id=<?= $user_id2 ?>" class="nav-item">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Edit Profile</span>
                    </a>
                </li>
                <li>
                    <a href="mental-health-logout.php" class="nav-item nav-logout">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span>Log Out</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Dashboard Area -->
    <main class="main-content">
        <!-- Top Bar Header -->
        <header class="dashboard-header">
            <div class="header-titles">
                <span class="header-badge">⦿ OVERVIEW</span>
                <h1 class="header-title">Manager Dashboard</h1>
                <p class="header-subtitle">Welcome back, <?= htmlspecialchars($manager_name) ?>. Here is the operational summary of your mental health system.</p>
            </div>
            <div class="header-actions">
                <div class="status-indicator">
                    <span class="status-dot"></span>
                    <span>System Active</span>
                </div>
                <a href="user-update.php?user_id=<?= $user_id2 ?>" class="btn-profile-quick">Edit Profile</a>
            </div>
        </header>

        <!-- Metric Stat Cards -->
        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon-wrapper icon-users">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg>
                </div>
                <div class="stat-data">
                    <span class="stat-label">TOTAL REGISTERED</span>
                    <h3 class="stat-number"><?= $total_users ?></h3>
                    <span class="stat-subtext">All campus accounts</span>
                </div>
            </div>

            <a href="users.php" class="stat-card stat-card-interactive <?= $pending_count > 0 ? 'card-pending' : '' ?>">
                <div class="stat-icon-wrapper icon-pending">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
                <div class="stat-data">
                    <span class="stat-label">PENDING APPROVALS</span>
                    <h3 class="stat-number"><?= $pending_count ?></h3>
                    <span class="stat-subtext">Requires action &rarr;</span>
                </div>
            </a>

            <a href="guidance-counsellors.php" class="stat-card stat-card-interactive">
                <div class="stat-icon-wrapper icon-counsellor">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div class="stat-data">
                    <span class="stat-label">COUNSELLORS</span>
                    <h3 class="stat-number"><?= $gc_count ?></h3>
                    <span class="stat-subtext">Active counselors &rarr;</span>
                </div>
            </a>

            <a href="students.php" class="stat-card stat-card-interactive">
                <div class="stat-icon-wrapper icon-student">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                </div>
                <div class="stat-data">
                    <span class="stat-label">STUDENTS</span>
                    <h3 class="stat-number"><?= $student_count ?></h3>
                    <span class="stat-subtext">Enrolled students &rarr;</span>
                </div>
            </a>

            <a href="teachers.php" class="stat-card stat-card-interactive">
                <div class="stat-icon-wrapper icon-teacher">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                </div>
                <div class="stat-data">
                    <span class="stat-label">TEACHERS</span>
                    <h3 class="stat-number"><?= $teacher_count ?></h3>
                    <span class="stat-subtext">Faculty staff &rarr;</span>
                </div>
            </a>
        </section>

        <!-- Dashboard Main Grid -->
        <section class="dashboard-grid">
            <!-- Manager Profile Showcase -->
            <div class="card profile-card">
                <div class="card-header">
                    <span class="card-tag">ADMINISTRATOR CARD</span>
                    <h2 class="card-title">Profile Overview</h2>
                </div>
                <div class="profile-card-body">
                    <div class="profile-avatar-wrap">
                        <?php if ($has_profilepic): ?>
                            <img src="<?= $profilepic_src ?>" alt="<?= htmlspecialchars($manager_name) ?>" class="profile-avatar-lg">
                        <?php else: ?>
                            <div class="avatar-lg-placeholder">
                                <span><?= strtoupper(substr($manager_name, 0, 1)) ?></span>
                            </div>
                        <?php endif; ?>
                        <div class="profile-badge-pill">
                            <span class="dot-online"></span> <?= htmlspecialchars($manager_status) ?>
                        </div>
                    </div>

                    <div class="profile-info-content">
                        <h3 class="profile-heading"><?= htmlspecialchars($manager_name) ?></h3>
                        <p class="profile-role-sub">Campus Manager &bull; Administrator</p>

                        <div class="profile-meta-grid">
                            <div class="meta-item">
                                <span class="meta-label">EMAIL</span>
                                <span class="meta-value"><?= htmlspecialchars($manager_email) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">CAMPUS OFFICE</span>
                                <span class="meta-value"><?= htmlspecialchars($manager_address) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">AGE / GENDER</span>
                                <span class="meta-value"><?= htmlspecialchars($manager_age) ?> &bull; <?= htmlspecialchars($manager_sex) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">ROLE TYPE</span>
                                <span class="meta-value">Manager</span>
                            </div>
                        </div>

                        <p class="profile-quote">
                            Welcome to the H.O.P.E. Mental Health Guidance & Rehabilitation Management System. As a manager, you have full oversight to approve accounts, direct student interventions, and coordinate counseling personnel.
                        </p>

                        <div class="profile-actions">
                            <a href="user-update.php?user_id=<?= $user_id2 ?>" class="btn-primary">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                <span>Edit Profile</span>
                            </a>
                            <a href="users.php" class="btn-secondary">
                                <span>Manage Users</span> &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campus Facility Slider & Quick Navigation -->
            <div class="card showcase-card">
                <div class="card-header">
                    <span class="card-tag">CAMPUS FACILITIES</span>
                    <h2 class="card-title">Care & Testing Center</h2>
                </div>

                <div class="slider-container">
                    <div class="slider-track">
                        <img src="../images/asylum1.jpg" alt="Campus Facility 1" class="slide-img">
                        <img src="../images/asylum2.jpg" alt="Campus Facility 2" class="slide-img">
                        <img src="../images/psychiatrist.jpg" alt="Counseling Room" class="slide-img">
                        <img src="../images/asylum1.jpg" alt="Campus Facility 1" class="slide-img">
                    </div>
                </div>

                <div class="quick-nav-block">
                    <span class="quick-nav-title">DIRECT PORTAL ACCESS</span>
                    <div class="quick-nav-buttons">
                        <a href="users.php" class="quick-btn">
                            <span class="qbtn-icon">👥</span>
                            <span class="qbtn-label">All Users</span>
                        </a>
                        <a href="guidance-counsellors.php" class="quick-btn">
                            <span class="qbtn-icon">🩺</span>
                            <span class="qbtn-label">Counsellors</span>
                        </a>
                        <a href="students.php" class="quick-btn">
                            <span class="qbtn-icon">🎓</span>
                            <span class="qbtn-label">Students</span>
                        </a>
                        <a href="teachers.php" class="quick-btn">
                            <span class="qbtn-icon">📚</span>
                            <span class="qbtn-label">Teachers</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
