<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once 'C:/xampp/htdocs/graduation_management/config/config.php';

$name = $degree = $graduation_year = "";
$name_err = $degree_err = $graduation_year_err = "";

if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    $id = trim($_GET["id"]);
    
    $sql = "SELECT name, degree, graduation_year FROM students WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("i", $param_id);
        $param_id = $id;

        if ($stmt->execute()) {
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($name, $degree, $graduation_year);
                $stmt->fetch();
            } else {
                echo "Грешка: Невалидно ID.";
                exit();
            }
        } else {
            echo "Нещо се обърка. Моля, опитайте отново по-късно.";
        }

        $stmt->close();
    } else {
        echo "Грешка в подготовката на заявката.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    
    if (empty(trim($_POST["name"]))) {
        $name_err = "Моля, въведете име.";
    } else {
        $name = trim($_POST["name"]);
    }
    
    if (empty(trim($_POST["degree"]))) {
        $degree_err = "Моля, изберете степен.";
    } else {
        $degree = trim($_POST["degree"]);
    }

    if (empty(trim($_POST["graduation_year"]))) {
        $graduation_year_err = "Моля, въведете година на дипломиране.";
    } else {
        $graduation_year = trim($_POST["graduation_year"]);
    }

    if (empty($name_err) && empty($degree_err) && empty($graduation_year_err)) {
        $sql = "UPDATE students SET name = ?, degree = ?, graduation_year = ? WHERE id = ?";
        
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ssii", $param_name, $param_degree, $param_graduation_year, $param_id);

            $param_name = $name;
            $param_degree = $degree;
            $param_graduation_year = $graduation_year;
            $param_id = $id;

            if ($stmt->execute()) {
                header("location: manage_students.php");
                exit();
            } else {
                echo "Нещо се обърка. Моля, опитайте отново по-късно.";
            }

            $stmt->close();
        }
    }
    
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Редактиране на студент</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrapper">
        <h2>Редактиране на студент</h2>
        <p>Моля, редактирайте полетата и запазете промените.</p>
        <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Име</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err;?></span>
            </div>
            <div class="form-group <?php echo (!empty($degree_err)) ? 'has-error' : ''; ?>">
                <label>Степен</label>
                <select name="degree" class="form-control">
                    <option value="бакалавър" <?php echo $degree == 'бакалавър' ? 'selected' : ''; ?>>Бакалавър</option>
                    <option value="магистър" <?php echo $degree == 'магистър' ? 'selected' : ''; ?>>Магистър</option>
                    <option value="докторант" <?php echo $degree == 'докторант' ? 'selected' : ''; ?>>Докторант</option>
                </select>
                <span class="help-block"><?php echo $degree_err;?></span>
            </div>
            <div class="form-group <?php echo (!empty($graduation_year_err)) ? 'has-error' : ''; ?>">
                <label>Година на дипломиране</label>
                <input type="text" name="graduation_year" class="form-control" value="<?php echo $graduation_year; ?>">
                <span class="help-block"><?php echo $graduation_year_err;?></span>
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Запази">
                <a href="manage_students.php" class="btn btn-default">Отказ</a>
            </div>
        </form>
    </div>
</body>
</html>
