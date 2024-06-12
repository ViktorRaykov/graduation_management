<?php
require_once 'C:/xampp/htdocs/graduation_management/config/config.php';

$username = $password = $email = $first_name = $last_name = $role = "";
$username_err = $password_err = $email_err = $first_name_err = $last_name_err = $role_err = "";

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST") {

    if(empty(trim($_POST["username"]))){
        $username_err = "Моля, въведете потребителско име.";
    } else {
        $sql = "SELECT id FROM users WHERE username = ?";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("s", $param_username);
            $param_username = trim($_POST["username"]);
            if($stmt->execute()){
                $stmt->store_result();
                if($stmt->num_rows == 1){
                    $username_err = "Това потребителско име вече съществува.";
                } else {
                    $username = trim($_POST["username"]);
                }
            } else {
                echo "Нещо се обърка. Моля, опитайте отново по-късно.";
            }
            $stmt->close();
        }
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "Моля, въведете парола.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Паролата трябва да бъде поне 6 символа.";
    } else {
        $password = trim($_POST["password"]);
    }

    if(empty(trim($_POST["email"]))){
        $email_err = "Моля, въведете имейл.";
    } elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
        $email_err = "Моля, въведете валиден имейл.";
    } else {
        $email = trim($_POST["email"]);
    }

    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Моля, въведете име.";
    } else {
        $first_name = trim($_POST["first_name"]);
    }

    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Моля, въведете фамилия.";
    } else {
        $last_name = trim($_POST["last_name"]);
    }

    if(empty(trim($_POST["role"]))){
        $role_err = "Моля, изберете роля.";
    } else {
        $role = trim($_POST["role"]);
    }

    if(empty($username_err) && empty($password_err) && empty($email_err) && empty($first_name_err) && empty($last_name_err) && empty($role_err)){
        $sql = "INSERT INTO users (username, password, email, first_name, last_name, role) VALUES (?, ?, ?, ?, ?, ?)";
        if($stmt = $mysqli->prepare($sql)){
            $stmt->bind_param("ssssss", $param_username, $param_password, $param_email, $param_first_name, $param_last_name, $param_role);
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_email = $email;
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_role = $role;
            if($stmt->execute()){

                $user_id = $stmt->insert_id;

                if ($role == 'student') {
                    $sql = "INSERT INTO students (user_id, name) VALUES (?, ?)";
                    if($stmt_student = $mysqli->prepare($sql)){
                        $stmt_student->bind_param("is", $user_id, $param_username);
                        $stmt_student->execute();
                        $stmt_student->close();
                    }
                }

                header("location: login.php");
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
    <title>Регистрация</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrapper">
        <h2>Регистрация</h2>
        <p>Моля, попълнете тази форма, за да създадете акаунт.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Потребителско име</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Парола</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Имейл</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
                <label>Име</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>">
                <span class="help-block"><?php echo $first_name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
                <label>Фамилия</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>">
                <span class="help-block"><?php echo $last_name_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
                <label>Роля</label>
                <select name="role" class="form-control">
                    <option value="student">Студент</option>
                    <option value="admin">Администратор</option>
                </select>
                <span class="help-block"><?php echo $role_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Регистрация">
            </div>
            <p>Вече имате акаунт? <a href="login.php">Влезте тук</a>.</p>
        </form>
    </div>    
</body>
</html>
