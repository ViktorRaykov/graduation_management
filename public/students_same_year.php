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
        <h2>Списък на студенти завършили в същата година</h2>
        <table id="studentsSameYearTable" class="table">
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
    <script>
        $(document).ready(function() {
            $('#studentsSameYearTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/Bulgarian.json"
                },
                "columnDefs": [
                    { "orderable": true, "targets": [1, 2] } // Позволява сортиране за колоните "Степен" и "Година на дипломиране"
                ]
            });
        });
    </script>
</body>
</html>
