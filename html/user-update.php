<?php
include "mental-health-db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

$user_id2 = (int) $_SESSION['user_id'];
$portalRoleKey = strtolower(str_replace([' ', '-', '_'], '', (string)($_SESSION['user_type'] ?? '')));
$portalHomePage = [
    'teacher' => 'teacher-homepage.php',
    'student' => 'student-homepage.php',
    'member' => 'student-homepage.php',
    'manager' => 'manager-homepage.php',
    'admin' => 'manager-homepage.php',
    'guidancecounsellor' => 'guidance-counsellor-homepage.php',
    'guidancecounselor' => 'guidance-counsellor-homepage.php'
][$portalRoleKey] ?? '../index.php';
$isManagerViewer = in_array($portalRoleKey, ['manager', 'admin'], true);
$isTeacherViewer = ($portalRoleKey === 'teacher');
$isStudentViewer = in_array($portalRoleKey, ['student', 'member'], true);
$isCounsellorViewer = in_array($portalRoleKey, ['guidancecounsellor', 'guidancecounselor'], true);

$target_user_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : $user_id2;
if (!$isManagerViewer && $target_user_id !== $user_id2) {
    header("Location: " . $portalHomePage);
    exit();
}

$profile = "SELECT name, email, profilepic, user_type FROM users WHERE user_id='$user_id2'";
$profile2 = mysqli_query($conn, $profile);
$user_data = ($profile2 && mysqli_num_rows($profile2) > 0) ? mysqli_fetch_assoc($profile2) : [];

$viewer_name = !empty($user_data['name']) ? $user_data['name'] : (!empty($_SESSION['name']) ? $_SESSION['name'] : 'User');
$viewer_role_label = !empty($user_data['user_type']) ? $user_data['user_type'] : (!empty($_SESSION['user_type']) ? $_SESSION['user_type'] : 'User');
if ($isCounsellorViewer) {
    $viewer_role_label = 'GUIDANCE COUNSELLOR';
} elseif ($isTeacherViewer) {
    $viewer_role_label = 'Teacher';
} elseif ($isStudentViewer) {
    $viewer_role_label = 'Student';
} elseif ($isManagerViewer) {
    $viewer_role_label = 'Manager';
}

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

$pending_count = 0;
if ($isManagerViewer) {
    $pending_q = mysqli_query($conn, "SELECT COUNT(*) as cnt FROM users WHERE user_status = 'Pending' OR user_type = 'PENDING'");
    $pending_count = $pending_q ? (int)(mysqli_fetch_assoc($pending_q)['cnt'] ?? 0) : 0;
}

$edit_user_id = $target_user_id;

