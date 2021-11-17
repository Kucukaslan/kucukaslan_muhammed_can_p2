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
    <header class="navbar">
        <a class=\"navbar-text\" href=".">Dashboard</a>
        <a class="navbar-text" href="profile" >Profile</a>
        <a class="navbar-text" href="logout.php" id="logout" >Logout</a>
    </header>

    <p id='info'>
    <?php
    // D:\drive\Bilkent\2020-2021_Summer\staj\db\vcs - Kopya
    include("config.php");
    session_start();

    if( !isset($_SESSION['cname']) || !isset($conn) ){
        header("location: ./login");
    }
    else{
        $cid = $_SESSION['cid'];
        $wallet_sql = "SELECT `wallet` FROM `customer` WHERE `cid` = '". $cid."' ; ";
        $res = $conn->query($wallet_sql);
        $wallet = $res->fetch()['wallet']; //$_SESSION['wallet'];
        $_SESSION['wallet'] = $wallet;
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pid']) && isset($_POST['amount'])) {
            $pid = $_POST['pid'];
            $sql = "select price, stock from product where pid = '" . $_POST['pid'] . "';";
            $result = $conn->query($sql);
            $price = 0;
            $stock = 0;
            $amount = $_POST['amount'] == "" ? 0 : $_POST['amount'];
            if ($datum = $result->fetch()) {
                $price = $datum['price'];
                $stock = $datum['stock'];

            }
            $cost = round($amount * $price, 2,1);
            if ($wallet < $cost) {
                echo "<script type='text/javascript'>alert('You do not have enough money in your account');</script>";
            } else if ($stock < $amount) {
                echo "<script type='text/javascript'>
                       alert('There is not enough of the items you requested. Only "
                    . $stock . " items are in stock.');</script>";
            } else {

                try {
                    //echo "<script type='text/javascript'>alert('Entered Correct Place');</script>";
                    // deduce the money =  $wallet -$cost;
                    $sql = "UPDATE `customer` SET `wallet`=`wallet` -" . $cost . " WHERE `cid` ='" . $cid . "'; ";
                    // deduce the stock
                    $sql = $sql . " UPDATE `product` SET `stock`= `stock` - "
                        . $amount . " WHERE `pid`='" . $pid . "'; ";

                    // Insert buy data

                    $sql = $sql . "INSERT INTO `buy`(`cid`, `pid`, `quantity`) VALUES ( '" . $cid . "' , '" . $pid . "', " . $amount . " )"
                        ." ON DUPLICATE KEY UPDATE  `quantity` = `quantity` + ".$amount
                        ." ;";
                    $res = $conn->query($sql);

                    echo "<script type='text/javascript'>alert('You have succesfully bought ".$amount." ".$pid.".!');</script>";

                    // Update Wallet
                    $res->fetchAll();
                    $wallet_sql = "SELECT `wallet` FROM `customer` WHERE `cid` = '". $cid."' ; ";
                    $res = $conn->query($wallet_sql);
                    $wallet = $res->fetch()['wallet']; //$_SESSION['wallet'];
                    $_SESSION['wallet'] = $wallet;
                } catch (PDOException $e) {
                    echo "<script > console.log('" . $e->getMessage() . "\n" . $e->getTraceAsString() . "')</script>";
                }
            }
        }
        echo "<h3> <abbr title='Your Majesties, Your Excellencies, Your Highnesses'>Hey</abbr> ". $_SESSION['cname']." </h3>";
        echo "<i> Welcome to the <abbr title='arguably'>smallest</abbr> e-market, <abbr title='of course by us'> <b>ever</b></abbr>!</i>";
        echo "<p>You have " . $wallet ." &#8378 </br></p>";

    }

    ?>
    </p>
    <div class="centerwrapper">
        <div class="centerdiv">
            <br><br>
            <?php
            try {
                try {
                    $sql_list = "select * from product where stock > 0";
                    $result = $conn->query($sql_list);
                } catch (PDOException $ex ){
                    echo $ex->getMessage();
                    sleep(1);
                    $sql_list = "select * from product where stock > 0";
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

    <form method='post' action="./profile"><div class="form-group">
            <input type="submit" class="button button_submit" value="Go to Profile Page">
        </div> </form>
</div>
</body>
</html>

