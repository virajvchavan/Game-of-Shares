<!DOCTYPE html>
<html lang="en">
<?php
include "classes.inc.php";
include "conn.inc.php";
include "reg_login_fun.inc.php";    

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
    
//loggin in the user
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
    <link href="css/index.css" rel="stylesheet">
    
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
                <li class="active">
                    <a href="index.php">Place Order</a>
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
        <form role="form">
        <br style="clear:both">
                    <h3 style="margin-bottom: 25px; text-align: center;">Place Order</h3>
                    <div class="form-group">
                    <label class="radio-inline"><input type="radio" name="buysell" value="buy">Buy</label>
                    <label class="radio-inline"><input type="radio" name="buysell" value="sell">Sell</label>
                    </div>
            
                    <div class="form-group">
                      <label for="sel1">Select company:</label>
                      <select class="form-control" id="company">
                        <option>Company 1</option>
                        <option>Company 2</option>
                      </select>
                    </div>

					<div class="form-group">
						<input type="number" class="form-control" id="quantity" name="quantity" placeholder="Quantity" required>
					</div>
            
                    <div class="form-group">
                    <label class="radio-inline"><input type="radio" name="limit" value="market">Market</label>
                    <label class="radio-inline"><input type="radio" name="limit" value="limit">Limit</label>
                    </div>   
            
                    <div class="form-group">
						<input type="number" class="form-control" id="limit_price" name="limit_price" placeholder="Limit Price">
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
                            <th class="text-left">Company</th>
                            <th class="text-left">Price</th>
                           
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                
                                
                                
                            <?php
                                
                                //get all the company names and their prices
                                $query = "SELECT id, name, abbr, price FROM companies";
                                
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
                                            
                                            echo "<tr>";
                                            echo "<td class='text-left'><a href='company.php?id=$company_id'>$company_name </a>($company_abbr)</td>";
                                            echo "<td class='text-left'>$company_price</td>";
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
             
                <a href="#menu-toggle" class="btn btn-default" id="menu-toggle">Toggle Menu</a>
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
