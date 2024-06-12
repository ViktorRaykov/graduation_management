<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit;
}

require_once '../config/config.php';

$query = "SELECT s.id, u.username, u.email, u.first_name, u.last_name, s.degree, s.graduation_year 
          FROM students s 
          JOIN users u ON s.user_id = u.id";
$result = $mysqli->query($query);

if ($result->num_rows > 0) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=students.csv');
    
    echo "\xEF\xBB\xBF";

    $output = fopen('php://output', 'w');
    fputcsv($output, array('ID', 'Потребителско име', 'Имейл', 'Име', 'Фамилия', 'Степен', 'Година на дипломиране'));

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }

    fclose($output);
    $mysqli->close();
    exit;
} else {
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
    <?php
}
?>
