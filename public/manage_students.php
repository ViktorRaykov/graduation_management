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
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="js/main.js"></script>
    <style>
        /* Custom styles for DataTables */
        .dataTables_wrapper {
            margin-top: 20px;
        }
        .dataTables_length {
            float: left;
        }
        .dataTables_filter {
            float: right;
        }
        .dataTables_info {
            float: left;
            margin-top: 10px;
        }
        .dataTables_paginate {
            float: right;
            margin-top: 10px;
        }
        table.dataTable thead th {
            border-bottom: none;
        }
        table.dataTable.no-footer {
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Управление на дипломирани студенти</h2>
        <a href="admin_index.php" class="btn btn-primary">Обратно към началната страница</a>
        <table id="studentsTable" class="table">
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
