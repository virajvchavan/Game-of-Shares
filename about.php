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
    <script src="https://use.fontawesome.com/8754a9ba67.js"></script>
    
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
            #block{
                background: #004D40;
                padding: 5px;
                margin: 10px;
                color: white;
                border-radius: 5px;
                width: 500px;
            }
            #block-title{
                color: #004D40;
                background: white;
                padding: 3px;
            }
    
    </style>
    


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li id="balance">
                    Balance: <?php echo number_format($user->get_balance($conn)); ?>
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
                <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                <li>
                    <a href="help.php">Help</a>
                </li>
                <li>
                    <a href="about.php" class="active">About</a>
                </li>
                <li>
                    <a href="user_password.php">Change Password</a>
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
                       
                            <div id="block">
                                <div id="block-title">
                                    <h2>Organized By:</h2>
                                </div>
                                
                                    <ul>
                                        <li><h4>Chani Bhate</h4></li>
                                        <li><h4>Purnima Iyer</h4></li>
                                        <li><h4>Prajakta Ghuli</h4></li>
                                        <li><h4>Sorabh Suryavanshi</h4></li>
                                    </ul>
                            </div>
                            <br>                          
 
                            <div id="block">
                                <div id="block-title">
                                    <h2>Developed By:</h2>
                                </div>
                                <div class="pull-right" style="padding:5px; font-size:30px">
                                <a href="http://github.com/virajvchavan"><i class="fa fa-github" aria-hidden="true"></i></a>&nbsp;
                                    <a href="http://linkedin.com/in/virajvchavan"><i class="fa fa-linkedin" aria-hidden="true"></i></a>&nbsp;
                                    <a href="http://facebook.com/virajvchavan"><i class="fa fa-facebook" aria-hidden="true"></i></a>&nbsp;
                                </div>
                               <ul>
                                    <li><h4>Viraj Chavan</h4></li>
                                    <ul>
                                        <li><h5>TY IT</h5></li>
                                        <li><h5>WCE, Sangli</h5></li>
                                   </ul>
                               </ul>
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
