<?php
// D:\drive\Bilkent\2020-2021_Summer\staj\db\vcs - Kopya
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

    session_start();

//defining necessary variables
    $username = "";
    $password = "";


    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // username and password sent from form
        $username = $_POST['username'];
        $password =$_POST['password'];

        //set parameters
        $entered_username = $username;
        $entered_password = $password;


        $sql = "SELECT cname, cid, wallet  FROM `can_kucukaslan`.`customer` WHERE cname = '"
            .$entered_username."' and cid = '".$entered_password."'";

        if($result = $conn->query($sql)) {

            $data_arr = array();




            if ($row = $result->fetch()) {//inputs are correct session is starting
                session_start();
                $_SESSION['cname'] = $row['cname'] ;
                $_SESSION['cid'] = $row['cid'] ;
                $_SESSION['wallet'] = $row['wallet'];
                header("location: ./welcome");
            } else {
                //wrong input
                echo "<script type='text/javascript'>alert('Invalid Username or Password.');</script>";
            }
        }

    }
} catch(PDOException $e) {
    echo $servername ." ". $username;
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="/styles.css">
    <style>

    </style>
</head>
<body>
<div class="container">
    <nav class="navbar">
        <h4 class="navbar-text">Simple, very very simple, e-trade app</h4>
    </nav>
    <div id="centerwrapper">
        <div id="centerdiv">
            <br><br>
            <h2>Login to Market</h2>
            <p>Please enter your username and password to login.</p>

            <form id="loginForm" action="" method="post">


                <div class="form-group">
                    <label for="username">Username</label> <br>
                    <input type="text" name="username" class="form-control" id="username">

                </div>
                <div class="form-group">
                    <label for="password">Password</label><br>
                    <input type="password" name="password" class="form-control" id="password">

                </div>
                <div class="form-group">
                    <input type="submit" onclick="checkEmptyAndLogin()" class="button button_submit" value="Login">
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    function checkEmptyAndLogin() {
        var usernameVal = document.getElementById("username").value;
        var passwordVal = document.getElementById("password").value;
        if (usernameVal === "" || passwordVal === "") {
            alert("Some fields are empty!");
        }
        else {
            var form = document.getElementById("loginForm").submit();
        }
    }
</script>
</body>
</html>
