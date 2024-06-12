<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добре дошли</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="page-header">
        <h1>Здравей, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Добре дошли в системата за управление на дипломирани студенти.</h1>
    </div>
    <p>
        <?php if($_SESSION["role"] == "admin"): ?>
            <a href="manage_students.php" class="btn btn-primary">Управление на студенти</a>
        <?php else: ?>
            <a href="student_profile.php" class="btn btn-primary">Личен профил</a>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-danger">Изход</a>
    </p>
</body>
</html>
