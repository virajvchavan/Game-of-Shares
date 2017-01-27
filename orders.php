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


//place the order    
if(isset($_POST['buysell']) && isset($_POST['company_id']) && isset($_POST['quantity']) && isset($_POST['limit_or_market']) && isset($_POST['limit_price']))
{
    //call the function to place the order
    if(!$user->placeOrder($conn, $_POST['buysell'], $_POST['company_id'], $_POST['quantity'], $_POST['limit_or_market'], $_POST['limit_price']))
    {
        header("refresh:0,index.php");
    }
    
    if($_POST['limit_or_market'] == 'limit')
    {
        if($_POST['buysell'] == "buy")
            $person = "Seller";
        else
            $person = "Buyer";
        echo "<div id='note'>Order will be executed once a $person is available at price ". $_POST['limit_price']."<a id='close' class='pull-right'>[Close]</a></div>";
    }
}


//delete an order
if(isset($_POST['delete_id']) && !empty($_POST['delete_id']))
{
    $order = new Order($_POST['delete_id']);
    $order->delete_order($conn);
    
    echo "<div id='note'>Order Deleted<a id='close' class='pull-right'>[Close]</a></div>";

}
    
//edit the limit price
if(isset($_POST['edit_id']) && !empty($_POST['edit_id']) && isset($_POST['new_price']) && !empty($_POST['new_price']))
{
    $order = new Order($_POST['edit_id']);
    $order->edit_order($conn, $_POST['new_price']);
    
    echo "<div id='note'>Limit Price Changed to ".$_POST['new_price']."<a id='close' class='pull-right'>[Close]</a></div>";
}

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
                    <a href="orders.php" class="active">Pending Orders</a>
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
                        <table class="table-fill">
                            <thead>
                            <tr>
                            <th>Time</th>
                            <th>Buy/Sell</th>
                            <th>Company</th>
                            <th>Quantity</th>
                            <th>Type</th>
                            <th>Limit Price</th>
                            <th>Current</th>
                            <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                
                                <?php
                                
                                $query = "SELECT * FROM orders WHERE user_id =".$user->get_id();
                                
                                if($run = mysqli_query($conn, $query))
                                {
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "<tr><td colspan='8'>No pending orders to show</td></tr>";
                                    }
                                    else
                                    {
                                        while($array = mysqli_fetch_assoc($run))
                                        {
                                            $order_id = $array['id'];
                                            $company_id = $array['company_id'];
                                            $quantity = $array['quantity'];
                                            $type = $array['type'];
                                            $time = $array['time'];
                                            $limit_or_market = $array['limit_or_market'];
                                            $limit_price = $array['limit_price'];
                                            
                                            $company = new Company($company_id);
                                            $company_name = $company->get_company_name($conn);
                                            $company_price = $company->get_company_price($conn);
                                            
                                            
                                            echo "<tr>
                                                    <td>".$time."</td>
                                                    <td>".ucfirst($type)."</td>
                                                    <td><a href='company.php?id=$company_id'>$company_name</a></td>
                                                    <td>$quantity</td>
                                                    <td>".ucfirst($limit_or_market);
                                            
                                            ?>
                                
                                            <script>
                                                    function deleteOrder()
                                                        {

                                                            if(confirm("Sure to Cancel the order?"))
                                                            {
                                                               return true;
                                                            }
                                                            else
                                                               return false;
                                                        }

                                            </script>
                                
                                <?php
                                            echo "</td>
                                                    <td>
                                                    <form method='post' action='orders.php' onsubmit='return editOrder()'>
                                                    <input type='text' value='$order_id' name='edit_id' hidden>
                                                    <input type='text' name='new_price' class='input-sm col-sm-3' value='$limit_price'>
                                                    &nbsp;&nbsp;
                                                    <input type='submit' class='btn btn-primary btn-sm' value='Change'>
                                                    </form>
                                                    </td>
                                                    
                                                    <td>$company_price</td>
                                                    <td>
                                                    <form method='post' action='orders.php' onsubmit='return deleteOrder()'>
                                                    <input type='text' value='$order_id' name='delete_id' hidden>
                                                    <input type='submit' class='btn btn-danger btn-sm' value='Cancel'>
                                                    </form>
                                                    </td>
                                                  
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


<?php



?>
