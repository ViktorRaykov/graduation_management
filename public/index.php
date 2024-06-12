<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$username = htmlspecialchars($_SESSION["username"]);
$role = htmlspecialchars($_SESSION["role"]);
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
        <?php if($role === 'admin'): ?>
            <a href="manage_students.php" class="btn btn-primary">Управление на студенти</a>
        <?php endif; ?>
        <a href="admin_profile.php" class="btn btn-primary">Личен профил</a>
        <a href="logout.php" class="btn btn-danger">Изход</a>
    </div>
</body>
</html>
