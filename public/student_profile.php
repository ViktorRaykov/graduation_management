<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'student') {
    header("location: login.php");
    exit;
}

require_once '../config/config.php';

$username = $_SESSION["username"];
$sql = "SELECT u.username, u.first_name, u.last_name, u.email, u.role, s.degree, s.graduation_year 
        FROM students s 
        JOIN users u ON s.user_id = u.id 
        WHERE u.username = ?";
        
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($username, $first_name, $last_name, $email, $role, $degree, $graduation_year);
        $stmt->fetch();
    } else {
        echo "Грешка: Невалиден потребител.";
        exit;
    }

    $stmt->close();
} else {
    echo "Грешка: Неуспешна заявка към базата данни.";
    exit;
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
            <p><strong>Степен:</strong> <?= htmlspecialchars($degree) ?></p>
            <p><strong>Година на дипломиране:</strong> <?= htmlspecialchars($graduation_year) ?></p>
        </div>
        <div class="profile-photo-placeholder">
            <p>Профилна снимка</p>
        </div>
        <a href="student_index.php" class="btn">Назад към началната страница</a>
    </div>
</body>
</html>

