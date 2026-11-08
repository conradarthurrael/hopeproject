<?php
include "mental-health-db.php";
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id2 = $_SESSION['user_id'];

// Retrieve logged-in manager profile
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

// Pending count for badge
$pending_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE user_status = 'Pending' OR user_type = 'PENDING'");
$pending_count = $pending_q ? mysqli_fetch_assoc($pending_q)['cnt'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users &amp; Approvals - H ♡ P E</title>
    <link rel="stylesheet" href="../css/manager-homepage.css?v=50">
    <link rel="stylesheet" href="../css/portal-pages.css?v=2">
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
                    <a href="manager-homepage.php" class="nav-item">
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
                    <a href="users.php" class="nav-item active">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        <span>Users &amp; Approvals</span>
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

    <!-- Main Content Area -->
    <main class="main-content">
        <!-- Page Header -->
        <header class="dashboard-header">
            <div class="header-titles">
                <span class="header-badge">⦿ MANAGEMENT</span>
                <h1 class="header-title">Users &amp; Approvals</h1>
                <p class="header-subtitle">Review, approve, and manage all registered accounts on the platform.</p>
            </div>
            <div class="header-actions">
                <?php if ($pending_count > 0): ?>
                <div class="status-indicator status-warning">
                    <span class="status-dot dot-amber"></span>
                    <span><?= $pending_count ?> Pending</span>
                </div>
                <?php else: ?>
                <div class="status-indicator">
                    <span class="status-dot"></span>
                    <span>All Approved</span>
                </div>
                <?php endif; ?>
            </div>
        </header>

        <!-- Search Card -->
        <section class="page-section">
            <div class="card search-card">
                <form method="POST" class="portal-search-form">
                    <div class="search-input-wrap">
                        <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="text" name="search1" class="portal-search-input" placeholder="Search users by name…" value="<?= isset($_POST['search1']) ? htmlspecialchars($_POST['search1']) : '' ?>">
                    </div>
                    <button type="submit" class="btn-primary">Search</button>
                </form>
            </div>
        </section>

        <!-- Users Grid Card -->
        <section class="page-section">
            <div class="card">
                <div class="card-header">
                    <span class="card-tag">ALL ACCOUNTS</span>
                    <h2 class="card-title">Registered Users</h2>
                </div>
                <div class="portal-grid">
                <?php
                $limit = 9;
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                if ($page < 1) { $page = 1; }
                $startingrecord = ($page - 1) * $limit;

                if (isset($_POST['search1']) && $_POST['search1'] !== '') {
                    $search1 = mysqli_real_escape_string($conn, $_POST['search1']);
                    $sql = "SELECT * FROM users WHERE name LIKE '%$search1%' LIMIT $limit OFFSET $startingrecord";
                    $count_sql = "SELECT COUNT(*) AS total FROM users WHERE name LIKE '%$search1%'";
                } else {
                    $search1 = '';
                    $sql = "SELECT * FROM users LIMIT $limit OFFSET $startingrecord";
                    $count_sql = "SELECT COUNT(*) AS total FROM users";
                }

                $users = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($users)) {
                    $finfo = finfo_open();
                    $mime = @finfo_buffer($finfo, $row['profilepic'], FILEINFO_MIME_TYPE);
                    $has_pic = $mime && strpos($mime, 'image/') === 0;
                    $status_class = ($row['user_status'] === 'Pending' || $row['user_type'] === 'PENDING') ? 'status-pending' : 'status-approved';
                    $status_label = ($row['user_status'] === 'Pending' || $row['user_type'] === 'PENDING') ? 'Pending' : ($row['user_status'] ?? 'Approved');
                ?>
                    <div class="portal-card">
                        <div class="portal-card-avatar">
                            <?php if ($has_pic): ?>
                                <img src="data: <?= $mime ?>;base64,<?= base64_encode($row['profilepic']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
                            <?php else: ?>
                                <div class="avatar-placeholder"><?= strtoupper(substr($row['name'], 0, 1)) ?></div>
                            <?php endif; ?>
                            <span class="portal-status-badge <?= $status_class ?>"><?= htmlspecialchars($status_label) ?></span>
                        </div>
                        <div class="portal-card-info">
                            <h3 class="portal-card-name"><?= htmlspecialchars($row['name']) ?></h3>
                            <div class="portal-card-meta">
                                <span class="meta-pill"><?= htmlspecialchars($row['user_type']) ?></span>
                                <span class="meta-item">Age <?= htmlspecialchars($row['age']) ?></span>
                                <span class="meta-item"><?= htmlspecialchars($row['sex']) ?></span>
                            </div>
                        </div>
                        <div class="portal-card-actions">
                            <a href="user-update.php?user_id=<?= $row['user_id'] ?>" class="btn-manage">Manage Access</a>
                        </div>
                    </div>
                <?php } ?>
                </div>

                <!-- Pagination -->
                <?php
                $count_result = mysqli_query($conn, $count_sql);
                $count_row = mysqli_fetch_assoc($count_result);
                $total = $count_row['total'];
                $total_pages = ceil($total / $limit);
                if ($total_pages > 1):
                ?>
                <div class="portal-pagination">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?= $i ?><?= $search1 ? '&search1=' . urlencode($search1) : '' ?>" class="page-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
                    <?php endfor; ?>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</body>
</html>
