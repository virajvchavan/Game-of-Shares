<!DOCTYPE html>
<html lang="en">    
<?php
include "classes.inc.php";
include "conn.inc.php";
include "functions.index.php";    
   
//now start or close the market according to the current time
market_start_or_stop($conn);

//logging in the user
if(isset($_POST['email']) && isset($_POST['password']))
{
    if(login($conn, $_POST['email'], md5($_POST['password'])))
    {
        header("Location:index.php");
    }
    else
    {
        echo "<script>alert('Wrong Email/Password');</script>";
        header("refresh:0,index.php");
        
    }
}
    
//leave if not logged in
if(!isLoggedIn())
{
    if(!isset($_POST['email']))    
        header("Location:login.php");
}

//restart the game for logged in user
if(isset($_POST['restart']) && !empty($_POST['restart']))
{
    if($_POST['restart'] == "yes")
    {
        $user->restartGame($conn);
        
    }
}
    
    
//change the password for user   
if(isset($_POST['current_p']) && isset($_POST['new_p']) && isset($_POST['new_confirm_p']))
{
    $query = "SELECT password FROM users WHERE id = $user->id";
    if($run = mysqli_query($conn, $query))
    {
        $array = mysqli_fetch_assoc($run);
        
        $real_p = $array['password'];
    }
    
    if(md5($_POST['current_p']) != $real_p)
    {
        echo "<script>alert('Wrong current password.')</script>";
        header("refresh:0,url=user_password.php");       
    }
    $query_change_p = "UPDATE users SET password = '".md5($_POST['new_p'])."' WHERE id = '$user->id'";
    if(mysqli_query($conn, $query_change_p))
    {
         echo "<div id='note'>Password Changed Successfuly. <a id='close' class='pull-right'>[Close]&nbsp;</a></div>";
    }
    
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
    <meta name="description" content="Game of shares is a Share market game/Stock market game where users compete with each other to stay at the top of the leader board." />
    <meta name="keywords" content="stock market, share market, game, learn stocks, begginer" />
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
        .inner_table{
            width: 200px;
            font-size: 12px;
            display: none;
        }
        #limit_div{
            display: none;
        }
    
    </style>
    
    <script>
    function validateForm()
        {
            var limit_price = document.forms["order"]["limit_price"].value;
            var type = document.forms["order"]["limit_or_market"].value;
            
            if(type == "limit" && limit_price == "")
            {
                alert("Please enter the Limit Price");
                return false;
            }
            return true;
        }
        
        function toggle_visibility(id) 
        {
           var e = document.getElementById(id);
           if(e.style.display == 'block')
              e.style.display = 'none';
           else
              e.style.display = 'block';
        }
        
        function show_limit(id)
        {
           var e = document.getElementById(id);
           e.style.display = 'block';
        }
        
        function hide_limit(id)
        {
           var e = document.getElementById(id);
           e.style.display = 'none';
        }
    </script>

