<?php
chdir(__DIR__);
require_once '../config/config.php';

$username = $password = "";
$username_err = $password_err = $login_err = "";

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){

    if (empty(trim($_POST["username"]))) {
        $username_err = "Моля, въведете потребителско име.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Моля, въведете парола.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id, username, password, role FROM users WHERE username = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = $username;

            if ($stmt->execute()) {
                $stmt->store_result();
                // After successful login
if ($stmt->num_rows == 1) {
    session_start();

    $stmt->bind_result($id, $username, $hashed_password, $role);
    if ($stmt->fetch()) {
        if (password_verify($password, $hashed_password)) {
            // Password is correct, start a new session
            session_start();

            // Store data in session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = $id;
            $_SESSION["username"] = $username;
            $_SESSION["role"] = $role;

            // Redirect user to the appropriate main page
            if ($role === 'admin') {
                header("location: admin_index.php");
            } else {
                header("location: student_index.php");
            }
        } else {
            // Password is not valid, display a generic error message
            $login_err = "Invalid username or password.";
        }
    }
} else {
    // Username doesn't exist, display a generic error message
    $login_err = "Invalid username or password.";
}

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
    <title>Вход</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="wrapper">
        <h2>Вход</h2>
        <p>Моля, попълнете вашите данни за вход.</p>
        <?php 
        if (!empty($login_err)) {
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>
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
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Вход">
            </div>
            <p>Нямате акаунт? <a href="register.php">Регистрация</a>.</p>
            <p>Забравена парола? <a href="forgot_password.php">Нулиране на парола</a>.</p>
        </form>
    </div>    
</body>
</html>
