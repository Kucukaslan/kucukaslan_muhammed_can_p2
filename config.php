<?php
include 'constants.php';
$servername = localhost;
$username = username;
$password = password;

$connname = "can_kucukaslan";
try {
    $conn = new PDO("mysql:host=$servername;dbname=".$connname, $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";

} catch(PDOException $e) {
    echo $servername ." ". $username;
    echo "Connection failed: " . $e->getMessage();
}