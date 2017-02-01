<!DOCTYPE html>
<html lang="en">

<?php
include "classes.inc.php";
include "conn.inc.php";
        
//leave if not logged in
if(!isLoggedIn())
{
    header("Location:login.php");
}

//change the share price of companies (from functions.index.php)    
if($session_db != "off")    
    changePrices($conn, $time_limit_for_company, $price_limit_for_company);
    
//check for any messages    
//$user->checkMessages($conn);

//execute orders for logged in user
if($session_db != "off")   
{ 
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
            }
           #links_bottom{
    position: fixed;
    z-index: 101;
    bottom: 0;
    left: 250px;
    right: 0;
    background: #fde073;
    text-align: center;
    line-height: 3;
    overflow: hidden; 
    -webkit-box-shadow: 0 0 5px black;
    -moz-box-shadow:    0 0 5px black;
    box-shadow:         0 0 5px black;
}
            h3{
                color: #004D40;
            }
    
    </style>

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
                    <a href="help.php">Help</a>
                </li>
                <li>
                    <a href="about.php" class="active">About</a>
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
                        <div id="links_bottom">
                        Contact/Feedback: &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="http://github.com/virajvchavan" target='_blank'>Github</a>
                        &nbsp;&nbsp;&nbsp;<a href="http://linkedin.com/in/virajvchavan" target='_blank'>LinkedIn</a>
                        &nbsp;&nbsp;&nbsp;<a href="http://facebook.com/virajvchavan" target='_blank'>Facebook</a>
                        &nbsp;&nbsp;&nbsp;Call: 8975201655    
                        </div>
                        
                        <div id="note">Love solving puzzles? Visit <a href="http://puzzlepedia.esy.es/" target='_blank'>PuzzlePedia</a> to play now!</div>
                        
                        <br>
                        <h3>Devepoled as a Mini Project by:</h3>
                        <h4>
                            <ul>
                                <li>Shubham Hapse</li>
                                <li>Viraj Chavan &nbsp;&nbsp;&nbsp;
                                </li>
                                <li>Varun Maheshwari</li>
                            </ul>
                        </h4>
                        <hr>
                        <h3>
                            Under the guidance of:</h3> <h4><ul><li>Mr. Mayur Rathi<br><br> Department of Information Technology,<br>
                        Walchand College of Engineering, Sangli.<br></li></ul></h4>
                       
                        <hr>
                        
                        <h3>Concept by:</h3>
                        <h4><ul><li>Chani Bhate<br><br>
                            Chintamanrao Institute of Management Development And Research, 
                            <br>Sangli
                            </li></ul></h4><br>
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
