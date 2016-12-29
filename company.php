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
    
if(!isset($_GET['id']) && empty($_GET['id']))
{
    header("Location:index.php");
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
                    <a href="index.php">Dashboard</a>
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
                       <?php
                        
                        $company_id = $_GET['id'];
                        
                        $query = "SELECT * FROM companies WHERE id='$company_id'";
                        
                        if($run = mysqli_query($conn, $query))
                        {
                            if(mysqli_num_rows($run) == 0)
                                echo "\n<h3>Dude, you're lost!</h3>\n";
                            while($array = mysqli_fetch_assoc($run))
                            {
                                $name = $array['name'];
                                $price = $array['price'];
                                $abbr = $array['abbr'];
                                $prev_price = $array['prev_price'];
                                $high = $array['high'];
                                $low = $array['low'];
                                $description = $array['description'];
                                
                                echo "<div id='name' class='jumbotron'><div class='page-header'>
                                      <h1>$name</h1>
                                    </div>
                                <div>($abbr)</div></div>
                                <div class='row'>
                                <div class='col-sm-3'>
                                <div class='panel panel-primary'><div class='panel-heading'>Current Price</div><div class='panel-body'>$price</div></div></div>
                                <div class='col-sm-3'>
                                <div class='panel panel-primary'><div class='panel-heading'>Previous Price</div><div class='panel-body'>$prev_price</div></div></div>
                                <div class='col-sm-3'>
                                <div class='panel panel-primary'><div class='panel-heading'>Lowest Price</div><div class='panel-body'>$low</div></div></div>
                                <div class='col-sm-3'>
                                <div class='panel panel-primary'><div class='panel-heading'>Highest Price</div><div class='panel-body'>$high</div></div>
                                </div>
                                </div>
                                <br><div class='panel panel-primary' id = 'description'><div class='panel-body'>$description</div></div>";
                                
                                
                            }
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
