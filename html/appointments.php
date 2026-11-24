<!DOCTYPE html>
<html lang="en">
<?php 
    include "mental-health-db.php";
    session_start();
    if(!isset($_SESSION['user_id'])){
        header("Location: ../index.php");
        exit();
    }
    $roleKey = strtolower(str_replace([' ', '-', '_'], '', (string)($_SESSION['user_type'] ?? '')));
    $portalHomePage = [
        'teacher' => 'teacher-homepage.php',
        'student' => 'student-homepage.php',
        'manager' => 'manager-homepage.php',
        'admin' => 'manager-homepage.php',
        'guidancecounsellor' => 'guidance-counsellor-homepage.php',
        'guidancecounselor' => 'guidance-counsellor-homepage.php'
    ][$roleKey] ?? '../index.php';
    if(!in_array($roleKey, ['manager','admin','teacher','guidancecounsellor','guidancecounselor'], true)){
        header("Location: " . $portalHomePage);
        exit();
    }
    $isTeacher = ($roleKey === 'teacher');
    $isCounsellor = in_array($roleKey, ['guidancecounsellor','guidancecounselor'], true);
    $user_id2 = (int)$_SESSION['user_id'];

    $profile = "SELECT name, profilepic, user_type FROM users WHERE user_id='$user_id2'";
    $profile2 = mysqli_query($conn, $profile);
    $user_data = ($profile2 && mysqli_num_rows($profile2) > 0) ? mysqli_fetch_assoc($profile2) : [];
    $sidebar_name = !empty($user_data['name']) ? $user_data['name'] : (!empty($_SESSION['name']) ? $_SESSION['name'] : 'User');
    $sidebar_role = $isCounsellor ? 'GUIDANCE COUNSELLOR' : (!empty($user_data['user_type']) ? $user_data['user_type'] : (!empty($_SESSION['user_type']) ? $_SESSION['user_type'] : 'User'));
    $has_profilepic = false;
    $profilepic_src = "";
    if (!empty($user_data['profilepic'])) {
        $finfo = finfo_open();
        $mime = $finfo ? @finfo_buffer($finfo, $user_data['profilepic'], FILEINFO_MIME_TYPE) : '';
        if ($mime && strpos($mime, 'image/') === 0) {
            $has_profilepic = true;
            $profilepic_src = 'data: ' . $mime . ';base64,' . base64_encode($user_data['profilepic']);
        }
        if ($finfo) { finfo_close($finfo); }
    }
    $pending_count = 0;
    if (in_array($roleKey, ['manager','admin'], true)) {
        $pending_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE user_status = 'Pending' OR user_type = 'PENDING'");
        $pending_count = $pending_q ? (int)(mysqli_fetch_assoc($pending_q)['cnt'] ?? 0) : 0;
    }
    $manager_name = $sidebar_name;
    ?>
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments - H.O.P.E.</title>
<link rel="stylesheet" href="../assets/css/appointments.css">
</head>
<body class="dashboard-page">
<?php if (!empty($isTeacher)): ?>
    <aside class="sidebar">
        <div class="sidebar-brand"><a href="teacher-homepage.php" class="brand-link"><img src="../images/hope-logo.png" alt="H.O.P.E. Logo" class="sidebar-logo"></a></div>
        <div class="sidebar-user"><div class="user-avatar-sm"><?php if (!empty($has_profilepic)): ?><img src="<?= htmlspecialchars($profilepic_src, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($sidebar_name) ?>"><?php else: ?><span class="avatar-initials"><?= strtoupper(substr($sidebar_name, 0, 1)) ?></span><?php endif; ?></div><div class="user-details"><h4 class="user-name"><?= htmlspecialchars($sidebar_name) ?></h4><span class="user-role">Teacher</span></div></div>
        <nav class="sidebar-nav"><span class="nav-section-title">MAIN NAVIGATION</span><ul class="nav-list">
            <li><a href="teacher-homepage.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg><span>Home</span></a></li>
            <li><a href="students.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg><span>Students</span></a></li>
            <li><a href="reports.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg><span>Reports</span></a></li>
            <li><a href="appointments.php" class="nav-item active"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Appointments</span></a></li>
            <li><a href="chat.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg><span>Chat</span></a></li>
        </ul><span class="nav-section-title">ACCOUNT</span><ul class="nav-list">
            <li><a href="user-update.php?user_id=<?= (int)$user_id2 ?>" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg><span>Edit Profile</span></a></li>
            <li><a href="mental-health-logout.php" class="nav-item nav-logout"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg><span>Log Out</span></a></li>
        </ul></nav>
    </aside>
