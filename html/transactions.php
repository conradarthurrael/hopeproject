<?php
include "mental-health-db.php";
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}
$portalRoleKey = strtolower(str_replace([' ', '-', '_'], '', (string)($_SESSION['user_type'] ?? '')));
$portalHomePage = [
    'teacher' => 'teacher-homepage.php',
    'student' => 'student-homepage.php',
    'manager' => 'manager-homepage.php',
    'admin' => 'manager-homepage.php',
    'guidancecounsellor' => 'guidance-counsellor-homepage.php',
    'guidancecounselor' => 'guidance-counsellor-homepage.php'
][$portalRoleKey] ?? '../index.php';
if (!in_array($portalRoleKey, ['manager', 'admin'], true)) {
    header("Location: " . $portalHomePage);
    exit();
}
$user_id3 = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rehab Management System</title>
<link rel="stylesheet" href="../assets/css/transactions.css">
</head>
<body>
<?php
$manager_name = $manager_name ?? (string)($_SESSION['name'] ?? 'User');
$has_profilepic = $has_profilepic ?? false;
$profilepic_src = $profilepic_src ?? '';
$pending_count = $pending_count ?? 0;
$user_id2 = $user_id2 ?? ($_SESSION['user_id'] ?? 0);
$sidebar_role = (string)($_SESSION['user_type'] ?? 'User');
?><aside class="sidebar">
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
    <div class="searchbar">
        <form method="POST">
    <input type="text" name="search1" class="search1" placeholder="Search">
    <button type="submit" class="submit1">Search</button>
</form>
</div>
<div class="transactioncontainer">
    <?php
$limit = 9;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$startingrecord = ($page - 1) * $limit;
    if(isset($_POST['search1'])){
        $search1 = $_POST['search1'];
    $searchtransactions = "SELECT * FROM transactions INNER JOIN users ON transactions.doctor_id = users.user_id WHERE transactions.user_id='$user_id3' AND name LIKE '%$search1%' LIMIT $limit OFFSET $startingrecord";
    $transactions = mysqli_query($conn, $searchtransactions);
    while($row = mysqli_fetch_assoc($transactions)){
        $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
        ?>
    <div class="transactionprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer2">
      <p class="p">Doctor: <?= $row['name'] ?></p>
      <p class="p">Transaction ID: <?= $row['transaction_id'] ?></p>
      <p class="p">Appointment Date: <?= $row['appointmentdate'] ?></p>
      <p class="p">Transaction Date: <?= $row['transaction_date'] ?></p>
    </div>
      <a class="edit" name = "edit" href="user-update.php?user_id=<?=$row['user_id']?>"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
      <a class="delete" href="user-delete.php?user_id=<?=$row['user_id']?>" onclick
        ="return confirm('Delete this user?')"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM transactions WHERE user_id='$user_id3'";
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
           $defaulttransactions = "SELECT * FROM transactions INNER JOIN users ON users.user_id = transactions.doctor_id WHERE transactions.user_id='$user_id3' LIMIT $limit OFFSET $startingrecord";
           $transactions2 = mysqli_query($conn, $defaulttransactions);
           while($row = mysqli_fetch_assoc($transactions2)){
            $mime = finfo_buffer(finfo_open(), $row['profilepic'], FILEINFO_MIME_TYPE);
            ?>
            <div class="transactionprofile">
        <td><?php echo '<img width="200px" height="200px" class="profilepic3" src="data: '.$mime.';base64,'.base64_encode($row['profilepic']).'">';?></td>
        <div class="infocontainer2">
      <p class="p">Doctor: <?= $row['name'] ?></p>
      <p class="p">Transaction ID: <?= $row['transaction_id'] ?></p>
      <p class="p">Appointment Date: <?= $row['appointmentdate'] ?></p>
      <p class="p">Transaction Date: <?= $row['transaction_date'] ?></p>
           </div>
      <a class="edit" name = "edit" href="user-update.php?user_id=<?=$row['user_id']?>"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></a>
      <a class="delete" href="user-delete.php?user_id=<?=$row['user_id']?>" onclick
        ="return confirm('Delete this user?')"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg></a></div>
        <div class="pages">
        <?php $count_sql = "SELECT COUNT(*) AS total FROM transactions WHERE user_id='$user_id3'";
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
</div>
<script src="../assets/js/animations.js" defer></script>
</body>
</html>
