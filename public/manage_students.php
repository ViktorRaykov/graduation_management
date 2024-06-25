<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit;
}

chdir(__DIR__);
require_once '../config/config.php';

$sql = "SELECT s.id, u.username, s.degree, s.graduation_year FROM students s JOIN users u ON s.user_id = u.id";
$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Управление на дипломирани студенти</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="wrapper">
        <h2>Управление на дипломирани студенти</h2>
        <a href="admin_index.php" class="btn btn-primary">Обратно към началната страница</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Име</th>
                    <th>Степен</th>
                    <th>Година на дипломиране</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr class="<?= $row['degree'] ?>">
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['degree']) ?></td>
                        <td><?= htmlspecialchars($row['graduation_year']) ?></td>
                        <td class="actions">
                            <a href="edit_student.php?id=<?= $row['id'] ?>" class="btn btn-warning">Редактиране</a>
                            <a href="delete_student.php?id=<?= $row['id'] ?>" class="btn btn-danger">Изтриване</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <p></p>
        <a href="add_student.php" class="btn btn-success">Добавяне на студент</a>
        <a href="import.php" class="btn btn-primary">Импортиране на данни</a>
        <a href="export.php" class="btn btn-primary">Експортиране на данни</a>
    </div>
</body>
</html>

<?php
$mysqli->close();
?>
