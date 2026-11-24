<?php
session_start();
include "mental-health-db.php";

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

$user_id = (int) $_SESSION['user_id'];
$guidance_id = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;
if ($guidance_id <= 0) {
    header("Location: book-an-appointment.php");
    exit();
}

$guidanceCheck = $conn->prepare("SELECT user_id, name FROM users WHERE user_id = ? AND user_type = 'Guidancecounsellor' AND user_status = 'Approved' LIMIT 1");
if (!$guidanceCheck) {
    header("Location: book-an-appointment.php");
    exit();
}
$guidanceCheck->bind_param("i", $guidance_id);
$guidanceCheck->execute();
$guidanceResult = $guidanceCheck->get_result();
$guidanceRow = $guidanceResult ? $guidanceResult->fetch_assoc() : null;
$guidanceCheck->close();
if (!$guidanceRow) {
    header("Location: book-an-appointment.php");
    exit();
}

$manager_name = (string) ($_SESSION['name'] ?? 'User');
$has_profilepic = false;
$profilepic_src = '';
$pending_count = 0;
$user_id2 = $user_id;
$sidebar_role = (string) ($_SESSION['user_type'] ?? 'User');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - H.O.P.E.</title>
<link rel="stylesheet" href="../assets/css/appointment.css">
</head>

<body class="dashboard-page">
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
                    <a href="chat.php" class="nav-item"><span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></span><span>Chat Support</span></a>
                </li>
                <li>
                    <a href="book-an-appointment.php" class="nav-item active"><span class="nav-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg></span><span>Appointments</span></a>
                </li>
            </ul>
            <span class="nav-section-title">ACCOUNT</span>
            <ul class="nav-list">
                <li>
                    <a href="user-update.php?user_id=<?= (int) $user_id2 ?>" class="nav-item">
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
                <h1 class="header-title">Book Appointment</h1>
                <p class="header-subtitle">Submit your concern and preferred schedule.</p>
            </div>
        </header>
        <div class="card booking-form-card">
            <div class="card-header">
                <span class="card-tag">Appointment Booking</span>
                <h1 class="card-title">Book an appointment with <?= htmlspecialchars((string) ($guidanceRow['name'] ?? 'Guidance Counsellor'), ENT_QUOTES) ?></h1>
            </div>
            <form method="POST" action="appointment-notify.php" class="update-form">
                <input type="hidden" name="guidance_id" value="<?= (int) $guidance_id ?>">
                <input type="hidden" name="user_id2" value="<?= (int) $user_id ?>">
                <div class="form-group">
                    <label for="concern" class="form-label">Concern</label>
                    <select name="concern" id="concern" class="form-input" required>
                        <option value="Academic Stress">Academic Stress</option>
                        <option value="Personal Concern">Personal Concern</option>
                        <option value="Bullying">Bullying</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="reason" class="form-label">Reason</label>
                    <textarea name="reason" id="reason" class="form-input" rows="5" required></textarea>
                </div>
                <div class="form-group">
                    <label for="appointmentdate" class="form-label">Appointment Date</label>
                    <input type="datetime-local" name="appointmentdate" id="appointmentdate" class="form-input" required>
                </div>
                <div class="form-actions">
                    <button type="submit" name="submit9" class="btn-primary">Submit Appointment</button>
                    <a href="book-an-appointment.php" class="btn-secondary">Back</a>
                </div>
            </form>
        </div>
    </main>
    <script src="../assets/js/animations.js" defer></script>
</body>

</html>