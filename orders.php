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
    
//edit the limit price and quantity
if(isset($_POST['edit_id']) && !empty($_POST['edit_id']) && isset($_POST['new_price']) && !empty($_POST['new_price']))
{
    $order = new Order($_POST['edit_id']);
    $order->edit_order($conn, $_POST['new_price'], $_POST['new_quantity']);
    
    echo "<div id='note'>Changes Saved!<a id='close' class='pull-right'>[Close]</a></div>";
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
                <li id="balance" style="font-size: 19px;">
                    Total Value: <?php echo number_format($user->get_valuation($conn) + $user->balance)  ; ?>
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
                <br><br><br><br><br><br><br>
                <li>
                    <a href="help.php">Help</a>
                </li>
                <li>
                    <a href="about.php">About</a>
                </li>
                <li>
                    <a href="profile.php">Your Profile</a>
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
                                //just to solve a little bug with mobile screens
                                if(detectMobile())
                                {
                                    $input_width = "12";
                                }
                                else
                                {
                                    $input_width = "6";
                                }

                                
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
                                            
                                            //for converting GMT time to IST, add this to GMT (19800)
                                            $GMT_to_IST = 19800;
                                            
                                            $company = new Company($company_id);
                                            $company_name = $company->get_company_name($conn);
                                            $company_price = $company->get_company_price($conn);
                                            
                                            
                                            echo "<tr>
                                                    <td>".date("Y-m-d H:i:s", strtotime($time) + $GMT_to_IST)."</td>
                                                    <td>".ucfirst($type)."</td>
                                                    <td><a href='company.php?id=$company_id'>$company_name</a></td>
                                                     <td> <form method='post' action='orders.php'>
                                                            <input type='text' value='$order_id' name='edit_id' hidden>
                                                            <input type='number' name='new_quantity' class='input-sm col-xs-$input_width' value=\"$quantity\" min='1'>
                                                    </td>
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
                                                    <input type='text' name='new_price' class='input-sm col-xs-$input_width' value=\"$limit_price\" min='1'>
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
function detectMobile()
{
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
        return true;
    else
        return false;
}

?>
