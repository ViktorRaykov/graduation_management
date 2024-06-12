<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once 'C:/xampp/htdocs/graduation_management/config/config.php';

$sql = "SELECT users.username, students.degree, students.graduation_year FROM students JOIN users ON students.user_id = users.id WHERE users.id = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $_SESSION["id"]);
    $stmt->execute();
    $stmt->bind_result($username, $degree, $graduation_year);
    $stmt->fetch();
    $stmt->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Личен профил на студента</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="profile-wrapper">
        <h2>Личен профил на студента</h2>
        <div class="profile-img">
            <img src="images/default_profile.png" alt="Профилна снимка">
            <p>Профилна снимка</p>
        </div>
        <p><b>Име:</b> <?php echo htmlspecialchars($username); ?></p>
        <p><b>Степен:</b> <?php echo htmlspecialchars($degree); ?></p>
        <p><b>Година на дипломиране:</b> <?php echo htmlspecialchars($graduation_year); ?></p>
        <a href="logout.php" class="btn btn-danger">Изход</a>
    </div>
</body>
</html>
