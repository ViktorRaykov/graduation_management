<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'student') {
    header("location: login.php");
    exit;
}

require_once '../config/config.php';

// Получаване на годината на дипломиране на логнатия студент
$user_id = $_SESSION["id"];
$sql = "SELECT graduation_year FROM students WHERE user_id = ?";
if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($graduation_year);
    $stmt->fetch();
    $stmt->close();
}

// Извличане на всички студенти, завършили в същата година
$sql = "SELECT u.username, s.degree, s.graduation_year 
        FROM users u 
        JOIN students s ON u.id = s.user_id 
        WHERE s.graduation_year = ? AND u.role = 'student'";

if ($stmt = $mysqli->prepare($sql)) {
    $stmt->bind_param("i", $graduation_year);
    $stmt->execute();
    $result = $stmt->get_result();
    $students = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Списък на студенти завършили в същата година</title>
    <link rel="stylesheet" href="css/students_same_year.css">
</head>
<body>
    <div class="wrapper">
        <h2>Списък на студенти завършили в същата година</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Потребителско име</th>
                    <th>Степен</th>
                    <th>Година на дипломиране</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($students as $student): ?>
                    <tr class="<?php echo strtolower($student['degree']); ?>">
                        <td><?php echo htmlspecialchars($student['username']); ?></td>
                        <td><?php echo htmlspecialchars($student['degree']); ?></td>
                        <td><?php echo htmlspecialchars($student['graduation_year']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="student_index.php" class="btn btn-secondary">Назад</a>
    </div>
</body>
</html>
