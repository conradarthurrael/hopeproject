<!DOCTYPE html>
<html lang="en">
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
    if(!isset($_SESSION['user_id'])){
        header("Location: ../index.php");
        exit();
    }
    $roleKey = $portalRoleKey;
    if(!in_array($roleKey, ['teacher','manager','admin','guidancecounsellor','guidancecounselor'], true)){
        header("Location: " . $portalHomePage);
        exit();
    }
    $isTeacher = ($roleKey === 'teacher');
    if (isset($_POST['submit10'])) {
        $user_id4 = $_POST['user_id'];
        $behavior = $_POST['behavior'];
        $report = $_POST['report'];
        $incidentdate = $_POST['incidentdate'];
        $reportinsert = "INSERT INTO reports(user_id, behavior, report, incidentdate) VALUES('$user_id4','$behavior','$report','$incidentdate');";
        mysqli_query($conn, $reportinsert);
        header("Location: report-students.php");
        exit();
    }
    $report_target_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
    $report_target = null;
    if ($report_target_id > 0) {
        $reportquery = "SELECT * FROM users WHERE user_id='$report_target_id'";
        $reportquery2 = mysqli_query($conn, $reportquery);
        $report_target = $reportquery2 ? mysqli_fetch_assoc($reportquery2) : null;
    }
    ?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Students - H.O.P.E.</title>
<link rel="stylesheet" href="../assets/css/report-students.css">
</head>
<body class="dashboard-page">
<?php
$manager_name = $manager_name ?? (string)($_SESSION['name'] ?? 'User');
$has_profilepic = $has_profilepic ?? false;
$profilepic_src = $profilepic_src ?? '';
$pending_count = $pending_count ?? 0;
$user_id2 = $user_id2 ?? ($_SESSION['user_id'] ?? 0);
$sidebar_role = (string)($_SESSION['user_type'] ?? 'User');
?>
<?php if (!empty($isTeacher)): ?>
    <aside class="sidebar">
        <div class="sidebar-brand"><a href="teacher-homepage.php" class="brand-link"><img src="../images/hope-logo.png" alt="H.O.P.E. Logo" class="sidebar-logo"></a></div>
        <div class="sidebar-user"><div class="user-avatar-sm"><?php if (!empty($has_profilepic)): ?><img src="<?= $profilepic_src ?>" alt="<?= htmlspecialchars($manager_name) ?>"><?php else: ?><span class="avatar-initials"><?= strtoupper(substr($manager_name, 0, 1)) ?></span><?php endif; ?></div><div class="user-details"><h4 class="user-name"><?= htmlspecialchars($manager_name) ?></h4><span class="user-role">Teacher</span></div></div>
        <nav class="sidebar-nav"><span class="nav-section-title">MAIN NAVIGATION</span><ul class="nav-list">
            <li><a href="teacher-homepage.php" class="nav-item"><span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg></span><span>Home</span></a></li>
            <li><a href="students.php" class="nav-item active"><span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg></span><span>Students</span></a></li>
            <li><a href="reports.php" class="nav-item"><span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg></span><span>Reports</span></a></li>
            <li><a href="appointments.php" class="nav-item"><span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span><span>Appointments</span></a></li>
            <li><a href="chat.php" class="nav-item"><span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></span><span>Chat</span></a></li>
        </ul><span class="nav-section-title">ACCOUNT</span><ul class="nav-list">
            <li><a href="user-update.php?user_id=<?= (int)$user_id2 ?>" class="nav-item"><span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></span><span>Edit Profile</span></a></li>
            <li><a href="mental-health-logout.php" class="nav-item nav-logout"><span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg></span><span>Log Out</span></a></li>
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
                    <a href="<?= htmlspecialchars($portalHomePage, ENT_QUOTES) ?>" class="nav-item active">
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
<?php endif; ?>
    <main class="main-content">
        <header class="dashboard-header">
            <div class="header-titles">
                <span class="header-badge">REPORTS</span>
                <h1 class="header-title">Report Students</h1>
                <p class="header-subtitle">Search for students and submit behavior or incident reports.</p>
            </div>
        </header>
        <section class="page-section">
            <div class="report-filters-card search-card searchcontainer">
                <div class="searchbar">
                    <form method="POST">
                        <input type="text" name="search1" class="search1" placeholder="Search students...">
                        <button type="submit" class="submit1">Search</button>
                    </form>
                </div>
            </div>
            <div class="studentcontainer">
