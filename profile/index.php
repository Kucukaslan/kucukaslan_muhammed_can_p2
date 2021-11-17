<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Page of <?php echo $_SESSION['cname']?></title>
    <link rel="stylesheet" href="../styles.css">

    <meta name="author" content="Muhammed Can Küçükaslan">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

</head>
<body>
<div class=\"container\">

<?php
    include "../config.php";
    session_start();

    if(! isset($_SESSION['cid'] )) {
        echo"<div class='centerwrapper'> <div class = 'centerdiv'>"
        . "You haven't logged in";
         echo "<form method='get' action=\"..\"><div class=\"form-group\">
                        <input type=\"submit\" class=\"button button_submit\" value=\"Go to Login Page\">
                    </div> </form>";
            echo "</div> </div>";
    }
    else {
        if($_SERVER["REQUEST_METHOD"] == "POST"
            && isset($_POST['cid']) ) {
            // the customer can top up money to his/her wallet.
            if( isset($_POST['deposit_amount'])) {
                try {
                    $cid = $_POST['cid'];
                    $deposit_amount = $_POST['deposit_amount'];
                    $sql_deposit_money = "UPDATE `customer` SET `wallet` = `wallet` + "
                        . $deposit_amount . " WHERE `cid` = '" . $cid . "' ;";
                    $conn->query($sql_deposit_money);
                    echo "<script type='text/javascript'>alert('You have succesfully deposited " . $deposit_amount . " in your account.');</script>";
                }
                catch (PDOException $e) {
                    echo "<script type='text/javascript'>alert('Some error occured! We're sorry!);
                        console.log('".$e->getMessage()."')</script>";
                }

            }
            elseif ( isset($_POST['pid'])) {
                $pid = $_POST['pid'];
                $cid = $_POST['cid'];
                $quantity = $_POST['quantity'];

                // DOES NOT UPDATES THE WALLET!
                $sql_return = "DELETE FROM `buy`  WHERE `cid` = '" .$cid
                    . "' AND`pid` = '" .$pid  . "' AND `quantity` = '" .$quantity  . "' ;";
                $conn->query($sql_return);
                echo "<script type='text/javascript'>alert('You have succesfully returned " . $quantity." of ".$pid.". ');</script>";

            }
        }

        print("
            <header class=\"navbar\">
                <a class=\"navbar-text\" href=\"..\" >Dashboard</a>
                <a class=\"navbar-text\" href=\".\">Profile</a>
                <a class=\"navbar-text\" href=\"../logout.php\" id=\"logout\">Logout</a>
            </header>");

        $cid = $_SESSION['cid'];
        $wallet_sql = "SELECT `wallet`, `cname` FROM `customer` WHERE `cid` = '". $cid."' ; ";
        $res = $conn->query($wallet_sql);
        $row =$res->fetch();
        $wallet = $row['wallet'];
        $cname = $row['cname'];
        $_SESSION['wallet'] = $wallet;
        $_SESSION['cname'] = $cname;
        echo "<h3> <abbr title='Your Majesties, Your Excellencies, Your Highnesses'>Hey</abbr> ". $_SESSION['cname']." </h3>";
        echo "<i> Welcome to the <abbr title='arguably'>smallest</abbr> e-market, <abbr title='of course by us'> <b>ever</b></abbr>!</i>";
        echo "</br>You have " . $wallet ." &#8378 </br>";

        echo "<form method=\"post\" action=\"index.php\"> 
                            <input type=\"number\" min='10' step='5' required='true' name=\"deposit_amount\">
                            <input type=\"hidden\" name=\"cid\"  required='true' value=" . $cid . "></td>"
            . "<td><input type='submit' class='button_submit' value='Deposit'></form>";

            try {
                    $sql_list = "select p.`pid`, p.`pname`, p.`price`, b.`quantity`, p.`price` * b.`quantity` as total_cost"
                        ." from `product` p NATURAL join `buy` b "
                        ." where b.cid = '".$cid."' ;";

                    $result = $conn->query($sql_list);

                    echo "<table style=\"width:100%\">";
                    echo "<tr >"
                        . "<th>" . "Product Id" . "</th>"
                        . "<th>" . "Product Name" . "</th>"
                        . "<th>" . "Price" . "</th>"
                        . "<th>" . "Amount" . "</th>"
                        . "<th>" . "Total Cost" . "</th>"
                        . "</tr>";
                    while ($row = $result->fetch()) {
                        echo "<tr>"
                            . "<td>" . $row['pid'] . "</td>"
                            . "<td>" . $row['pname'] . "</td>"
                            . "<td>" . $row['price'] . "</td>"
                            . "<td>" .  $row['quantity'] . "</td>"
                            . "<td>" . round($row['total_cost'], 2, PHP_ROUND_HALF_UP) . "</td>"
                            . "<td> <form method=\"post\" action=\"index.php\"> 
                                    <input type=\"hidden\" name=\"pid\" value=" . $row['pid'] . "></td>
                                    <input type=\"hidden\" name=\"cid\" value=" . $cid . "></td>
                                    <input type=\"hidden\" name=\"quantity\" value=" . $row['quantity']. "></td>"
                            . "<td><input type='submit' class='button_submit' value='Return'></form></td>";
                        echo "</tr>";
                    }
                    echo "</table>";

        }
        catch (PDOException $p) {
            echo $p->getMessage();
        }

        /*

            At this page, show ids, names, and quantities of the
            products which customer has bought. Also provide an input field and a “return” button, so that
            the customer can choose to return in one of these displayed products (e.g., by typing the product
            id, quantity of the product that will be returned and clicking on the return button). Increase the
            money  the  customer  has  in  his/her  wallet  accordingly.
        */
}
?>


</div>


</body>
</html>