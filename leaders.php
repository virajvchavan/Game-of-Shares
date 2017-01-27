<!DOCTYPE html>
<html lang="en">

<?php
include "classes.inc.php";
include "conn.inc.php";
include "functions.index.php"; 
           
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
                font-size: 14px;
            }
            
            #golden{
                background: gold;
            }    
            #silver{
                background: silver;
            }
            #bronz{
                background: #CD7F32;
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
                    <a href="leaders.php" class="active">LeaderBoard</a>
                </li>
                <br><br><br><br><br><br><br><br><br><br>
                <li>
                    <a href="help.php">Help</a>
                </li>
                <li>
                    <a href="about.php">About</a>
                </li>
                <li>
                    <a href="user_password.php">Change Password</a>
                </li>
                <li>
                    <a data-toggle="modal" data-target="#restartModal">Restart Game</a>
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
                        <br>
                        <table class="table-fill">
                            <thead>
                            <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Balance</th>
                            <th>Stocks Valuation</th>
                            <th>Total</th>
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                
                                <?php
                                
                                $array_sorted = array();
                                
                                $query = "SELECT * FROM users ORDER BY balance DESC";
                                
                                if($run = mysqli_query($conn, $query))
                                {
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "<tr><td colspan='5'>No users to show</td></tr>";
                                    }
                                    else
                                    {
                                        
                                        //for each user
                                        while($array = mysqli_fetch_assoc($run))
                                        {
                                            $user_id = $array['id'];
                                            $fname = $array['first_name'];
                                            $lname = $array['last_name'];
                                            $balance = $array['balance'];
                                            
                                            //get user's shares and valuate them
                                            $total_valuation = 0;
                                            $valuation_in_shares = 0;
                                            
                                            $query_shares = "SELECT * FROM shares WHERE user_id =$user_id";
                                
                                            if($run_shares = mysqli_query($conn, $query_shares))
                                            {
                                                if(mysqli_num_rows($run_shares) < 1)
                                                {
                                                   $valuation_in_shares = 0;
                                                }
                                                else
                                                {
                                                    //for each company
                                                    while($array_shares = mysqli_fetch_assoc($run_shares))
                                                    {
                                                        $company_id = $array_shares['company_id'];
                                                        $quantity = $array_shares['quantity'];
                                                        
                                                        $query_company_price = "SELECT price FROM companies WHERE id = $company_id";
                                                        
                                                        $getPrice = mysqli_fetch_assoc(mysqli_query($conn, $query_company_price));
                                                        $company_price = $getPrice['price'];
                                                        
                                                        $shares_value = $quantity*$company_price;
                                                        
                                                        $valuation_in_shares += $shares_value;
                                                        
                                                    }
                                                    
                                                }
                                            }
                                            $total_valuation = $balance + $valuation_in_shares;
                                            
                                            
                                            $array['total'] = $total_valuation;
                                            $array['stock_v'] = $valuation_in_shares;
                                            
                                            //push all that data to a new array so that we can sort it according to the newly calculated  total valuation
                                            array_push($array_sorted, $array);
                                                         
                                        }
                                    }
                                }
                                
                                //needed for usort()
                                function sortByTotal($a, $b)
                                {
                                    $a = $a['total'];
                                    $b = $b['total'];

                                    if ($a == $b)
                                    {
                                        return 0;
                                    }

                                    return ($a > $b) ? -1 : 1;
                                }
                                
                                //sort by total_valuation
                                usort($array_sorted, 'sortByTotal');
                                
                                $no = 0;
                                
                                
                                //now print the leaderboard table rows
                                foreach($array_sorted as $key=>$value)
                                {
                                    $no++;
                                    
                                    $id = $value['id'];
                                    $fname = $value['first_name'];
                                    $lname = $value['last_name'];
                                    $balance = $value['balance'];
                                    $total = $value['total'];
                                    $stock_v = $value['stock_v'];
                                    
                                    if($no == 1)
                                        $color = "golden";
                                    elseif($no == 2)
                                        $color = "silver";
                                    elseif($no == 3)
                                        $color = "bronz";
                                    else
                                        $color = "";
                                    
                                    echo "<tr>
                                            <td id='$color'>".$no."</td>
                                            <td id='$color'>$fname $lname</td>
                                            <td>$balance</td>
                                            <td>$stock_v</td>
                                            <td>$total</td>
                                    </tr>";
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

</body>

</html>


<?php



?>
