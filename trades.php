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
    
    <!-- Custom CSS -->
    <link href="css/sidebar.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">

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
                <div class="row">
                    <div class="col-lg-12">
                        <br>
                        <table class="table-fill">
                            <thead>
                            <tr>
                            <th>Time</th>
                            <th>Buy/Sell</th>
                            <th>Company</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Balance</th>
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                <?php
                                $user_id = $user->id;
                                $query = "SELECT * FROM transactions WHERE user_id ='$user_id' ORDER BY time";
                                
                                if($run = mysqli_query($conn, $query))
                                {
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "<tr><td colspan='7'>No transactions to show</td></tr>";
                                    }
                                    else
                                    {
                                        //set the initial balance
                                        //IMPORTANT: This has to be same as the one stored in the database
                                        $balance = floatval(500000);
                                        
                                        while($array = mysqli_fetch_assoc($run))
                                        {
                                            $company_id = $array['company_id'];
                                            $quantity = $array['quantity'];
                                            $time = $array['time'];
                                            $price = $array['price'];
                                            $company = new Company($company_id);
                                            $company_name = $company->get_company_name($conn);
                                            $company_price = $company->get_company_price($conn);
                                            
                                            //for converting GMT time to IST, add this to GMT (19800)
                                            $GMT_to_IST = 19800;
                                            
                                            
                                            $balance -= ($quantity*floatval($price));
                                            
                                            echo "<tr>
                                                    <td>".date("Y-m-d H:i:s", strtotime($time) + $GMT_to_IST)."</td>
                                                    <td>";
                                                    if($quantity > 0)
                                                    {
                                                        echo "Buy";
                                                        $class = "red";
                                                    }
                                                    elseif($quantity < 0)
                                                    {
                                                        echo "Sell";
                                                        $class = "green";
                                                    }
                                            
                                                    echo "</td>
                                                    <td><a href='company.php?id=$company_id'>$company_name</a></td>
                                                    <td>".abs($quantity)."</td>
                                                    <td>$price</td>
                                                    <td class='$class'>";
                                                    if($quantity < 0)
                                                        echo "+";
                                                    echo number_format(-$quantity*$price)."</td>
                                                    <td>".number_format($balance)."</td>
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
    
    <script>
 close = document.getElementById("close");
 close.addEventListener('click', function() {
   note = document.getElementById("note");
   note.style.display = 'none';
 }, false);
</script>

</body>

</html>
