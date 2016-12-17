<!DOCTYPE html>
<html lang="en">

    
<?php
include "classes.inc.php";
include "conn.inc.php";    
    
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
                    Balance :
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
                    <a href="logout.php">Logout</a>
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
                        
                        <div class="table-title">
                            <h3>Trade Book</h3>
                        </div>
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
                            <tr>
                            <td class="text-left">January</td>
                            <td class="text-left">$ 50,000.00</td>
                            <td class="text-left">10.6</td>
                            <td class="text-left">January</td>
                            <td class="text-left">$ 50,000.00</td>
                            <td class="text-left">10.6</td>
                            <td class="text-left">Blah</td>
                            </tr>
                            <tr>
                            <td class="text-left">January</td>
                            <td class="text-left">$ 50,000.00</td>
                            <td class="text-left">10.6</td>
                            <td class="text-left">January</td>
                            <td class="text-left">$ 50,000.00</td>
                            <td class="text-left">10.6</td>
                            <td class="text-left">Blah</td>
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
