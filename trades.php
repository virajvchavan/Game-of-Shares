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
    
?>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Game Of Shares</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/simple-sidebar.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">
    
    <style>
        #balance{
            color: #1b1c1b;
            font-size: 25px;
            background-color: #7f8989;
            padding-bottom: 6px;
            padding-top: 6px;
            margin: 8px;
        }
         .active{
            background-color: #263238 
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
                    Balance : <?php echo $user->get_balance(); ?>
                </li>
                <li>
                    <a href="index.php">Place Order</a>
                </li>
                <li >
                    <a href="owned.php">Your Shares</a>
                </li>
                <li>
                    <a href="orders.php" >Pending Orders</a>
                </li>
                <li>
                    <a href="trades.php" class="active">Trade Book</a>
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
                 <a href="#menu-toggle" class="btn btn-default pull-right" id="menu-toggle">Toggle Menu</a>
                <div class="row">
                    <div class="col-lg-12">
                        <table class="table-fill">
                            <thead>
                            <tr>
                            <th class="text-left">Time</th>
                            <th class="text-left">Buy/Sell</th>
                            <th class="text-left">Company</th>
                            <th class="text-left">Quantity</th>
                            <th class="text-left">Price</th>
                            <th class="text-left">Total</th>
                            <th class="text-left">Balance</th>
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                <?php
                                
                                $query = "SELECT * FROM transactions WHERE user_id =".$user->get_id();
                                
                                if($run = mysqli_query($conn, $query))
                                {
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "<tr><td colspan='7'>No transactions to show</td></tr>";
                                    }
                                    else
                                    {
                                        $balance = 5000;
                                        while($array = mysqli_fetch_assoc($run))
                                        {
                                            $company_id = $array['company_id'];
                                            $quantity = $array['quantity'];
                                            $time = $array['time'];
                                            $price = $array['price'];
                                            $company = new Company($company_id);
                                            $company_name = $company->get_company_name($conn);
                                            $company_price = $company->get_company_price($conn);
                                            
                                            if($company_price > $price)
                                                $change = "Up";
                                            elseif($company_price < $price)
                                                $change = "Down";
                                            else
                                                $change = " ";
                                            
                                            $balance -= $quantity*$price;
                                            echo "<tr>
                                                    <td class='text-left'>$time</td>
                                                    <td class='text-left'>";
                                                    if($quantity > 0)
                                                        echo "Buy";
                                                    elseif($quantity < 0)
                                                        echo "Sell";
                                            
                                                    echo "</td>
                                                    <td class='text-left'>$company_name</td>
                                                    <td class='text-left'>".abs($quantity)."</td>
                                                    <td class='text-left'>$price  $change</td>
                                                    <td class='text-left'>".abs($quantity*$price)."</td>
                                                    <td class='text-left'>$balance</td>
                                                 </tr>";
                                            
                                        }
                                    }
                                }   
                                
                                ?>
                            </tbody>
                        </table>
                       
                       
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

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    </script>

</body>

</html>
