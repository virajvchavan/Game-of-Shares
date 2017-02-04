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

//change user's consent about showing info of his stocks to others
if(isset($_POST['consent']) && !empty($_POST['consent']))
{
    $new_consent = $_POST['consent'];
    if($user->set_consent($conn, $new_consent))
        echo "<div id='note'>Changes saved!<a id='close' class='pull-right'>[Close]</a></div>";
    //mysqli_query($conn, "UPDATE users SET consent = '$new_consent' WHERE id = '$user->id'");
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
    
if(isset($_GET['id']) && !empty($_GET['id']))
{
    $profile_id = $_GET['id'];   

}
else
{
    $profile_id = $user->id;
}
    
    $query = "SELECT * FROM users WHERE id = $profile_id";
    if($run = mysqli_query($conn, $query))
    {
        if(mysqli_num_rows($run) != 1)
        {
            header("Location:index.php");
        }
        while($array = mysqli_fetch_assoc($run))
        {
            $profile_f_name = $array['first_name'];
            $profile_l_name = $array['last_name'];
            $profile_highest_rank = $array['highest_rank'];
            $profile_rank = $array['rank'];
            $profile_balance = $array['balance'];
            
            $profile = new User($profile_id, $conn);
            
            $profile_valuation = $profile->get_valuation($conn);
            
            if($profile_highest_rank == 1000)
                $profile_highest_rank = $profile_rank;
    
            $profile_consent = $array['consent'];
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
            h3{
                color: #004D40;
            }
            
            #name{
                background-color: #262626;
                color: white;
            }
            #user_shares{
                 background-color: white;
                color: #262626;
                padding: 8px;
                font-size: 16px;
                border-radius: 7px;
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
                    <a href="profile.php" <?php if($profile_id == $user->id) echo "class='active'"?>>Your Profile</a>
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

        
        <?php include "fb_inc.php";  ?>
        
       
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                        
                        echo "<div id='name' class='jumbotron'>
                                <div class='pull-left' id='user_shares'>Current Rank: $profile_rank</div>
                                <div class='pull-right' id='user_shares'>Highest Rank: $profile_highest_rank</div>
                                <hr>
                                <div class='page-header'>
                                 <h1>$profile_f_name $profile_l_name</h1>
                              </div>";
                        if($profile_id == $user->id)
                              echo "<a href='account.php' class='btn btn-primary pull-right'>Account Settings</a>";
                             echo "</div>";
                        
                        
                        ?>
                        
                        <div class='col-sm-4'>
                                    <div class='panel panel-primary'>
                                        <div class='panel-heading'>Balance</div>
                                        <div class='panel-body'><?php echo number_format($profile_balance);?></div>
                                    </div>
                                </div>
                                    
                                <div class='col-sm-4'>
                                    <div class='panel panel-primary'>
                                        <div class='panel-heading'>Valuation in Stocks</div>
                                        <div class='panel-body'><?php echo number_format($profile_valuation);?></div>
                                    </div>
                                </div>
                                
                                <div class='col-sm-4'>               
                                    <div class='panel panel-primary'>
                                        <div class='panel-heading'>Total Valuation</div>
                                        <div class='panel-body'><?php echo number_format($profile_valuation + $profile_balance); ?></div>
                                    </div>                                
                                </div>
                    
                    <!--Show This Users Shares-->
                    <?php
                    //show only if it is allowed
                    if(($profile_id == $user->id) || ($profile_consent == "yes"))
                    {
                        
                    ?>    
                    <div class="col-lg-6">
                    <div class="table-title">
                        <h3><?php echo "$profile_f_name's Stocks"?></h3>
                        <?php
                        
                        //show an form for consent to the logged in user only
                        if($profile_id == $user->id)
                        {?>
                            <form action="profile.php" method="post" class="form-inline">
                                Show this to Other Users:
                                &nbsp;&nbsp;&nbsp;
                                <div class="form-group">
                                    <div class="radio">
                                        <label class="radio-inline"><input type="radio" name="consent" value="yes" <?php if($profile_consent == "yes") echo "checked='checked'"; ?> >Yes</label>
                                    </div>
                                </div>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <div class="form-group">
                                    <div class="radio">
                                        <label class="radio-inline"><input type="radio" name="consent" value="no" <?php if($profile_consent == "no") echo "checked='checked'"; ?>>No</label>
                                    </div>
                                </div>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <small>
                                    <span class="btn-group">
                                        <button class="btn btn-mini btn-success" type="submit">Change</button>
                                    </span>
                                </small>
                            </form>
                            <br>
                        <?php         
                        }
                        
                        ?>
                            
                        
                    </div>
                <table class="table-fill">
                            <thead>
                            <tr>
                            <th>Company</th>
                            <th>Quantity</th>
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                <?php
                                
                                $query = "SELECT * FROM shares WHERE user_id =$profile_id";
                                
                                if($run = mysqli_query($conn, $query))
                                {                                   
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "<tr><td colspan='2'>$profile_f_name does not own any Stocks.</td></tr>";
                                    }
                                    else
                                    {
                                        
                                        while($array = mysqli_fetch_assoc($run))
                                        {
                                            
                                            $company_id = $array['company_id'];
                                            $quantity = $array['quantity'];
                                            $company = new Company($company_id);
                                            $company_name = $company->get_company_name($conn); 
                                            $abbr = $company->get_abbr($conn);
                                            
                                            if($quantity > 0)
                                            {
                                                echo "<tr>
                                                    <td><a href='company.php?id=$company_id'>$company_name ($abbr)</a></td>";?>
                                
                                                    <td><a>
                                                    <?php echo "$quantity</div></a></td>
                                                 </tr>";
                                                
                                            }
                                            
                                        }
                                    }
                                }
                              
                                ?>
                           
                            </tr>
                        </table>
                </div>
                    
                    <?php
                    }//the users share table block ends here
                     else
                     {                        
                         echo "<h4  style='padding:25px;'>$profile_f_name doesn't want you to see his stocks.</h4>";
                     }   
                    ?>
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
