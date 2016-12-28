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
    
//execute orders for logged in user
$message = $user->executeOrders($conn);
if($message != "")
{
    echo "<div id='note'>$message<a id='close' class='pull-right'>[Close]</a></div>";
}
    
?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
                    Balance: <?php echo $user->get_balance(); ?>
                </li>
                <li>
                    <a href="index.php">Place Order</a>
                </li>
                <li>
                    <a href="owned.php" class="active">Your Shares</a>
                </li>
                <li>
                    <a href="orders.php">Pending Orders</a>
                </li>
                <li>
                    <a href="trades.php">Trade Book</a>
                </li>
                <li>
                    <a href="help.php">Help</a>
                </li>
                <li>
                    <a href="about.php">About</a>
                </li>
                <li>
                    <a href="logout.php">Logout (<?php echo $user->get_name(); ?>)</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        
                        <table class="table-fill">
                            <thead>
                            <tr>
                            <th>Company</th>
                            <th>Quantity</th>
                            <th>Current Price</th>
                            <th>High</th>
                            <th>Low</th>
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                <?php
                                
                                $query = "SELECT * FROM shares WHERE user_id =".$user->get_id();
                                
                                if($run = mysqli_query($conn, $query))
                                {
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "<tr><td colspan='6'>You do not own any shares</td></tr>";
                                    }
                                    else
                                    {
                                        while($array = mysqli_fetch_assoc($run))
                                        {
                                            $company_id = $array['company_id'];
                                            $quantity = $array['quantity'];
                                            $company = new Company($company_id);
                                            $company_name = $company->get_company_name($conn);
                                            $company_price = $company->get_company_price($conn);
                                            $high = $company->get_high_price($conn);
                                            $low = $company->get_low_price($conn);
                                            
                                            
                                            echo "<tr>
                                                    <td><a href='company.php?id=$company_id'>$company_name</a></td>
                                                    <td>$quantity</td>
                                                    <td>$company_price</td>
                                                    <td>$high</td>
                                                    <td>$low</td>
                                                 </tr>";
                                            
                                        }
                                    }
                                }
                              
                                ?>
                           
                            </tr>
                        </table>
                       
                       
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->

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
