<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'student') {
    header("location: login.php");
    exit;
}

$username = htmlspecialchars($_SESSION["username"]);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Добре дошли</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<div class="header">
        <h1>Система за управление на дипломирани студенти</h1>
    </div>
    <div class="welcome-banner">
        <h2>Здравей, <?php echo $username; ?>. Добре дошли в системата за управление на дипломирани студенти.</h2>
    </div>
    <div class="buttons">
        <a href="student_profile.php" class="btn btn-primary">Личен профил</a>
        <a href="students_same_year.php" class="btn btn-primary">Списък на студенти завършили в същата година</a>
        <a href="logout.php" class="btn btn-danger">Изход</a>
    </div>
</body>
</html>