<?php elseif (!empty($isCounsellor)): ?>
    <aside class="sidebar">
        <div class="sidebar-brand"><a href="guidance-counsellor-homepage.php" class="brand-link"><img src="../images/hope-logo.png" alt="H.O.P.E. Logo" class="sidebar-logo"></a></div>
        <div class="sidebar-user"><div class="user-avatar-sm"><?php if (!empty($has_profilepic)): ?><img src="<?= htmlspecialchars($profilepic_src, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($sidebar_name) ?>"><?php else: ?><span class="avatar-initials"><?= strtoupper(substr($sidebar_name, 0, 1)) ?></span><?php endif; ?></div><div class="user-details"><h4 class="user-name"><?= htmlspecialchars($sidebar_name) ?></h4><span class="user-role">GUIDANCE COUNSELLOR</span></div></div>
        <nav class="sidebar-nav"><span class="nav-section-title">MAIN NAVIGATION</span><ul class="nav-list">
            <li><a href="guidance-counsellor-homepage.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg><span>Home</span></a></li>
            <li><a href="students.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg><span>Students</span></a></li>
            <li><a href="reports.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg><span>Reports</span></a></li>
            <li><a href="appointments.php" class="nav-item active"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Appointments</span></a></li>
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
                <span class="header-badge">APPOINTMENTS</span>
                <h1 class="header-title">Appointments</h1>
                <p class="header-subtitle">Review scheduled appointments and connect with students.</p>
            </div>
        </header>
        <section class="page-section">
            <div class="search-card searchcontainer">
    <div class="searchbar">
        <form method="POST">
    <input type="text" name="search1" class="search1" placeholder="Search">
    <button type="submit" class="submit1">Search</button>
</form>
</div>
<br>
<br>
<div class="appointmentcontainer">
<?php
$limit = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$startingrecord = ($page - 1) * $limit;
    if(isset($_POST['search1'])){
        $search1 = $_POST['search1'];
    $searchappointments = "SELECT * FROM appointments INNER JOIN users ON users.user_id = appointments.user_id WHERE name LIKE '%$search1%' LIMIT $limit OFFSET $startingrecord";
    $appointments = mysqli_query($conn, $searchappointments);
    while($row = mysqli_fetch_assoc($appointments)){
        $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
        ?>
    <div class="appointmentprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer2">
      <p class="p">Name: <?= $row['name'] ?></p>
      <p class="p">Age: <?= $row['age'] ?></p>
      <p class="p">Sex: <?= $row['sex'] ?></p>
      <p class="p">Concern: <?= $row['concern'] ?></p>
      <div class="reportcontainer3">
      <p class="p2">Reason: <?= $row['reason'] ?></p>
    </div>
      <p class="p">Appointment Date: <?= $row['appointmentdate'] ?></p>
      <p class="p">Date Created: <?= $row['date_created'] ?></p>
    </div>
      <a class="chatreport" name="edit" href="report-chat.php?user_id=<?= (int)$row['user_id'] ?>">Chat</a>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM appointments WHERE name LIKE '%$search1%'";
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
           $defaultappointments = "SELECT * FROM appointments INNER JOIN users ON users.user_id = appointments.user_id LIMIT $limit OFFSET $startingrecord";
           $appointments2 = mysqli_query($conn, $defaultappointments);
           while($row = mysqli_fetch_assoc($appointments2)){
            $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
            ?>
            <div class="appointmentprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer2">
        <p class="p">Name: <?= $row['name'] ?></p>
      <p class="p">Age: <?= $row['age'] ?></p>
      <p class="p">Sex: <?= $row['sex'] ?></p>
      <p class="p">Concern: <?= $row['concern'] ?></p>
      <div class="reportcontainer3">
      <p class="p2">Reason: <?= $row['reason'] ?></p>
    </div>
      <p class="p">Appointment Date: <?= $row['appointmentdate'] ?></p>
      <p class="p">Date Created: <?= $row['date_created'] ?></p>
           </div>
           <a class="chatreport" name="edit" href="report-chat.php?user_id=<?= (int)$row['user_id'] ?>">Chat</a>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM appointments";
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
        </section>
    </main>
    <script src="../assets/js/animations.js" defer></script>
</body>
    </html>
