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
        .form-area
            {
                background-color: #FAFAFA;
                padding: 10px 40px 60px;
                margin: 10px 0px 60px;
                border: 1px solid GREY;
                padding: 20px;
                margin: 20px;
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
                <br><br><br><br><br><br><br><br><br><br>
                <li>
                    <a href="help.php">Help</a>
                </li>
                <li>
                    <a href="about.php">About</a>
                </li>
                <li>
                    <a href="account.php" class="active">Account Settings</a>
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
                
                <a data-toggle="modal" data-target="#restartModal" class="btn btn-danger btn-lg">Restart Game</a>
                <br><br>
                
                <h3>Change Password</h3>
                <div class="row">
                <!-- The form for changing password -->
                <div class="form-area" class="col-xs-4">
                        <form action="index.php" method="post" onsubmit="return validateForm()" name="password">
                            <div class="row form-group">
                                <label for="current_p" class="col-xs-2">Current Password</label>
                                <div class="col-xs-10"><input type="password" class="form-control" name="current_p" required>
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="new_p" class="col-xs-2">New Password:</label>
                                <div class="col-xs-10"><input type="password" class="form-control" name="new_p" required></div>
                            </div>
                            <div class="row form-group">
                                <label for="price" class="col-xs-2">Retype New Password</label>
                                <div class="col-xs-10"><input type='password' class="form-control" name="new_confirm_p" required></div>
                            </div>
                                <input type="submit" class="btn btn-success pull-right" value="Change Password">
                        </form>
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

<!-- Modal -->
<div id="restartModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Are you sure?</h4>
      </div>
      <div class="modal-body">
        <p>All your current data will be erased.<hr> All your shares will be gone.<hr> Your balance will be set to 500000.<hr>It is not recoverable.</p>
      </div>
      <div class="modal-footer">
        <form method="post" action="index.php"><input type="text" name="restart" value="yes" hidden><input type="submit" class="btn btn-danger" value="Yes. I know what I'm doing!">
        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>  
        </form>
        
      </div>
    </div>

  </div>
</div>


<?php



?>
