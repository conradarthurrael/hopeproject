<?php
include "mental-health-db.php";
session_start();
$portalRoleKey = strtolower(str_replace([' ', '-', '_'], '', (string)($_SESSION['user_type'] ?? '')));
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
if (!in_array($portalRoleKey, ['manager', 'admin', 'teacher', 'guidancecounsellor', 'guidancecounselor'], true)) {
    header("Location: " . $portalHomePage);
    exit();
}
$isTeacher = ($portalRoleKey === 'teacher');
$isManagerViewer = in_array($portalRoleKey, ['manager', 'admin'], true);
$isCounsellor = in_array($portalRoleKey, ['guidancecounsellor', 'guidancecounselor'], true);

$user_id2 = (int)$_SESSION['user_id'];

$profile = "SELECT name, email, profilepic FROM users WHERE user_id='$user_id2'";
$profile2 = mysqli_query($conn, $profile);
$user_data = ($profile2 && mysqli_num_rows($profile2) > 0) ? mysqli_fetch_assoc($profile2) : [];

$manager_name = !empty($user_data['name']) ? $user_data['name'] : (!empty($_SESSION['name']) ? $_SESSION['name'] : 'Manager');

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

$pending_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE user_status = 'Pending' OR user_type = 'PENDING'");
$pending_count = $pending_q ? mysqli_fetch_assoc($pending_q)['cnt'] : 0;

