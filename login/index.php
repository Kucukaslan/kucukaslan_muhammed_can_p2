<?php
    // D:\drive\Bilkent\2020-2021_Summer\staj\db\vcs - Kopya
    include("../config.php");

    session_start();

    if(isset($conn) && $_SERVER["REQUEST_METHOD"] == "POST") {
        // username and password sent from the form
        $username = $_POST['username'];
        $password =$_POST['password'];

        //set parameters
        $entered_username = $username;
        $entered_password = $password;


        $sql = "SELECT cname, cid, wallet  FROM `can_kucukaslan`.`customer` WHERE cname = '"
            .$entered_username."' and cid = '".$entered_password."'";

        if($result = $conn->query($sql)) {

            $data_arr = array();

            if ($row = $result->fetch()) {//inputs are correct, start session
                session_start();
                $_SESSION['cname'] = $row['cname'] ;
                $_SESSION['cid'] = $row['cid'] ;
                $_SESSION['wallet'] = $row['wallet'];
                header("location: ..");
            } else {
                // wrong credentials
                echo "<script type='text/javascript'>alert('Invalid Username or Password.');</script>";
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../styles.css">
    <style>

    </style>
</head>
<body>
<!--
<header class="navbar">
    <div class="navbar-text">Simple e-trade app <div class="navbar-text" style="text-align:right">Logout</div>
    </div>
</header>
-->

<div class="container">
    <nav class="navbar">
        <h4 class="navbar-text">Simple, very very simple, e-trade app</h4>
    </nav>

    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <h2>Login to Market</h2>
            <p>Please enter your username and password to login.</p>

            <form id="loginForm" action="" method="post">


                <div class="form-group">
                    <label for="username">Username</label> <br>
                    <input type="text" name="username" class="form-control" id="username" required='true'>

                </div>
                <div class="form-group">
                    <label for="password">Password</label><br>
                    <input type="password" name="password" class="form-control" id="password" required='true'>

                </div>
                <div class="form-group">
                    <input type="submit"  class="button button_submit" value="Login">
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
