<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer welcome page</title>
    <link rel="stylesheet" href="./styles.css">

    <meta name="author" content="Muhammed Can Küçükaslan">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
        Customer welcome page
        <a class="navbar-text" href="" >Dashboard</a>
        <a class="navbar-text" href="logout.php" id="logout" >Logout</a>
    </nav>

    <p >
    <?php
    // D:\drive\Bilkent\2020-2021_Summer\staj\db\vcs - Kopya
    include("config.php");
    session_start();

    if( !isset($_SESSION['cname']) || !isset($conn) ){
        header("location: ./login");
    }
    else{
        echo "<h3> <abbr title='Your Majesties, Your Excellencies, Your Highnesses'>Hey</abbr> ". $_SESSION['cname']." </h3>";
        echo "<i> Welcome to the <abbr title='arguably'>smallest</abbr> e-market, <abbr title='of course by'> <b>ever</b></abbr>!</i>";
        $cid = $_SESSION['cid'];
        $wallet_sql = "SELECT `wallet` FROM `customer` WHERE `cid` = '". $cid."' ; ";
        $res = $conn->query($wallet_sql);
        $wallet = $res->fetch()['wallet']; //$_SESSION['wallet'];
        $_SESSION['wallet'] = $wallet;
        echo "</br>You have " . $wallet ." &#8378 </br>";

    }

    ?>
    </p>
    <div id="centerwrapper">
        <div id="centerdiv">
            <br><br>
            <?php
            try {
                try {
                    $sql_list = "select * from product";
                    $result = $conn->query($sql_list);
                } catch (PDOException $ex ){
                    echo $ex->getMessage();
                    sleep(1);
                    $sql_list = "select * from product";
                    $result = $conn->query($sql_list);
                }
            echo "<table style=\"width:100%\">";
            echo "<tr >"
                . "<th>" . "Product Id" . "</th>"
                . "<th>" . "Product Name" . "</th>"
                . "<th>" . "Price" . "</th>"
                . "<th>" . "Amount" . "</th>"
                . "</tr>";
            while ($row = $result->fetch()) {
                echo "<tr>"
                    . "<td>" . $row['pid'] . "</td>"
                    . "<td>" . $row['pname'] . "</td>"
                    . "<td>" . $row['price'] . "</td>"
                    . "<td> <form method=\"post\" action=\"index.php\"> 
                        <input type=\"number\" min='1' required='true' name=\"amount\">
                        <input type=\"hidden\" name=\"pid\" value=" . $row['pid'] . "></td>"
                    . "<td><input type='submit' class='button_submit' value='Buy'></form></td>";

                echo $row['stock'] < 10 ? "<td>Only <b>" . $row['stock'] . "</b> remained in stock.</td>" : "";
                echo "</tr>";
            }
             echo "</table>";
            }
            catch (PDOException $p) {
                echo $p->getTraceAsString();
            }
            ?>
        </div>
    </div>
</div>
</body>
</html>

<?php
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pid']) && isset($_POST['amount'])) {
    $pid = $_POST['pid'];
    $sql = "select price, stock from product where pid = '".$_POST['pid']."';";
    $result = $conn->query($sql);
    $price = 0;
    $stock = 0;
    $amount = $_POST['amount'] == "" ? 0 : $_POST['amount'];
    if($datum = $result->fetch()){
        $price = $datum['price'];
        $stock = $datum['stock'];

    }
    $cost = $amount * $price;
    if ( $wallet < $cost ) {
        echo "<script type='text/javascript'>alert('You do not have enough money in your account');</script>";
    }
    else if( $stock < $amount) {
        echo "<script type='text/javascript'>
                       alert('There is not enough of the items you requested. Only "
            .$stock. " items are in stock.');</script>";
    }
    else {

        try {
            //echo "<script type='text/javascript'>alert('Entered Correct Place');</script>";
            // deduce the money =  $wallet -$cost;
            $sql = "UPDATE `customer` SET `wallet`=`wallet` -".$cost." WHERE `cid` ='". $cid."'; ";
            // deduce the stock
            $sql = $sql." UPDATE `product` SET `stock`= `stock` - "
                .$amount." WHERE `pid`='".$pid."'; ";

            // Insert buy data
            $sql = $sql." INSERT INTO `buy`(`cid`, `pid`, `quantity`) VALUES ( '".$cid."' , '".$pid."', ".$amount." );";

            $res = $conn->query($sql);
            $res->fetchAll();

            header("Refresh:0");
        } catch (PDOException $e) {
            echo "<script > console.log('". $e->getMessage()."\n".$e->getTraceAsString()."')</script>";
        }
    }
}
?>
