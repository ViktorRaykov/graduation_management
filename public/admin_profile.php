<?php

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once '../config/config.php';

$sql = "SELECT username, first_name, last_name, email, role FROM users WHERE id = ?";
if($stmt = $mysqli->prepare($sql)){
    $stmt->bind_param("i", $param_id);
    $param_id = $_SESSION["id"];
    
    if($stmt->execute()){
        $stmt->store_result();
        if($stmt->num_rows == 1){
            $stmt->bind_result($username, $first_name, $last_name, $email, $role);
            $stmt->fetch();
        }
    }
    $stmt->close();
}
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Личен профил</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="wrapper">
        <h2>Личен профил</h2>
        <div class="profile-info">
            <p><strong>Потребителско име:</strong> <?= htmlspecialchars($_SESSION["username"]) ?></p>
            <p><strong>Име:</strong> <?= htmlspecialchars($first_name) ?></p>
            <p><strong>Фамилия:</strong> <?= htmlspecialchars($last_name) ?></p>
            <p><strong>Имейл:</strong> <?= htmlspecialchars($email) ?></p>
            <p><strong>Роля:</strong> <?= htmlspecialchars($role) ?></p>
        </div>
        <div class="profile-photo-placeholder">
            <p>Профилна снимка</p>
        </div>
        <a href="admin_index.php" class="btn">Назад към началната страница</a>
    </div>
</body>
</html>

