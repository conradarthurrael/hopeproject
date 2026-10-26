<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment</title>
    <link rel="stylesheet" href="../css/appointment.css?v=58">
</head>
<body>
    <?php
    include "mhdb.php";
    session_start();
    $user_id = $_SESSION['user_id'];
    $guidance_id = $_GET['user_id'];
    ?>
    <nav class="navbar">
      <div class="nav-container">
        <img src="../images/hope-logo.png" alt="Logo" class="logo" />
        <div class="nav-container2">
        <ul>
        <li class="chat"><a href="chat2.php" class="chat2">Chat</a></li>
        <li><a href="appointment.php">Book an appointment</a></li>
        <li><a href="bookanappointment.php">Back</a></li>
          <li><a href="mhlogout.php">Log Out</a></li>
        </ul>
</div>
      </div>
    </nav>
<div class="outerformcontainer">
    <div class="formcontainer">
    <form method="POST" action="appointmentnotify.php">
<h2 class="bd">Guidance Appointment</h2>
<h3>Confirmation details</h3>
<input type="text" name="guidance_id" class="hideinput" value=<?=$guidance_id?>>
<input type="text" name="user_id2" class="hideinput" value=<?=$user_id?>>
<label>Concern</label><br>
<select name="concern" id="concern">
  <option value="Academic Stress">Academic Stress</option>
  <option value="Personal Concern">Personal Concern</option>
  <option value="Bullying">Bullying</option>
</select><br><br>
<label>Reason</label><br>
<textarea name="reason" id="reason" required></textarea><br><br>
<label>Appointment Date</label>
<input type="datetime-local" name="appointmentdate" id="appointmentdate"><br><br>
<input type="submit" name="submit9" class="transbutton">
</form>
</div>
</div>
</body>
</html>