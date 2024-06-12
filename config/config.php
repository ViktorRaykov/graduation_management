<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'graduation_management');


$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($mysqli->connect_error) {
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}
