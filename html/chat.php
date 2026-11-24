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
if (isset($_SESSION['user_id'])) {
    $user_id5 = (int)$_SESSION['user_id'];
    $userquery = "UPDATE users SET last_activity = NOW() WHERE user_id='$user_id5'";
    mysqli_query($conn, $userquery);
}
$current_role = strtolower(str_replace([' ', '-', '_'], '', (string) ($_SESSION['user_type'] ?? '')));
$contact_filter = $current_role === 'student' ? " AND user_type='Guidancecounsellor'" : '';
$isCounsellor = in_array($current_role, ['guidancecounsellor','guidancecounselor'], true);
$isTeacher = ($current_role === 'teacher');
$isStudent = in_array($current_role, ['student','member'], true);
$user_id2 = (int)($_SESSION['user_id'] ?? 0);

$profileRes = mysqli_query($conn, "SELECT name, profilepic, user_type FROM users WHERE user_id='$user_id2' LIMIT 1");
$profileRow = ($profileRes && mysqli_num_rows($profileRes) > 0) ? mysqli_fetch_assoc($profileRes) : [];
$sidebar_name = !empty($profileRow['name']) ? $profileRow['name'] : (!empty($_SESSION['name']) ? $_SESSION['name'] : 'User');
$sidebar_role = $isCounsellor ? 'GUIDANCE COUNSELLOR' : (!empty($profileRow['user_type']) ? $profileRow['user_type'] : (!empty($_SESSION['user_type']) ? $_SESSION['user_type'] : 'User'));
$has_profilepic = false;
$profilepic_src = '';
if (!empty($profileRow['profilepic'])) {
    $finfo = finfo_open();
    $mime = $finfo ? @finfo_buffer($finfo, $profileRow['profilepic'], FILEINFO_MIME_TYPE) : '';
    if ($mime && strpos($mime, 'image/') === 0) {
        $has_profilepic = true;
        $profilepic_src = 'data: ' . $mime . ';base64,' . base64_encode($profileRow['profilepic']);
    }
    if ($finfo) { finfo_close($finfo); }
}
$manager_name = $sidebar_name;
$pending_count = 0;


?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat - H.O.P.E.</title>
<link rel="stylesheet" href="../assets/css/chat.css">
</head>

