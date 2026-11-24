<?php
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
    header("Location: mental-health-login.php");
    exit();
}
if ($portalRoleKey !== 'student') {
    header("Location: " . $portalHomePage);
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book an Appointment - H.O.P.E.</title>
<link rel="stylesheet" href="../assets/css/book-an-appointment.css">
</head>

<body class="dashboard-page">
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
                    <a href="<?= htmlspecialchars($portalHomePage, ENT_QUOTES) ?>" class="nav-item">
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
                    <a href="chat.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg><span>Chat
                            Support</span></a>
                </li>
                <li>
                    <a href="book-an-appointment.php" class="nav-item active"><span
                            class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span><span>Appointments</span></a>
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
                <span class="header-badge">APPOINTMENTS</span>
                <h1 class="header-title">Guidance Counsellors</h1>
                <p class="header-subtitle">Find an approved guidance counsellor and book your appointment.</p>
            </div>
        </header>
        <section class="page-section">
            <div class="search-card search-toolbar directory-search">
                <form method="POST" class="portal-search-form">
                    <input type="text" name="search1" class="search1 portal-search-input" placeholder="Search guidance counsellors..." value="<?= isset($_POST['search1']) ? htmlspecialchars($_POST['search1'], ENT_QUOTES) : '' ?>">
                    <button type="submit" class="submit1 btn-primary">Search</button>
                </form>
            </div>
            <div class="counsellorcontainer counsellor-directory-grid">
                    <?php
                    $limit = 9;
                    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                    if ($page < 1) {
                        $page = 1;
                    }
                    $startingrecord = ($page - 1) * $limit;
                    if (isset($_POST['search1'])) {
                        $search1 = $_POST['search1'];
                        $searchcounsellors = "SELECT * FROM users WHERE user_type='Guidancecounsellor' AND name LIKE '%$search1%' LIMIT $limit OFFSET $startingrecord";
                        $counsellors = mysqli_query($conn, $searchcounsellors);
                        while ($row = mysqli_fetch_assoc($counsellors)) {
                            $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
                            ?>
                            <div class="counsellorprofile">
                                <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: ' . $mime . ';base64,' . base64_encode($row['profilepic']) . '">'; ?>
                                </td>
                                <div class="infocontainer3">
                                    <p class="p">Name: <?= $row['name'] ?></p>
                                    <p class="p">Age: <?= $row['age'] ?></p>
                                    <p class="p">Sex: <?= $row['sex'] ?></p>
                                    <p class="p">Type: <?= $row['user_type'] ?></p>
                                </div>
                                <a class="bookanappointment" name="edit"
                                    href="appointment.php?user_id=<?= $row['user_id'] ?>">Book an Appointment</a>
                                <?php $count_sql = "SELECT COUNT(*) AS total FROM users WHERE user_type='Guidancecounsellor' AND name LIKE '%$search1%'";
                                $count_result = $conn->query($count_sql);
                                $count_row = $count_result->fetch_assoc();
                                $total = $count_row['total'];
                                $total_pages = ceil($total / $limit);
                                for ($i = 1; $i <= $total_pages; $i++) {
                                    ?>
                                    <a id="pages3" href="?page=<?php echo $i; ?>"><?php echo $i ?></a>
                                <?php } ?>
                            </div>

                        <?php }
                    } else if (empty($_POST['search1'])) {
                        $defaultcounsellors = "SELECT * FROM users WHERE user_type='Guidancecounsellor' LIMIT $limit OFFSET $startingrecord";
                        $counsellors2 = mysqli_query($conn, $defaultcounsellors);
                        while ($row = mysqli_fetch_assoc($counsellors2)) {
                            $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
                            ?>
                                <div class="counsellorprofile">
                                    <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: ' . $mime . ';base64,' . base64_encode($row['profilepic']) . '">'; ?>
                                    </td>
                                    <div class="infocontainer3">
                                        <p class="p">Name: <?= $row['name'] ?></p>
                                        <p class="p">Age: <?= $row['age'] ?></p>
                                        <p class="p">Sex: <?= $row['sex'] ?></p>
                                        <p class="p">Type: <?= $row['user_type'] ?></p>
                                    </div>
                                    <a class="bookanappointment" name="edit"
                                        href="appointment.php?user_id=<?= $row['user_id'] ?>">Book an Appointment</a>
                                    <div class="pages">
                                    <?php $count_sql = "SELECT COUNT(*) AS total FROM users WHERE user_type='Guidancecounsellor'";
                                    $count_result = $conn->query($count_sql);
                                    $count_row = $count_result->fetch_assoc();
                                    $total = $count_row['total'];
                                    $total_pages = ceil($total / $limit);
                                    for ($i = 1; $i <= $total_pages; $i++) {
                                        ?>
                                            <a id="pages3" href="?page=<?php echo $i; ?>"><?php echo $i ?></a>
                                    <?php } ?>
                                    </div>
                                </div>
                        <?php }
                    } ?>
            </div>
        </section>
    </main>
    <script src="../assets/js/animations.js" defer></script>
</body>

</html>