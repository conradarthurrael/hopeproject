<!DOCTYPE html>
<html lang="en">
<?php
include "mental-health-db.php";
session_start();
$portalRoleKey = strtolower(str_replace([' ', '-', '_'], '', (string) ($_SESSION['user_type'] ?? '')));
$portalHomePage = [
    'teacher' => 'teacher-homepage.php',
    'student' => 'student-homepage.php',
    'manager' => 'manager-homepage.php',
    'admin' => 'manager-homepage.php',
    'guidancecounsellor' => 'guidance-counsellor-homepage.php',
    'guidancecounselor' => 'guidance-counsellor-homepage.php'
][$portalRoleKey] ?? '../index.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id2 = $_SESSION['user_id'];

$profile = "SELECT name, email, age, sex, address, profilepic, user_status, user_type FROM users WHERE user_id='$user_id2'";
$profile2 = mysqli_query($conn, $profile);
$user_data = ($profile2 && mysqli_num_rows($profile2) > 0) ? mysqli_fetch_assoc($profile2) : [];

$manager_name = !empty($user_data['name']) ? $user_data['name'] : (!empty($_SESSION['name']) ? $_SESSION['name'] : 'Manager');
$manager_email = !empty($user_data['email']) ? $user_data['email'] : (!empty($_SESSION['email']) ? $_SESSION['email'] : '');
$manager_age = !empty($user_data['age']) ? $user_data['age'] : 'N/A';
$manager_sex = !empty($user_data['sex']) ? $user_data['sex'] : 'N/A';
$manager_address = !empty($user_data['address']) ? $user_data['address'] : 'Main Campus';
$manager_status = !empty($user_data['user_status']) ? $user_data['user_status'] : 'Approved';

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

$managers_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE user_type = 'Manager'");
$manager_count = $managers_q ? mysqli_fetch_assoc($managers_q)['cnt'] : 0;
$appointments_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM appointments");
$appointments_count = $appointments_q ? mysqli_fetch_assoc($appointments_q)['cnt'] : 0;
$active_users_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE last_activity >= NOW() - INTERVAL 1 DAY");
$active_users_count = $active_users_q ? mysqli_fetch_assoc($active_users_q)['cnt'] : 0;
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - H.O.P.E.</title>
    <link rel="stylesheet" href="../assets/css/manager-homepage.css?v=4">
</head>

