<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: login.php");
    exit;
}

require_once '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $email = trim($_POST["email"]);
    $password = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
    $degree = trim($_POST["degree"]);
    $graduation_year = trim($_POST["graduation_year"]);

    $sql = "SELECT id FROM users WHERE username = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $param_username);
        $param_username = $username;
        if ($stmt->execute()) {
            $stmt->store_result();
            if ($stmt->num_rows == 1) {
                echo "Това потребителско име вече съществува.";
            } else {
                $sql = "INSERT INTO users (username, password, email, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, 'student')";
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("sssss", $username, $password, $email, $first_name, $last_name);
                    if ($stmt->execute()) {
                        $user_id = $stmt->insert_id;
                        $sql = "INSERT INTO students (user_id, name, degree, graduation_year) VALUES (?, ?, ?, ?)";
                        if ($stmt = $mysqli->prepare($sql)) {
                            $stmt->bind_param("isss", $user_id, $username, $degree, $graduation_year);
                            if ($stmt->execute()) {
                                header("location: manage_students.php");
                                exit;
                            } else {
                                echo "Нещо се обърка. Моля, опитайте отново.";
                            }
                        }
                    } else {
                        echo "Нещо се обърка. Моля, опитайте отново.";
                    }
                }
            }
        } else {
            echo "Нещо се обърка. Моля, опитайте отново.";
        }
        $stmt->close();
    }
    $mysqli->close();
}
?>


<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Добавяне на студент</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrapper">
        <h2>Добавяне на студент</h2>
        <form action="" method="post">
            <div class="form-group">
                <label>Потребителско име</label>
                <input type="text" name="username" class="form-control" required>
            </div>    
            <div class="form-group">
                <label>Име</label>
                <input type="text" name="first_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Фамилия</label>
                <input type="text" name="last_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Имейл</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Парола</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Степен</label>
                <select name="degree" class="form-control" required>
                    <option value="бакалавър">Бакалавър</option>
                    <option value="магистър">Магистър</option>
                    <option value="докторант">Докторант</option>
                </select>
            </div>
            <div class="form-group">
                <label>Година на дипломиране</label>
                <input type="text" name="graduation_year" class="form-control" required>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Добави">
                <a href="manage_students.php" class="btn btn-secondary">Назад</a>
            </div>
        </form>
    </div>    
</body>
</html>
