<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: login.php");
    exit;
}

require_once '../config/config.php';

$query = "SELECT * FROM students";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    $delimiter = ",";
    $filename = "students_" . date('Y-m-d') . ".csv";

    $f = fopen('php://memory', 'w');

    $fields = array('ID', 'Име', 'Степен', 'Година на дипломиране');
    fputcsv($f, $fields, $delimiter);

    while($row = $result->fetch_assoc()) {
        $lineData = array($row['id'], $row['name'], $row['degree'], $row['graduation_year']);
        fputcsv($f, $lineData, $delimiter);
    }

    fseek($f, 0);

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '";');

    fpassthru($f);
    exit;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Експортиране на данни</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="wrapper">
        <h2>Експортиране на данни</h2>
        <p>Няма намерени данни за експортиране.</p>
        <a href="manage_students.php" class="btn btn-secondary">Назад</a>
    </div>
</body>
</html>