if (isset($_POST['updatebutton'])) {
    $requested_user_id = isset($_POST['user_id']) ? (int) $_POST['user_id'] : (isset($_GET['user_id']) ? (int) $_GET['user_id'] : $user_id2);
    if (!$isManagerViewer && $requested_user_id !== $user_id2) {
        header("Location: " . $portalHomePage);
        exit();
    }
    $edit_user_id = $requested_user_id;

    $name       = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
    $email      = mysqli_real_escape_string($conn, $_POST['email'] ?? '');
    $age        = mysqli_real_escape_string($conn, $_POST['age'] ?? '');
    $sex        = mysqli_real_escape_string($conn, $_POST['sex'] ?? '');
    $address    = mysqli_real_escape_string($conn, $_POST['address'] ?? '');
    $dob        = mysqli_real_escape_string($conn, $_POST['dob'] ?? '');
    $contactno  = mysqli_real_escape_string($conn, $_POST['contactno'] ?? '');
    $usertype   = mysqli_real_escape_string($conn, $_POST['usertype'] ?? '');
    $user_status = "Approved";

    if (!empty($_FILES['profilepic']['tmp_name'])) {
        $profilepic = addslashes(file_get_contents($_FILES['profilepic']['tmp_name']));
        $updatesql = "UPDATE users SET name='$name', email='$email', age='$age', sex='$sex', address='$address', date_of_birth='$dob', contactno='$contactno', user_type='$usertype', user_status='$user_status', profilepic='$profilepic' WHERE user_id='$edit_user_id'";
    } else {
        $updatesql = "UPDATE users SET name='$name', email='$email', age='$age', sex='$sex', address='$address', date_of_birth='$dob', contactno='$contactno', user_type='$usertype', user_status='$user_status' WHERE user_id='$edit_user_id'";
    }

    mysqli_query($conn, $updatesql);
    header("Location: " . $portalHomePage);
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM users WHERE user_id='$edit_user_id'");
$row = mysqli_fetch_assoc($result);

$edit_pic_src = "";
if (!empty($row['profilepic'])) {
    $fi = finfo_open();
    $em = @finfo_buffer($fi, $row['profilepic'], FILEINFO_MIME_TYPE);
    if ($em && strpos($em, 'image/') === 0) {
        $edit_pic_src = 'data: ' . $em . ';base64,' . base64_encode($row['profilepic']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - H.O.P.E.</title>
<link rel="stylesheet" href="../assets/css/user-update.css">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="<?= htmlspecialchars($portalHomePage, ENT_QUOTES) ?>" class="brand-link">
                <img src="../images/hope-logo.png" alt="H.O.P.E. Logo" class="sidebar-logo">
            </a>
        </div>

        <div class="sidebar-user">
            <div class="user-avatar-sm">
                <?php if (!empty($has_profilepic)): ?>
                    <img src="<?= htmlspecialchars($profilepic_src, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($viewer_name) ?>">
                <?php else: ?>
                    <span class="avatar-initials"><?= strtoupper(substr($viewer_name, 0, 1)) ?></span>
                <?php endif; ?>
            </div>
            <div class="user-details">
                <h4 class="user-name"><?= htmlspecialchars($viewer_name) ?></h4>
                <span class="user-role"><?= htmlspecialchars($viewer_role_label) ?></span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <span class="nav-section-title">MAIN NAVIGATION</span>
            <ul class="nav-list">
                <?php if ($isTeacherViewer): ?>
                <li><a href="teacher-homepage.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg><span>Home</span></a></li>
                <li><a href="students.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg><span>Students</span></a></li>
                <li><a href="reports.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg><span>Reports</span></a></li>
                <li><a href="appointments.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Appointments</span></a></li>
                <li><a href="chat.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg><span>Chat</span></a></li>
                <?php elseif ($isStudentViewer): ?>
                <li><a href="student-homepage.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg><span>Dashboard</span></a></li>
                <li><a href="chat.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg><span>Chat Support</span></a></li>
                <li><a href="book-an-appointment.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Appointments</span></a></li>
                <?php elseif ($isCounsellorViewer): ?>
                <li><a href="guidance-counsellor-homepage.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg><span>Home</span></a></li>
                <li><a href="students.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg><span>Students</span></a></li>
                <li><a href="reports.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg><span>Reports</span></a></li>
                <li><a href="appointments.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Appointments</span></a></li>
                <li><a href="chat.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg><span>Chat</span></a></li>
                <?php else: ?>
                <li><a href="manager-homepage.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg><span>Dashboard</span></a></li>
                <li><a href="users.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg><span>Users &amp; Approvals</span><?php if (!empty($pending_count)): ?><span class="nav-counter"><?= $pending_count ?></span><?php endif; ?></a></li>
                <li><a href="guidance-counsellors.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg><span>Guidance Counsellors</span></a></li>
                <li><a href="students.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg><span>Students</span></a></li>
                <li><a href="teachers.php" class="nav-item"><svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg><span>Teachers</span></a></li>
                <?php endif; ?>
            </ul>

            <span class="nav-section-title">ACCOUNT</span>
            <ul class="nav-list">
                <li>
                    <a href="user-update.php?user_id=<?= $user_id2 ?>" class="nav-item active">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        <span>Edit Profile</span>
                    </a>
                </li>
                <li>
                    <a href="mental-health-logout.php" class="nav-item nav-logout">
                        <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        <span>Log Out</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <main class="main-content">
        <header class="dashboard-header">
            <div class="header-titles">
                <span class="header-badge">ACCOUNT</span>
                <h1 class="header-title">Edit Profile</h1>
                <p class="header-subtitle">Update user account details, role assignment, and profile picture.</p>
            </div>
            <div class="header-actions"><!-- Back to Dashboard Button -->

            </div>
        </header>

        <section class="page-section">
            <div class="card update-card">
                <div class="card-header">
                    <span class="card-tag">USER RECORD</span>
                    <h2 class="card-title">Profile Information</h2>
                </div>

                <div class="update-card-body">
                    
                    <div class="update-avatar-col">
                        <?php if ($edit_pic_src): ?>
                            <img src="<?= $edit_pic_src ?>" alt="Profile" class="update-avatar-img">
                        <?php else: ?>
                            <div class="avatar-lg-placeholder">
                                <span><?= strtoupper(substr($row['name'] ?? 'U', 0, 1)) ?></span>
                            </div>
                        <?php endif; ?>
                        <p class="update-avatar-name"><?= htmlspecialchars($row['name'] ?? '') ?></p>
                        <span class="meta-pill"><?= htmlspecialchars($row['user_type'] ?? '') ?></span>
                    </div>

                    
                    <div class="update-form-col">
                        <form method="POST" enctype="multipart/form-data" class="update-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-input" placeholder="Enter full name" value="<?= htmlspecialchars($row['name'] ?? '') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-input" placeholder="Enter email" value="<?= htmlspecialchars($row['email'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Age</label>
                                    <input type="number" name="age" class="form-input" placeholder="Age" value="<?= htmlspecialchars($row['age'] ?? '') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Sex</label>
                                    <select name="sex" class="form-input" required>
                                        <option value="Male" <?= ($row['sex'] ?? '') === 'Male' ? 'selected' : '' ?>>Male</option>
                                        <option value="Female" <?= ($row['sex'] ?? '') === 'Female' ? 'selected' : '' ?>>Female</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Date of Birth</label>
                                    <input type="text" name="dob" class="form-input" placeholder="yyyy/mm/dd" value="<?= htmlspecialchars($row['date_of_birth'] ?? '') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Contact No.</label>
                                    <input type="text" name="contactno" class="form-input" placeholder="Contact number" value="<?= htmlspecialchars($row['contactno'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="form-group form-group-full">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-input" placeholder="Enter address" value="<?= htmlspecialchars($row['address'] ?? '') ?>" required>
                            </div>

                            <div class="form-group form-group-full">
                                <label class="form-label">User Role</label>
                                <select name="usertype" class="form-input" required>
                                    <option value="Student"              <?= ($row['user_type'] ?? '') === 'Student'              ? 'selected' : '' ?>>Student</option>
                                    <option value="Guidancecounsellor"   <?= ($row['user_type'] ?? '') === 'Guidancecounsellor'   ? 'selected' : '' ?>>Guidance Counsellor</option>
                                    <option value="Teacher"              <?= ($row['user_type'] ?? '') === 'Teacher'              ? 'selected' : '' ?>>Teacher</option>
                                    <option value="Manager"              <?= ($row['user_type'] ?? '') === 'Manager'              ? 'selected' : '' ?>>Manager</option>
                                </select>
                            </div>

                            <div class="form-group form-group-full">
                                <label class="form-label">Profile Picture</label>
                                <input type="file" name="profilepic" class="form-file-input" accept="image/*">
                                <span class="form-hint">Leave blank to keep the current photo.</span>
                            </div>

                            <div class="form-actions"><!-- Save Profile Button -->

                                    <button name='updatebutton'><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg></button>
                                    Save Changes
                                </button><!-- Cancel Button -->

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </main>
<script src="../assets/js/animations.js" defer></script>
</body>
</html>