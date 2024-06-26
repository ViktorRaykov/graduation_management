<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || $_SESSION["role"] !== 'admin'){
    header("location: login.php");
    exit;
}

require_once '../config/config.php';

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);

    $sql = "DELETE FROM students WHERE user_id = (SELECT user_id FROM students WHERE id = ?)";
    if($stmt = $mysqli->prepare($sql)){
        $stmt->bind_param("i", $param_id);
        $param_id = $id;
        
        if($stmt->execute()){
            $sql = "DELETE FROM users WHERE id = (SELECT user_id FROM students WHERE id = ?)";
            if($stmt = $mysqli->prepare($sql)){
                $stmt->bind_param("i", $param_id);
                $param_id = $id;
                
                if($stmt->execute()){
                    header("location: manage_students.php");
                } else {
                    echo "Нещо се обърка при изтриването от таблицата users. Моля, опитайте отново.";
                }
                $stmt->close();
            }
        } else {
            echo "Нещо се обърка при изтриването от таблицата students. Моля, опитайте отново.";
        }
        $stmt->close();
    }
} else {
    header("location: error.php");
    exit();
}

$mysqli->close();
?>
