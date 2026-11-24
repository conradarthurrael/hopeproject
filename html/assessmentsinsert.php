<?php
include "mental-health-db.php";
session_start();
    $question1 = $_POST['question1'];
    $question2 = $_POST['question2'];
    $question3 = $_POST['question3'];
    $question4 = $_POST['question4'];
    $question5 = $_POST['question5'];
    $question6 = $_POST['question6'];
    $question7 = $_POST['question7'];
    $question8 = $_POST['question8'];
    $question9 = $_POST['question9'];
    $question10 = $_POST['question10'];
    $questionssql = "INSERT INTO questionnaire(question1, question2, question3, question4, question5, question6, question7, question8, question9, question10)
    VALUES('$question1', '$question2', '$question3', '$question4', '$question5', '$question6', '$question7', '$question8', '$question9', '$question10');";
    mysqli_query($conn, $questionssql);
    header("Location: guidance-counsellor-homepage.php");
    exit();
?>