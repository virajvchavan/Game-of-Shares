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
    
//change the users details
if(isset($_POST['u_name']) && isset($_POST['u_l_name']) && isset($_POST['u_email']) && !empty($_POST['u_name']) && !empty($_POST['u_l_name']) && !empty($_POST['u_email']))
{
    $f_name = filter_var($_POST['u_name'], FILTER_SANITIZE_STRING);
    $l_name = filter_var($_POST['u_l_name'], FILTER_SANITIZE_STRING);
    $u_email = filter_var($_POST['u_email'], FILTER_SANITIZE_EMAIL);
    
    if (strlen($f_name) >= 0 && strlen(trim($f_name)) != 0 && strlen($l_name) >= 0 && strlen(trim($l_name)) != 0)
    {
        if($user->set_basic_info($conn, $f_name, $l_name, $u_email))
        {
            echo "<div id='note'>Changes Saved<a id='close' class='pull-right'>[Close]</a></div>";
        }
    }
    else
        echo "<div id='note'>Invalid First Name<a id='close' class='pull-right'>[Close]</a></div>";
}

//get logged in user details to show in the form 
//select all the data of user with id
    $query = "SELECT first_name, last_name, email FROM users WHERE id = $user->id";
    if($run = mysqli_query($conn, $query))
    {
        while($array = mysqli_fetch_assoc($run))
        {
            $first_name = $array['first_name'];
            $last_name = $array['last_name'];
            $email = $array['email'];
        }
    }
  
?>
    
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1024">
    <meta name="description" content="Game of shares is a Share market game/Stock market game where users compete with each other to stay at the top of the leader board." />
    <meta name="keywords" content="stock market, share market, game, learn stocks, begginer, simulation" />
    <meta name="author" content="Viraj Chavan"/>
    <meta name="robots" content="index, follow" />

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
            <?php include "sidebar_nav_inc.php"; ?>
        </div>
        <!-- /#sidebar-wrapper -->

        
        <?php include "fb_inc.php";  ?>
        
       
        <!-- Page Content -->
        <div id="page-content-wrapper">
            
            <div class="container-fluid">
                <h3>Change Profile Details</h3>
                <div class="row">
                <!-- The form for changing user details -->
                <div class="form-area" class="col-xs-4">
                        <form action="account.php" method="post">
                            <div class="row form-group">
                                <label for="u_name" class="col-xs-2">First Name</label>
                                <div class="col-xs-10"><input type="text" class="form-control" name="u_name" required value="<?php echo $first_name; ?>">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="u_l_name" class="col-xs-2">Last Name</label>
                                <div class="col-xs-10"><input type="text" class="form-control" name="u_l_name" required value="<?php echo $last_name; ?>">
                                </div>
                            </div>
                            <div class="row form-group">
                                <label for="u_email" class="col-xs-2">Email</label>
                                <div class="col-xs-10"><input type="email" class="form-control" name="u_email" required value="<?php echo $email; ?>">
                                </div>
                            </div>
                                <input type="submit" class="btn btn-success pull-right" value="Save">
                        </form>
                </div>
                    </div>
            </div>

            
            <div class="container-fluid">
                
                <br>
                
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
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

        <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
            
        <script>
         close = document.getElementById("close");
         close.addEventListener('click', function() {
           note = document.getElementById("note");
           note.style.display = 'none';
         }, false);
    </script>
</body>

</html>


<?php



?>