$students_total_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE user_type='Student'");
$students_total = $students_total_q ? mysqli_fetch_assoc($students_total_q)['cnt'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - H.O.P.E.</title>
<link rel="stylesheet" href="../assets/css/students.css">
</head>
<body class="dashboard-page">
    <?php
    $sidebar_role = $isCounsellor ? 'GUIDANCE COUNSELLOR' : (string)($_SESSION['user_type'] ?? 'User');
    ?>
    <?php if ($isTeacher): ?>
        <aside class="sidebar">
            <div class="sidebar-brand"><a href="teacher-homepage.php" class="brand-link"><img src="../images/hope-logo.png" alt="H.O.P.E. Logo" class="sidebar-logo"></a></div>
            <div class="sidebar-user"><div class="user-avatar-sm"><?php if (!empty($has_profilepic)): ?><img src="<?= htmlspecialchars($profilepic_src, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($manager_name) ?>"><?php else: ?><span class="avatar-initials"><?= strtoupper(substr($manager_name, 0, 1)) ?></span><?php endif; ?></div><div class="user-details"><h4 class="user-name"><?= htmlspecialchars($manager_name) ?></h4><span class="user-role">Teacher</span></div></div>
            <nav class="sidebar-nav"><span class="nav-section-title">MAIN NAVIGATION</span><ul class="nav-list">
                <li><a href="teacher-homepage.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg><span>Home</span></a></li>
                <li><a href="students.php" class="nav-item active"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg><span>Students</span></a></li>
                <li><a href="reports.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg><span>Reports</span></a></li>
                <li><a href="appointments.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Appointments</span></a></li>
                <li><a href="chat.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg><span>Chat</span></a></li>
            </ul><span class="nav-section-title">ACCOUNT</span><ul class="nav-list">
                <li><a href="user-update.php?user_id=<?= (int)$user_id2 ?>" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg><span>Edit Profile</span></a></li>
                <li><a href="mental-health-logout.php" class="nav-item nav-logout"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg><span>Log Out</span></a></li>
            </ul></nav>
        </aside>
    <?php elseif ($isCounsellor): ?>
        <aside class="sidebar">
            <div class="sidebar-brand"><a href="guidance-counsellor-homepage.php" class="brand-link"><img src="../images/hope-logo.png" alt="H.O.P.E. Logo" class="sidebar-logo"></a></div>
            <div class="sidebar-user"><div class="user-avatar-sm"><?php if (!empty($has_profilepic)): ?><img src="<?= htmlspecialchars($profilepic_src, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($manager_name) ?>"><?php else: ?><span class="avatar-initials"><?= strtoupper(substr($manager_name, 0, 1)) ?></span><?php endif; ?></div><div class="user-details"><h4 class="user-name"><?= htmlspecialchars($manager_name) ?></h4><span class="user-role">GUIDANCE COUNSELLOR</span></div></div>
            <nav class="sidebar-nav"><span class="nav-section-title">MAIN NAVIGATION</span><ul class="nav-list">
                <li><a href="guidance-counsellor-homepage.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg><span>Home</span></a></li>
                <li><a href="students.php" class="nav-item active"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg><span>Students</span></a></li>
                <li><a href="reports.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg><span>Reports</span></a></li>
                <li><a href="appointments.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Appointments</span></a></li>
                <li><a href="chat.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg><span>Chat</span></a></li>
            </ul><span class="nav-section-title">ACCOUNT</span><ul class="nav-list">
                <li><a href="user-update.php?user_id=<?= (int)$user_id2 ?>" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg><span>Edit Profile</span></a></li>
                <li><a href="mental-health-logout.php" class="nav-item nav-logout"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg><span>Log Out</span></a></li>
            </ul></nav>
        </aside>
    <?php else: ?>
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
                    <a href="<?= htmlspecialchars($portalHomePage, ENT_QUOTES) ?>" class="nav-item">
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
                        <span>Users &amp; Approvals</span>
                        <?php if (!empty($pending_count)): ?>
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
                    <a href="students.php" class="nav-item active">
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
    <?php endif; ?>

    <main class="main-content">
        <header class="dashboard-header">
            <div class="header-titles">
                <span class="header-badge">STUDENTS</span>
                <h1 class="header-title">Students</h1>
                <p class="header-subtitle">Browse and manage all enrolled students in the mental health system.</p>
            </div>
            <div class="header-actions">
                <div class="status-indicator">
                    <span class="status-dot"></span>
                    <span><?= $students_total ?> Students</span>
                </div>
            </div>
        </header>

        
        <section class="page-section">
            <div class="card search-card">
                <form method="POST" class="portal-search-form">
                    <div class="search-input-wrap">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" name="search1" class="portal-search-input" placeholder="Search students by name..." value="<?= isset($_POST['search1']) ? htmlspecialchars($_POST['search1']) : '' ?>">
                    </div>
                    <button type="submit" class="btn-primary">Search</button>
                </form>
            </div>
        </section>

        
        <section class="page-section">
            <div class="card">
                <div class="card-header">
                    <span class="card-tag">STUDENT ROSTER</span>
                    <h2 class="card-title">Enrolled Students</h2>
                </div>
                <div class="portal-grid">
                <?php
                $limit = 9;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                if ($page < 1) { $page = 1; }
                $startingrecord = ($page - 1) * $limit;

                if (isset($_POST['search1']) && $_POST['search1'] !== '') {
                    $search1 = mysqli_real_escape_string($conn, $_POST['search1']);
                    $sql = "SELECT * FROM users WHERE user_type='Student' AND name LIKE '%$search1%' LIMIT $limit OFFSET $startingrecord";
                    $count_sql = "SELECT COUNT(*) AS total FROM users WHERE user_type='Student' AND name LIKE '%$search1%'";
                } else {
                    $search1 = '';
                    $sql = "SELECT * FROM users WHERE user_type='Student' LIMIT $limit OFFSET $startingrecord";
                    $count_sql = "SELECT COUNT(*) AS total FROM users WHERE user_type='Student'";
                }

                $students = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($students)) {
                    $finfo = finfo_open();
                    $mime = @finfo_buffer($finfo, $row['profilepic'], FILEINFO_MIME_TYPE);
                    $has_pic = $mime && strpos($mime, 'image/') === 0;
                ?>
                    <div class="portal-card">
                        <div class="portal-card-avatar">
                            <?php if ($has_pic): ?>
                                <img src="data: <?= $mime ?>;base64,<?= base64_encode($row['profilepic']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                            <?php else: ?>
                                <div class="avatar-placeholder"><?= strtoupper(substr($row['name'], 0, 1)) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="portal-card-info">
                            <h3 class="portal-card-name"><?= htmlspecialchars($row['name']) ?></h3>
                            <div class="portal-card-meta">
                                <span class="meta-pill">Student</span>
                                <span class="meta-item">Age <?= htmlspecialchars($row['age']) ?></span>
                                <span class="meta-item"><?= htmlspecialchars($row['sex']) ?></span>
                            </div>
                        </div>
                        <?php if ($isManagerViewer): ?>
                        <div class="portal-card-actions">
                            <a href="user-update.php?user_id=<?= $row['user_id'] ?>" class="btn-manage">Edit Profile</a>
                            <a href="user-delete.php?user_id=<?= $row['user_id'] ?>&amp;return_to=students.php" class="btn-delete" onclick="return confirm('Delete this student?')">Remove</a>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php } ?>
                </div>

                
                <?php
                $count_result = mysqli_query($conn, $count_sql);
                $count_row = mysqli_fetch_assoc($count_result);
                $total = $count_row['total'];
                $total_pages = ceil($total / $limit);
                if ($total_pages > 1):
                ?>
                <div class="portal-pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?>" class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
<script src="../assets/js/animations.js" defer></script>
</body>
</html>
