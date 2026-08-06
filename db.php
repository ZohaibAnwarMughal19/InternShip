<?php
// Centralized Database Connection Settings
$db_host = "localhost";
$db_user = "root";
$db_pass = "";
$db_name = "internship_db";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if(!$conn){
    die("Database Connection Error: " . mysqli_connect_error());
}
?>
