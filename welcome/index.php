<?php
include '../constants.php';
$servername = localhost;
$username = username;
$password = password;

$connname = "can_kucukaslan";
try {
    $conn = new PDO("mysql:host=$servername;dbname=" . $connname, $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";

    session_start();
    echo "Welcome to the system: ";
    echo $_SESSION['cname'];
    echo "\n" . $_SESSION['cid'];
    echo "\n" .  $_SESSION['wallet'];
}
catch (PDOException $e) {
      echo "Connection failed: " . $e->getMessage();

}
?>