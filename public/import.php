<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin') {
    header("location: login.php");
    exit;
}

require_once '../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileName = $_FILES["file"]["tmp_name"];
    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($fileName, "r");
        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
            if (isset($column[0]) && isset($column[1]) && isset($column[2]) && isset($column[3]) && isset($column[4]) && isset($column[5]) && isset($column[6])) {
                $sql_check = "SELECT id FROM users WHERE username = ?";
                if ($stmt_check = $mysqli->prepare($sql_check)) {
                    $stmt_check->bind_param("s", $column[0]);
                    $stmt_check->execute();
                    $stmt_check->store_result();
                    if ($stmt_check->num_rows > 0) {
                        $stmt_check->close();
                        $errorCount++;
                        $errors[] = "Username already exists: " . $column[0];
                        continue;
                    }
                    $stmt_check->close();
                }

                $sql = "INSERT INTO users (username, password, email, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, 'student')";
                if ($stmt = $mysqli->prepare($sql)) {
                    $stmt->bind_param("sssss", $column[0], password_hash($column[1], PASSWORD_DEFAULT), $column[2], $column[3], $column[4]);
                    if ($stmt->execute()) {
                        $user_id = $stmt->insert_id;

                        $sql_student = "INSERT INTO students (user_id, name, degree, graduation_year) VALUES (?, ?, ?, ?)";
                        if ($stmt_student = $mysqli->prepare($sql_student)) {
                            $stmt_student->bind_param("isss", $user_id, $column[0], $column[5], $column[6]);
                            if ($stmt_student->execute()) {
                                $successCount++;
                            } else {
                                $errorCount++;
                                $errors[] = "Failed to insert student for username: " . $column[0];
                            }
                            $stmt_student->close();
                        } else {
                            $errorCount++;
                            $errors[] = "Failed to prepare student insert statement for username: " . $column[0];
                        }
                    } else {
                        $errorCount++;
                        $errors[] = "Failed to insert user with username: " . $column[0];
                    }
                    $stmt->close();
                } else {
                    $errorCount++;
                    $errors[] = "Failed to prepare user insert statement for username: " . $column[0];
                }
            } else {
                $errorCount++;
                $errors[] = "Invalid data for username: " . (isset($column[0]) ? $column[0] : "unknown");
            }
        }
        fclose($file);

        if ($errorCount == 0) {
            header("location: manage_students.php?status=succ");
        } else {
            header("location: manage_students.php?status=err&errors=" . urlencode(implode(", ", $errors)));
        }
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
        <?php
        if (isset($_GET['status']) && $_GET['status'] == 'err') {
            echo '<div class="alert alert-danger">Имаше грешки при импортирането: ' . htmlspecialchars($_GET['errors']) . '</div>';
        } elseif (isset($_GET['status']) && $_GET['status'] == 'succ') {
            echo '<div class="alert alert-success">Данните бяха импортирани успешно!</div>';
        }
        ?>
    </div>
</body>
</html>
