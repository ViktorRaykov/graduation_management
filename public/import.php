<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: login.php");
    exit;
}

require_once '../config/config.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileName = $_FILES["file"]["tmp_name"];
    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($fileName, "r");

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            $sql = "INSERT INTO students (name, degree, graduation_year) VALUES (?, ?, ?)";
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param("sss", $column[0], $column[1], $column[2]);
                $stmt->execute();
                $stmt->close();
            }
        }
        fclose($file);
        header("location: manage_students.php");
        exit;
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Импортиране на данни</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <div class="wrapper">
        <h2>Импортиране на данни</h2>
        <form action="" method="post" name="uploadCSV" enctype="multipart/form-data">
            <div class="form-group">
                <label>Изберете CSV файл:</label>
                <input type="file" name="file" class="form-control">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Импортиране">
                <a href="manage_students.php" class="btn btn-secondary">Назад</a>
            </div>
        </form>
    </div>
</body>
</html>
