<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: login.php");
    exit;
}

require_once '../config/config.php';

$name = $degree = $graduation_year = "";
$name_err = $degree_err = $graduation_year_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty(trim($_POST["name"]))){
        $name_err = "Моля, въведете име.";
    } else {
        $name = trim($_POST["name"]);
    }

    if(empty(trim($_POST["degree"]))){
        $degree_err = "Моля, изберете степен.";
    } else {
        $degree = trim($_POST["degree"]);
    }

    if(empty(trim($_POST["graduation_year"]))){
        $graduation_year_err = "Моля, въведете година на дипломиране.";
    } else {
        $graduation_year = trim($_POST["graduation_year"]);
    }

    if(empty($name_err) && empty($degree_err) && empty($graduation_year_err)){
        $sql = "INSERT INTO students (name, degree, graduation_year) VALUES (?, ?, ?)";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("sss", $param_name, $param_degree, $param_graduation_year);
            $param_name = $name;
            $param_degree = $degree;
            $param_graduation_year = $graduation_year;

            if($stmt->execute()){
                header("location: manage_students.php");
            } else {
                echo "Нещо се обърка. Моля, опитайте отново.";
            }
            $stmt->close();
        }
    }

    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Добавяне на студент</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrapper">
        <h2>Добавяне на нов студент</h2>
        <p>Попълнете формата, за да добавите нов студент.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                <label>Име</label>
                <input type="text" name="name" class="form-control" value="<?php echo $name; ?>">
                <span class="help-block"><?php echo $name_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($degree_err)) ? 'has-error' : ''; ?>">
                <label>Степен</label>
                <select name="degree" class="form-control">
                    <option value="bachelor">Бакалавър</option>
                    <option value="master">Магистър</option>
                    <option value="phd">Докторант</option>
                </select>
                <span class="help-block"><?php echo $degree_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($graduation_year_err)) ? 'has-error' : ''; ?>">
                <label>Година на дипломиране</label>
                <input type="text" name="graduation_year" class="form-control" value="<?php echo $graduation_year; ?>">
                <span class="help-block"><?php echo $graduation_year_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Добавяне">
            </div>
            <p><a href="manage_students.php">Обратно към управление на студенти</a>.</p>
        </form>
    </div>    
</body>
</html>
