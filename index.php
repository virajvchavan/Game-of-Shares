<!DOCTYPE html>
<html lang="en">
<?php
include "classes.inc.php";
include "conn.inc.php";
include "functions.index.php";    

    
//registering the user    
if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['password']))
{
    if(register($conn, ($_POST['first_name']), ($_POST['last_name']), ($_POST['email']), ($_POST['phone']), md5(($_POST['password']))))
    {
        //registration successful
    }
    else
    {
        echo "Registration error";
        //might wanna refresh
    }
}
    
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

//change the share price of companies (from functions.index.php)
changePrices($conn);

    
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
    <meta name="viewport" content="width=device-width, shrink-to-fit=no, initial-scale=1">
    <meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Game Of Shares</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sidebar.css" rel="stylesheet">
    <link href="css/table.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
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
    </script>

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
                    <a href="index.php"  class="active">Place Order</a>
                </li>
                <li>
                    <a href="owned.php">Your Shares</a>
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
                 
                
              <div class="col-md-5">
    <div class="form-area">  
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
                                                                                
                                        echo "<option value='$company_id'><b>$company_abbr</b> ($$company_stock_price) </option>";

                                    }
                                }
                        }
                        
                        ?>
                        
                      </select>
                    </div>

					<div class="form-group">
						<input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" required>
					</div>
            
                    <div class="form-group">
                    <label class="radio-inline"><input type="radio" id="market" name="limit_or_market" value="market" required>Market</label>
                    <label class="radio-inline"><input type="radio" id="limit" name="limit_or_market" value="limit" required>Limit</label>
                    </div>   
            
                    <div class="form-group">
						<input type="number" class="form-control" id="limit_price" name="limit_price" placeholder="Limit Price" id="required_later">
					</div>
                    <input type="submit" id="submit" name="Submit" class="btn btn-primary pull-right">
                    </div>
            
        
        </form>
                  
    </div>
                
                <div class="col-md-5 pull-right">
                    <div class="row">
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