</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <?php include "sidebar_nav_inc.php"; ?>
        </div>
        <!-- /#sidebar-wrapper -->
        
        <?php include "fb_inc.php";  ?>
         
        <style>   
            .red{
                color:red;
                }
            .form-area
            {
                background-color: #FAFAFA;
                padding: 10px 40px 60px;
                margin: 10px 0px 60px;
                border: 1px solid GREY;
                }
        </style>
        
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                 <br>
                
        <div class="col-md-5">
        <div class="form-area">
            <!-- Form for placing order -->
            <form role="form" method="post" action="orders.php" onsubmit="return validateForm()" name="order">
            <br style="clear:both">
                    <h3 style="margin-bottom: 25px; text-align: center;">Place Order</h3>
                    <div class="form-group">
                    <label class="radio-inline"><input type="radio" name="buysell" value="buy" required>Buy</label>
                    <label class="radio-inline"><input type="radio" name="buysell" value="sell" required>Sell</label>
                    </div>
            
                    <div class="form-group">
                      <label for="sel1">Select company:</label>
                        <select class="form-control" id="company" name="company_id" required>
                        <?php
                        
                        //get company data
                        $query_get_companies = "SELECT id, abbr, price FROM companies";

                        if($run_get_companies = mysqli_query($conn, $query_get_companies))
                            {
                                if(mysqli_num_rows($run_get_companies) >= 1)
                                {
                                    while($array = mysqli_fetch_assoc($run_get_companies))
                                    {
                                        $company_id = $array['id'];
                                        $company_abbr = $array['abbr'];
                                        $company_stock_price = $array['price'];
                                                                                
                                        echo "<option value='$company_id'><b>$company_abbr</b> ($company_stock_price) </option>";

                                    }
                                }
                        }
                        
                        ?>
                        
                      </select>
                    </div>

					<div class="form-group">
						<input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" required min="1">
					</div>
            
                    <div class="form-group">
                    <label class="radio-inline"><input type="radio" id="market" name="limit_or_market" value="market" required  onclick="hide_limit('limit_div');">Market</label>
                    <label class="radio-inline"><input type="radio" id="limit" name="limit_or_market" value="limit" required  onclick="show_limit('limit_div');">Limit</label>
                    </div>   
            
                    <div class="form-group" id="limit_div">
						<input type="text" class="form-control" id="limit_price" name="limit_price" placeholder="Limit Price" id="required_later">
					</div>
                    <input type="submit" id="submit" name="Submit" class="btn btn-primary pull-right" <?php if($session_db == "off") echo "disabled"; ?> >
                    </div>
            
        
        </form>
        </div>
          
                
        <div class="col-md-6 pull-right">
                <!-- Show shares owned by the user -->
                <div class="col-lg-12">
                    <div class="table-title">
                            <h3>Your Shares</h3>
                        </div>
                <table class="table-fill">
                            <thead>
                            <tr>
                            <th>Company</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                <?php
                                
                                $query = "SELECT * FROM shares WHERE user_id =".$user->get_id();
                                
                                if($run = mysqli_query($conn, $query))
                                {                                   
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "<tr><td colspan='3'>You do not own any shares</td></tr>";
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
                                            $company_price = $company->get_company_price($conn);
                                            
                                            if($quantity > 0)
                                            {
                                                echo "<tr>
                                                    <td><a href='company.php?id=$company_id'>$company_name ($abbr)</a></td>";?>
                                
                                                    <td><a><div onclick="toggle_visibility('<?php echo $company_id; ?>');">
                                                    <?php    echo "$quantity <img src='https://cdn4.iconfinder.com/data/icons/simplicity-vector-icon-set/512/click.png' height='22' width='22'></div></a></td>
                                                    <td>$company_price</td>
                                                 </tr>";
                                                echo "<tr>
                                                <td colspan='2'>
                                                <table class='table-fill pull-right inner_table' id='$company_id'>
                                                            <thead>
                                                                <tr>
                                                                    <th>Buy/Sell</th>
                                                                    <th>Quantity</th>
                                                                    <th>Price</th>
                                                                </tr>
                                                                </thead>
                                                            <tbody class='table-hover'>";
                                            
                                                $query_inline = "SELECT quantity, price, time FROM transactions WHERE user_id =".$user->id." AND company_id = $company_id ORDER BY time DESC";
                                
                                                if($run_inline = mysqli_query($conn, $query_inline))
                                                {
                                                    echo "<tr><td colspan = '3'>In Latest First order</td></tr>";
                                                    
                                                    if(mysqli_num_rows($run_inline) < 1)
                                                    {
                                                        echo "<tr><td colspan='3'>No Past Transactions</td></tr>";
                                                    }
                                                    else
                                                    {

                                                        while($array_inline = mysqli_fetch_assoc($run_inline))
                                                        {

                                                            $quantity_inline = $array_inline['quantity'];
                                                            $time_inline = $array_inline['time'];
                                                            $price_inline = $array_inline['price'];   
                                                            
                                                            if($quantity_inline > 0)
                                                            {
                                                                $type_inline = "Buy";
                                                                $color = "green";
                                                            }
                                                            else
                                                            {
                                                                $type_inline = "Sell";
                                                                $color = "red";
                                                            }
                                                            
                                                            
                                                            echo "<tr>
                                                            <td class='$color'>$type_inline</td>
                                                            <td class='$color'>".abs($quantity_inline)."</td>
                                                            <td>$price_inline</td>
                                                            </tr>";
                                                            
                                                           
                                                        }
                                                        echo "</td></tr>";
                                                     
                                                    }
                                                    
                                                }
                                                   
                                                   echo  "</tbody></table>";
                                                
                                            }
                                            
                                        }
                                    }
                                }
                              
                                ?>
                           
                            </tr>
                        </table>
                </div>
                
                </div>
            
                <div class="col-md-12">
                    <div class="col-lg-12">
                        
                        <div class="table-title">
                            <h3>Stock Prices</h3>
                        </div>
                        <table class="table-fill">
                            <thead>
                            <tr>
                            <th>Company</th>
                            <th >Price</th>
                            <th>High</th>
                            <th>Low</th>
                           
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                          
                            <?php
                                
                                //get all the company names and their prices
                                $query = "SELECT * FROM companies";
                                
                                if($run = mysqli_query($conn, $query))
                                {
                                    if(mysqli_num_rows($run) >= 1)
                                    {
                                        
                                        while($array = mysqli_fetch_assoc($run))
                                        {
                                            $company_id = $array['id'];
                                            $company_name = $array['name'];
                                            $company_abbr = $array['abbr'];
                                            $company_price = $array['price'];
                                            $prev_price = $array['prev_price'];
                                            $high = $array['high'];
                                            $low = $array['low'];
                                            
                                            if($company_price > $prev_price)
                                            {
                                                $class = "green";
                                                $change = "&uarr;";
                                                
                                            }
                                            else
                                            {
                                                $change = "&darr;";
                                                $class = "red";
                                            }
                                            
                                            echo "<tr>";
                                            echo "<td><a href='company.php?id=$company_id'>$company_name </a>($company_abbr)</td>";
                                            echo "<td><span class='$class'>$company_price $change</span></td>
                                            <td>$high</td>
                                            <td>$low</td>";
                                            echo "</tr>";
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