<body class="dashboard-page">
    <?php if ($isTeacher): ?>
    <aside class="sidebar">
        <div class="sidebar-brand"><a href="teacher-homepage.php" class="brand-link"><img src="../images/hope-logo.png" alt="H.O.P.E. Logo" class="sidebar-logo"></a></div>
        <div class="sidebar-user"><div class="user-avatar-sm"><?php if (!empty($has_profilepic)): ?><img src="<?= htmlspecialchars($profilepic_src, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($sidebar_name) ?>"><?php else: ?><span class="avatar-initials"><?= strtoupper(substr($sidebar_name, 0, 1)) ?></span><?php endif; ?></div><div class="user-details"><h4 class="user-name"><?= htmlspecialchars($sidebar_name) ?></h4><span class="user-role">Teacher</span></div></div>
        <nav class="sidebar-nav"><span class="nav-section-title">MAIN NAVIGATION</span><ul class="nav-list">
            <li><a href="teacher-homepage.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg><span>Home</span></a></li>
            <li><a href="students.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg><span>Students</span></a></li>
            <li><a href="reports.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg><span>Reports</span></a></li>
            <li><a href="appointments.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Appointments</span></a></li>
            <li><a href="chat.php" class="nav-item active"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg><span>Chat</span></a></li>
        </ul><span class="nav-section-title">ACCOUNT</span><ul class="nav-list">
            <li><a href="user-update.php?user_id=<?= (int)$user_id2 ?>" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg><span>Edit Profile</span></a></li>
            <li><a href="mental-health-logout.php" class="nav-item nav-logout"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg><span>Log Out</span></a></li>
        </ul></nav>
    </aside>
    <?php elseif ($isCounsellor): ?>
    <aside class="sidebar">
        <div class="sidebar-brand"><a href="guidance-counsellor-homepage.php" class="brand-link"><img src="../images/hope-logo.png" alt="H.O.P.E. Logo" class="sidebar-logo"></a></div>
        <div class="sidebar-user"><div class="user-avatar-sm"><?php if (!empty($has_profilepic)): ?><img src="<?= htmlspecialchars($profilepic_src, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($sidebar_name) ?>"><?php else: ?><span class="avatar-initials"><?= strtoupper(substr($sidebar_name, 0, 1)) ?></span><?php endif; ?></div><div class="user-details"><h4 class="user-name"><?= htmlspecialchars($sidebar_name) ?></h4><span class="user-role">GUIDANCE COUNSELLOR</span></div></div>
        <nav class="sidebar-nav"><span class="nav-section-title">MAIN NAVIGATION</span><ul class="nav-list">
            <li><a href="guidance-counsellor-homepage.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg><span>Home</span></a></li>
            <li><a href="students.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg><span>Students</span></a></li>
            <li><a href="reports.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg><span>Reports</span></a></li>
            <li><a href="appointments.php" class="nav-item"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg><span>Appointments</span></a></li>
            <li><a href="chat.php" class="nav-item active"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg><span>Chat</span></a></li>
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
                    <a href="chat.php" class="nav-item active"><svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg><span>Chat
                            Support</span></a>
                </li>
                <?php if ($current_role === 'student'): ?>
                <li>
                    <a href="book-an-appointment.php" class="nav-item"><span
                            class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span><span>Appointments</span></a>
                </li>
                <?php endif; ?>
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
    <?php endif; ?>

    <main class="main-content">
        <header class="dashboard-header">
            <div class="header-titles">
                <span class="header-badge">CHAT</span>
                <h1 class="header-title">Messages</h1>
                <p class="header-subtitle">Connect and communicate securely within your H.O.P.E. portal.</p>
            </div>
        </header>
        <div class="chat-container chat-workspace">
        <div class="chatprofile">
            <?php
            $chatprofileselection = "SELECT user_id,name,profilepic,last_activity FROM users WHERE user_id!='$user_id5'$contact_filter";
            $chatprofileselection2 = mysqli_query($conn, $chatprofileselection);
            while ($row = mysqli_fetch_assoc($chatprofileselection2)) {
                $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
                ?>
                <div class="chatprofile2">
                    <a href="chat.php?user_id=<?= $row['user_id'] ?>"
                        class="a"><?php echo '<img width="40px" height="40px" id="profilepic2" src="data: ' . $mime . ';base64,' . base64_encode($row['profilepic']) . '">'; ?></a>

                    <?= $row['name']; ?>
                    <br>
                    <?php
                    if ($row['last_activity'] !== NULL) {
                        echo "<span stye='color:green;'>Active</span>";
                    } else {
                        echo "<span stye='color:gray;'>Offline</span>";
                    }
                    ?>
                </div>
                <?php
            }
            $receiver = $_GET['user_id'] ?? "";

            $queryunique = "SELECT * FROM messages INNER JOIN users ON messages.sender_id = users.user_id WHERE(users.user_id = '$user_id5' AND messages.receiver_id = '$receiver') OR (users.user_id = '$receiver' AND messages.receiver_id = '$user_id5') ORDER BY sent_at DESC";
            if (isset($queryunique)) {
                $_SESSION["queryunique"] = $queryunique;
            }
            ?>
        </div>
        <div class="chat-container2">
            <div id="chats">
                <?php
                $query2 = "SELECT DISTINCT receiver_id, name, profilepic, user_id FROM messages INNER JOIN users ON messages.receiver_id = users.user_id WHERE messages.message IS NOT NULL AND messages.sender_id = $user_id5";
                $result2 = mysqli_query($conn, $query2);
                while ($row = mysqli_fetch_assoc($result2)) {
                    $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
                    ?>
                    <div class="chats-container">
                        <a href="chat.php?user_id=<?= $row['user_id'] ?>"
                            class="a"><?php echo '<img width="70px" height="70px" id="profilepic2" src="data: ' . $mime . ';base64,' . base64_encode($row['profilepic']) . '">'; ?></a>
                        <div class="namecontainer">
                            <?= $row['name']; ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div id="messages" class="messages" data-selected="<?= $receiver !== '' ? '1' : '0' ?>">
                <?php if ($receiver === ''): ?>
                    <p class="chat-empty-state">Choose a person above to open a conversation.</p><?php endif; ?>
            </div>
        </div>
        <div class="messageform">
            <div id="chat-status" class="chat-status" role="status" aria-live="polite"></div>
            <form id="typemessage-container" method="POST" action="chat-message.php">
                <textarea id="typemessage" name="typemessage" placeholder="Enter a message..." maxlength="5000" required
                    <?= $receiver === '' ? 'disabled' : '' ?>></textarea>
                <button type="submit" <?= $receiver === '' ? 'disabled' : '' ?>>Send</button>
                <input type="text" name="sender_id" class="hiddeninput" value=<?= $user_id5 ?>>
                <input type="text" name="receiver_id" class="hiddeninput" value=<?= $receiver ?>>
            </form>
        </div>
        </div>
    </main>
    <script src="../assets/js/chat.js" defer></script>
    <script src="../assets/js/animations.js" defer></script>
</body>

</html>