<?php
$limit = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$startingrecord = ($page - 1) * $limit;
    if(isset($_POST['search1'])){
        $search1 = $_POST['search1'];
    $searchstudents = "SELECT * FROM users WHERE user_type='Student' AND name LIKE '%$search1%' LIMIT $limit OFFSET $startingrecord";
    $students = mysqli_query($conn, $searchstudents);
    while($row = mysqli_fetch_assoc($students)){
        $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
        ?>
    <div class="studentprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer2">
      <p class="p">Name: <?= $row['name'] ?></p>
      <p class="p">Age: <?= $row['age'] ?></p>
      <p class="p">Sex: <?= $row['sex'] ?></p>
      <p class="p">Type: <?= $row['user_type'] ?></p>
    </div>
      <a class="edit" name = "edit" href="report-students.php?user_id=<?=$row['user_id']?>">Report Student</a>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM users WHERE user_type='Student' AND name LIKE '%$search1%'";
              $count_result = $conn-> query($count_sql);
              $count_row = $count_result->fetch_assoc();
              $total = $count_row['total'];
              $total_pages = ceil($total / $limit);
              for($i = 1; $i <= $total_pages; $i++) {
        ?>
        <a id="pages2" href="?page=<?php echo $i; ?>"><?php echo $i ?></a>
        <?php } ?>
              </div>
    </div>
        <?php } }
        else if(empty($_POST['search1'])){
           $defaultpatients = "SELECT * FROM users WHERE user_type='Student' LIMIT $limit OFFSET $startingrecord";
           $patients2 = mysqli_query($conn, $defaultpatients);
           while($row = mysqli_fetch_assoc($patients2)){
            $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
            ?>
            <div class="studentprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer2">
      <p class="p">Name: <?= $row['name'] ?></p>
      <p class="p">Age: <?= $row['age'] ?></p>
      <p class="p">Sex: <?= $row['sex'] ?></p>
      <p class="p">Type: <?= $row['user_type'] ?></p>
      <a class="edit" name = "edit" href="report-students.php?user_id=<?=$row['user_id']?>">Report Student</a>
           </div>
           </div>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM users WHERE user_type='Student'";
              $count_result = $conn-> query($count_sql);
              $count_row = $count_result->fetch_assoc();
              $total = $count_row['total'];
              $total_pages = ceil($total / $limit);
              for($i = 1; $i <= $total_pages; $i++) {
        ?>
        <a id="pages2" href="?page=<?php echo $i; ?>"><?php echo $i ?></a>
        <?php } ?>
              </div>
        <?php } } ?>
            </div>
            <?php if ($report_target): ?>
            <section class="report-card report-entry-card booking-form-card">
                <h2>Report <?= htmlspecialchars($report_target['name']) ?></h2>
                <form method="POST" action="report-students.php" class="report-entry-form">
                    <input type="hidden" name="user_id" value="<?= $report_target_id ?>">
                    <label for="report-behavior">Behavior</label>
                    <select id="report-behavior" name="behavior" class="selectbehavior">
                        <option value="emotional">Emotional</option>
                        <option value="bullying">Bullying</option>
                        <option value="academic">Academic</option>
                        <option value="other">Other</option>
                    </select>
                    <label for="report-details">Report details</label>
                    <textarea id="report-details" name="report" rows="8" required></textarea>
                    <label for="report-date">Incident date</label>
                    <input id="report-date" type="datetime-local" name="incidentdate" class="reportdate" required>
                    <button type="submit" name="submit10" class="reportbutton btn-primary">Submit Report</button>
                </form>
            </section>
            <?php endif; ?>
        </section>
    </main>
    <script src="../assets/js/animations.js" defer></script>
</body>
</html>
