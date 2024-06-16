<?php
chdir(__DIR__);
require_once '../config/config.php';

session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$sql = "SELECT username, first_name, last_name, email FROM users WHERE id = ?";
if($stmt = $mysqli->prepare($sql)){
    $stmt->bind_param("i", $param_id);
    $param_id = $_SESSION["id"];
    
    if($stmt->execute()){
        $stmt->store_result();
        if($stmt->num_rows == 1){
            $stmt->bind_result($username, $first_name, $last_name, $email);
            $stmt->fetch();
        }
    }
    $stmt->close();
}
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Профил на студента</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="wrapper">
        <h2>Профил на студента</h2>
        <p>Поребителско име: <?php echo $username; ?></p>
        <p>Име: <?php echo $first_name; ?></p>
        <p>Фамилия: <?php echo $last_name; ?></p>
        <p>Имейл: <?php echo $email; ?></p>
        <div class="profile-picture">
            <img src="path/to/profile/picture.png" alt="Профилна снимка">
        </div>
        <a href="index.php">Назад към началната страница</a>
    </div>    
</body>
</html>