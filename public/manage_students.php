<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: login.php");
    exit;
}

require_once '../config/config.php';

$query = "SELECT * FROM students";
$result = $mysqli->query($query);

if(!$result){
    echo "Грешка при изпълнение на заявката: " . $mysqli->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Управление на студенти</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="wrapper">
        <h2>Управление на дипломирани студенти</h2>
        <p><a href="admin_index.php" class="btn btn-primary">Обратно към началната страница</a></p>
        <?php if($result->num_rows > 0): ?>
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
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['degree']; ?></td>
                            <td><?php echo $row['graduation_year']; ?></td>
                            <td>
                                <a href="edit_student.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">Редактиране</a>
                                <a href="delete_student.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Изтриване</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Няма намерени студенти.</p>
        <?php endif; ?>
        <a href="add_student.php" class="btn btn-success">Добавяне на студент</a>
        <a href="import.php" class="btn btn-info">Импортиране на данни</a>
        <a href="export.php" class="btn btn-secondary">Експортиране на данни</a>
    </div>
</body>
</html>

<?php
$mysqli->close();
?>
