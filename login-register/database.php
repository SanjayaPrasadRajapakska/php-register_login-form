<?php
$hostName= "localhost";
$dbUser= "root";
$bdPassword= "";
$dbName= "login-register";

$conn = mysqli_connect($hostName, $dbUser, $bdPassword, $dbName);
if (!$conn) {
    die ("Something went to wrong;");
}
?>