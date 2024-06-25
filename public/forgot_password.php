<?php
chdir(__DIR__);
require_once '../config/config.php';

$email = $email_err = $password = $confirm_password = $password_err = $confirm_password_err = $message = "";

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){

    if (empty(trim($_POST["email"]))) {
        $email_err = "Моля, въведете имейл.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Моля, въведете валиден имейл.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Моля, въведете нова парола.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Паролата трябва да бъде поне 6 символа.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Моля, потвърдете паролата.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Паролите не съвпадат.";
        }
    }

    if (empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "SELECT id FROM users WHERE email = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = $email;

            if ($stmt->execute()) {
                $stmt->store_result();

                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id);
                    if ($stmt->fetch()) {
                        $sql = "UPDATE users SET password = ? WHERE id = ?";
                        if ($stmt_update = $mysqli->prepare($sql)) {
                            $stmt_update->bind_param("si", $param_password, $param_id);
                            $param_password = password_hash($password, PASSWORD_DEFAULT);
                            $param_id = $id;

                            if ($stmt_update->execute()) {
                                $message = "Имейлът с потвърждение беше изпратен на вашата електронна поща.";
                            } else {
                                echo "Нещо се обърка. Моля, опитайте отново.";
                            }
                            $stmt_update->close();
                        }
                    }
                } else {
                    $email_err = "Няма акаунт, свързан с този имейл.";
                }
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
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Забравена парола</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .message {
            text-align: center;
            font-weight: bold;
            color: green;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Забравена парола</h2>
        <p>Моля, попълнете тази форма, за да възстановите паролата си.</p>
        <?php 
        if (!empty($message)) {
            echo '<div class="message">' . $message . '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Имейл</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Нова парола</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Потвърдете паролата</label>
                <input type="password" name="confirm_password" class="form-control">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Възстановяване">
                <a href="login.php" class="btn btn-secondary">Назад</a>
            </div>
        </form>
    </div>
</body>
</html>