<body>

    <?php
    $manager_name = $manager_name ?? (string) ($_SESSION['name'] ?? 'User');
    $has_profilepic = $has_profilepic ?? false;
    $profilepic_src = $profilepic_src ?? '';
    $pending_count = $pending_count ?? 0;
    $user_id2 = $user_id2 ?? ($_SESSION['user_id'] ?? 0);
    $sidebar_role = (string) ($_SESSION['user_type'] ?? 'User');
    ?>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="<?= htmlspecialchars($portalHomePage, ENT_QUOTES) ?>" class="brand-link">
                <img src="../images/hope-logo.png" alt="H.O.P.E. Logo" class="sidebar-logo">
            </a>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar-sm">
                <?php if (!empty($has_profilepic)): ?>
                    <img src="<?= $profilepic_src ?>" alt="<?= htmlspecialchars($manager_name) ?>">
                <?php else: ?>
                    <span class="avatar-initials"><?= strtoupper(substr($manager_name, 0, 1)) ?></span>
                <?php endif; ?>
            </div>
            <div class="user-details">
                <h4 class="user-name"><?= htmlspecialchars($manager_name) ?></h4>
                <span class="user-role"><?= htmlspecialchars($sidebar_role) ?></span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-section-title">MAIN NAVIGATION</span>
            <ul class="nav-list">
                <li>
                    <a href="<?= htmlspecialchars($portalHomePage, ENT_QUOTES) ?>" class="nav-item active">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
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
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Users &amp; Approvals</span>
                        <?php if (!empty($pending_count)): ?>
                            <span class="nav-counter"><?= $pending_count ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li>
                    <a href="guidance-counsellors.php" class="nav-item">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <span>Guidance Counsellors</span>
                    </a>
                </li>
                <li>
                    <a href="students.php" class="nav-item">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        <span>Students</span>
                    </a>
                </li>
                <li>
                    <a href="teachers.php" class="nav-item">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
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
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>Edit Profile</span>
                    </a>
                </li>
                <li>
                    <a href="mental-health-logout.php" class="nav-item nav-logout">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
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


    <main class="main-content">

        <header class="dashboard-header">
            <div class="header-titles">
                <span class="header-badge">OVERVIEW</span>
                <h1 class="header-title">Manager Dashboard</h1>
                <p class="header-subtitle">Welcome back, <?= htmlspecialchars($manager_name) ?>. Here is the operational
                    summary of your mental health system.</p>
            </div>
            <div class="header-actions">
                <div class="status-indicator">
                    <span class="status-dot"></span>
                    <span>System Active</span>
                </div>
                <a href="user-update.php?user_id=<?= $user_id2 ?>" class="btn-profile-quick">Edit Profile</a>
            </div>
        </header>

        <section class="analytics-grid" aria-label="Campus system analytics">
            <article class="analytics-card" data-bar-chart>
                <h2>User accounts by role</h2>
                <div class="chart-row" data-value="<?= (int) $manager_count ?>"><span>Managers</span><span
                        class="chart-track"><span class="chart-fill"></span></span><span
                        class="chart-value"><?= (int) $manager_count ?></span></div>
                <div class="chart-row" data-value="<?= (int) $gc_count ?>"><span>Counsellors</span><span
                        class="chart-track"><span class="chart-fill"></span></span><span
                        class="chart-value"><?= (int) $gc_count ?></span></div>
                <div class="chart-row" data-value="<?= (int) $student_count ?>"><span>Students</span><span
                        class="chart-track"><span class="chart-fill"></span></span><span
                        class="chart-value"><?= (int) $student_count ?></span></div>
                <div class="chart-row" data-value="<?= (int) $teacher_count ?>"><span>Teachers</span><span
                        class="chart-track"><span class="chart-fill"></span></span><span
                        class="chart-value"><?= (int) $teacher_count ?></span></div>
            </article>
            <article class="analytics-card" data-bar-chart>
                <h2>Appointments</h2>
                <div class="chart-row" data-value="<?= (int) $appointments_count ?>"><span>All appointments</span><span
                        class="chart-track"><span class="chart-fill"></span></span><span
                        class="chart-value"><?= (int) $appointments_count ?></span></div>
            </article>
            <article class="analytics-card" data-bar-chart>
                <h2>System activity</h2>
                <div class="chart-row" data-value="<?= (int) $active_users_count ?>"><span>Active in 24
                        hours</span><span class="chart-track"><span class="chart-fill"></span></span><span
                        class="chart-value"><?= (int) $active_users_count ?></span></div>
                <div class="chart-row" data-value="<?= (int) $pending_count ?>"><span>Pending approvals</span><span
                        class="chart-track"><span class="chart-fill"></span></span><span
                        class="chart-value"><?= (int) $pending_count ?></span></div>
            </article>
        </section>


        <section class="dashboard-grid">

            <div class="card profile-card">
                <div class="card-header">
                    <span class="card-tag">ADMINISTRATOR CARD</span>
                    <h2 class="card-title">Profile Overview</h2>
                </div>
                <div class="profile-card-body">
                    <div class="profile-avatar-wrap">
                        <?php if ($has_profilepic): ?>
                            <img src="<?= $profilepic_src ?>" alt="<?= htmlspecialchars($manager_name) ?>"
                                class="profile-avatar-lg">
                        <?php else: ?>
                            <div class="avatar-lg-placeholder">
                                <span><?= strtoupper(substr($manager_name, 0, 1)) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="profile-info-content">
                        <h3 class="profile-heading"><?= htmlspecialchars($manager_name) ?></h3>
                        <p class="profile-role-sub">Wellness Manager &bull; Administrator</p>

                        <div class="profile-meta-grid">
                            <div class="meta-item">
                                <span class="meta-label">EMAIL</span>
                                <span class="meta-value"><?= htmlspecialchars($manager_email) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">OFFICE LOCATION</span>
                                <span class="meta-value"><?= htmlspecialchars($manager_address) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">AGE / GENDER</span>
                                <span class="meta-value"><?= htmlspecialchars($manager_age) ?> &bull;
                                    <?= htmlspecialchars($manager_sex) ?></span>
                            </div>
                            <div class="meta-item">
                                <span class="meta-label">ROLE TYPE</span>
                                <span class="meta-value">Manager</span>
                            </div>
                        </div>

                        <p class="profile-quote">
                            Welcome to the H.O.P.E. Mental Health Guidance & Rehabilitation Management System. As a
                            manager, you have full oversight to approve accounts, direct student interventions, and
                            coordinate counseling personnel.
                        </p>

                        <div class="profile-actions">
                            <!-- Edit Profile Button -->
                            <a href="user-update.php?user_id=<?= $user_id2 ?>" class="btn-primary">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                                <span>Edit Profile</span>
                            </a>
                            <!-- Manage Users Button -->
                            <a href="users.php" class="btn-secondary">
                                <span>Manage Users</span> &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <div class="card showcase-card">
                <div class="card-header">
                    <span class="card-tag">WELLNESS FACILITIES</span>
                    <h2 class="card-title">Care & Testing Center</h2>
                </div>

                <div class="facility-grid">
                    <img src="../images/asylum1.jpg" alt="Care facility" class="facility-img">
                    <img src="../images/asylum2.jpg" alt="Support facility" class="facility-img">
                    <img src="../images/psychiatrist.jpg" alt="Counseling room" class="facility-img">
                </div>
                <div class="quick-nav-block">
                    <span class="quick-nav-title">DIRECT PORTAL ACCESS</span>
                    <div class="quick-nav-buttons">
                        <a href="users.php" class="quick-btn">
                            <span class="qbtn-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg></span>
                            <span class="qbtn-label">All Users</span>
                        </a>
                        <a href="guidance-counsellors.php" class="quick-btn">
                            <span class="qbtn-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg></span>
                            <span class="qbtn-label">Counsellors</span>
                        </a>
                        <a href="students.php" class="quick-btn">
                            <span class="qbtn-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                    <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                                </svg></span>
                            <span class="qbtn-label">Students</span>
                        </a>
                        <a href="teachers.php" class="quick-btn">
                            <span class="qbtn-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" aria-hidden="true">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                </svg></span>
                            <span class="qbtn-label">Teachers</span>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <script src="../assets/js/animations.js" defer></script>
    <script src="../assets/js/dashboard-charts.js" defer></script>
</body>

</html>