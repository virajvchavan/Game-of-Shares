<!DOCTYPE html>
<html lang="en">

<?php
include "classes.inc.php";
include "conn.inc.php";
include "functions.index.php"; 
    
//registering the user    
if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['password']))
{
    if(register($conn, ($_POST['first_name']), ($_POST['last_name']), ($_POST['email']), ($_POST['phone']), md5(($_POST['password']))))
    {
        //registration successful
    }
    else
    {
        echo "Registration error";
        //might wanna refresh
    }
}    
    
//leave if not logged in
if(!isLoggedIn())
{
    header("Location:login.php");
}

if($session_db != "off")    
    //change the share price of companies (from functions.index.php)
    changePrices($conn, $time_limit_for_company, $price_limit_for_company);    
    
//check for any messages    
$user->checkMessages($conn);
  
if($session_db != "off")
{   
    //execute orders for logged in user
    $message = $user->executeOrders($conn);
    if($message != "")
    {
        echo "<div id='note'>$message<a id='close' class='pull-right'>[Close]</a></div>";
    }
}
?>
    
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1024">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Game Of Shares</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sidebar.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
    
        <style>
    #balance{
        color: #f6f8f6;
        font-size: 23px;
        background-color: #004D40;
        padding-bottom: 6px;
        padding-top: 6px;
        margin: 8px;
        }
            body{
                font-family: 'Montserrat', sans-serif;
                font-size: 14px;
            }
             .block{
                color: #004D40;
                padding: 5px;
                margin: 10px;
                background: #DCEDC8;
                border-radius: 5px;
                width: 500px;
            }
            .block-title{
                background: #004D40;
                color: #DCEDC8;
                padding: 3px;
            }
            .block-body{
                padding: 5px;
                display: none;
            }
            #bb1{
                display: block;
            }
    
    </style>
    
    <script type="text/javascript">
   function toggle_visibility(id) {
       var e = document.getElementById(id);
       if(e.style.display == 'block')
          e.style.display = 'none';
       else
          e.style.display = 'block';
   }
</script>
    
</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li id="balance">
                    Balance: <?php echo number_format($user->get_balance($conn)); ?>
                </li>
                <li id="balance" style="font-size: 19px;">
                    Total Value: <?php echo number_format($user->get_valuation($conn) + $user->balance)  ; ?>
                </li>
                <li>
                    <a href="index.php">Dashboard</a>
                </li>
                <li>
                    <a href="orders.php">Pending Orders</a>
                </li>
                <li>
                    <a href="trades.php">Trade Book</a>
                </li>
                <li>
                    <a href="leaders.php">LeaderBoard</a>
                </li>
                <br><br><br><br><br><br><br>
                <li>
                    <a href="help.php"  class="active">Help</a>
                </li>
                <li>
                    <a href="about.php">About</a>
                </li>
                <li>
                    <a href="profile.php">Your Profile</a>
                </li>
                <li>
                    <a href="feedback.php">Feedback</a>
                </li>
                <li>
                    <a href="logout.php">Logout (<?php echo $user->get_name($conn); ?>)</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <br>
                        <h4>Please read this page before starting :-)</h4>
                        <h5>Click on the below titles to reveal the answer.</h5>
                        <div class="row">
                            <div class="block col-xs-6">
                                <a href="#"><div class="block-title" onclick="toggle_visibility('bb1');">What is this website?</div></a>
                                <div class="block-body" id="bb1">
                                <ul>
                                    <li>It's a stock market game.</li>
                                    <li>Lets you buy/sell imaginary shares with virtual money.</li>
                                    <li>It is based on very basic Share Market Concepts.</li>
                                    <li>It can help you understand the share market on a basic level.</li>
                                </ul>
                                </div>
                            </div>
                            <div class="block class col-xs-6">
                                <a href="#"><div class="block-title"  onclick="toggle_visibility('bb2');">Who can use this?</div></a>
                                <div class="block-body" id="bb2">
                                <ul>
                                    <li>Those who want to learn about share market.</li>
                                    <li>Having no or very little knowledge of share market.</li>                       
                                </ul>
                                </div>
                            </div>
                        </div>
                       <br>
                        <h3>Concepts you need to know:</h3>
                        <div class="row">
                            <div class="block col-xs-6">
                                <a href="#"><div class="block-title" onclick="toggle_visibility('bb3');">Stock/Share/Equity</div></a>
                                <div class="block-body" id="bb3">
                                    A stock signifies ownership in a company and represents a claim on part of the company's assets and earnings.
                                    <hr>
                                    For example, if a company has 1,000 shares of stock outstanding and one person owns 100 shares, that person would own and have claim to 10% of the company's assets.
                                </div>
                            </div>
                            <div class="block col-xs-6">
                                <a href="#"><div class="block-title" onclick="toggle_visibility('bb4');">Stock Market</div></a>
                                <div class="block-body" id="bb4">
                                A stock market, is a network of buyers and sellers trading Stocks between each other.
                                <hr>
                                The stock prices keep on changing depending on the demand of that share.    
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="block col-xs-6">
                                <a href="#"><div class="block-title" onclick="toggle_visibility('bb5');">What makes the stock price change?</div></a>
                                <div class="block-body" id="bb5">
                                    Share prices change because of supply and demand.
                                    <hr>
                                        If more people want to buy a stock (demand) than sell it (supply), then the price moves up.<br> Conversely, if more people wanted to sell a stock than buy it, there would be greater supply than demand, and the price would fall.
                                </div>
                            </div>
                            <div class="block col-xs-6">
                                <a href="#"><div class="block-title" onclick="toggle_visibility('bb6');">How is profit made?</div></a>
                                <div class="block-body" id="bb6">
                                Suppose you buy 100 shares of XYZ at $40.<hr>
                                Now the price keeps on changing.
                                <br>Eg. You sell these shares when the price is $60, then you've made $2000 profit.
                                <br>In a similar way, you can go in loss.
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="block col-xs-6">
                                <a href="#"><div class="block-title" onclick="toggle_visibility('bb7');">Market Order</div></a>
                                <div class="block-body" id="bb7">
                                    Buy/sell at the current available price.
                                    <hr>
                                        Usually gets executed immediately.<br>
                                        Gets executed at whatever the current stock price is.
                                    <hr>
                                    Eg. If for XYZ, price = $40, then the Market Buy order will execute at the Current Price. 
                                </div>
                            </div>
                            <div class="block col-xs-6">
                                <a href="#"><div class="block-title" onclick="toggle_visibility('bb8');">Limit Order</div></a>
                                <div class="block-body" id="bb8">
                                Buy/sell at a user specified price or better.
                                <hr>
                                May not get executed immediately.
                                <br>Will get executed only when the Current Price crosses the specified limit price.
                                <hr>
                                    Eg. If for XYZ, price = $40, then the Limit Buy order with the limit $35 will execute as soon as the Current Price becomes <$35. 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>


<?php



?>
