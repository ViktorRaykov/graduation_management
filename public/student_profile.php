<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'student') {
    header("location: login.php");
    exit;
}

require_once '../config/config.php';

$username = $_SESSION["username"];
$sql = "SELECT u.username, u.first_name, u.last_name, s.degree, s.graduation_year 
        FROM students s 
        JOIN users u ON s.user_id = u.id 
        WHERE u.username = ?";
        
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($username, $first_name, $last_name, $degree, $graduation_year);
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
    <title>Личен профил на студента</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="wrapper">
        <h2>Личен профил на студента</h2>
        <p><b>Потребителско име:</b> <?php echo htmlspecialchars($username); ?></p>
        <p><b>Име:</b> <?php echo htmlspecialchars($first_name); ?></p>
        <p><b>Фамилия:</b> <?php echo htmlspecialchars($last_name); ?></p>
        <p><b>Степен:</b> <?php echo htmlspecialchars($degree); ?></p>
        <p><b>Година на дипломиране:</b> <?php echo htmlspecialchars($graduation_year); ?></p>
        <div class="profile-picture">
            <img src="path/to/profile/picture.png" alt="Профилна снимка">
        </div>
        <a href="student_index.php">Назад към началната страница</a>
    </div>
</body>
</html>
