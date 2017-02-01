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
$user->checkMessages($conn);

//execute orders for logged in user
if($session_db != "off")   
{ 
    $message = $user->executeOrders($conn);
    if($message != "")
    {
        echo "<div id='note'>$message<a id='close' class='pull-right'>[Close]</a></div>";
    }
}

//save feedback in db
if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['feedback']) && !empty($_POST['feedback']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $feedback = $_POST['feedback'];
    
    $query = "INSERT INTO feedback(name, email, feedback) VALUES('$name','$email','$feedback')";
    if(mysqli_query($conn, $query))
    {
        echo "<div id='note'>Thank you for your Feedback. Keep rocking!</div>";
    }
    else
        echo "Problemo feedback";
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
            h3{
                color: #004D40;
            }
            .form-area
            {
                background-color: #FAFAFA;
                padding: 10px 40px 60px;
                margin: 10px 0px 60px;
                border: 1px solid GREY;
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
                    <a href="about.php">About</a>
                </li>
                <li>
                    <a href="account.php">Account Settings</a>
                </li>
                <li>
                    <a href="feedback.php" class="active">Feedback</a>
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
                    <div class="col-lg-2"></div>
                    <div class="col-lg-8">
                        <div class="form-area">
                        <form role="form" method="post" action="feedback.php">
            <br style="clear:both">
                    <h3 style="margin-bottom: 25px; text-align: center;">Send Us Feedback!</h3>
                            <br>
					<div class="form-group">
                        <div class="row">
                            
						  <div class="col-xs-2">Name:</div> 
                           <div class="col-xs-10"> <input type="text" class="form-control" name="name" placeholder="Leave blank to be Anonomous"></div>
                        </div>
					</div>
                    <div class="form-group">
                        <div class="row">
                           
						  <div class="col-xs-2">Email:</div> 
                           <div class="col-xs-10"> <input type="email" class="form-control" name="email" placeholder="Leave blank to be Anonomous"></div>
                        </div>
					</div>
                    <div class="form-group">
                        <div class="row">
                           
						  <div class="col-xs-2">Feedback:</div> 
                            <div class="col-xs-10"> 
                                <textarea class="form-control" name="feedback" placeholder="Your thoughts on 'Game Of Shares'" required cols="20" rows="6"></textarea></div>
                        </div>
					</div>
                            <br>
                        <div class="row">
                        <div class="col-xs-10"></div>
                            <input type="submit" class="btn btn-primary">
                        </div>
                            
                        </form>
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
