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

//check for any messages    
$user->checkMessages($conn);

//change the share price of companies (from functions.index.php)
changePrices($conn, $time_limit_for_company, $price_limit_for_company);
    
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
            
            
        #chartdiv {
            background-color: #30303d; color: #fff;
	width	: 100%;
	height	: 500px;
}
            #name{
                background-color: #262626;
                color: white;
            }
            #user_shares{
                 background-color: white;
                color: #262626;
                padding: 8px;
                font-size: 16px;
                border-radius: 7px;
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
                <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
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
                       <?php
                        
                        $company_id = $_GET['id'];
                        
                        //to show how many shares usser owns of this company
                        $query_get_users_shares = "SELECT quantity FROM shares WHERE user_id = $user->id AND company_id = $company_id";
                        if($run_user_shares = mysqli_query($conn, $query_get_users_shares))
                        {
                            if(mysqli_num_rows($run_user_shares) == 0)
                                $user_shares = 0;
                            else
                            {
                                $array_users_shares = mysqli_fetch_assoc($run_user_shares);
                                $user_shares = $array_users_shares['quantity'];
                            }
                            
                        }
                        
                        //get all the company data
                        $query = "SELECT * FROM companies WHERE id='$company_id'";                      
                        if($run = mysqli_query($conn, $query))
                        {
                            if(mysqli_num_rows($run) == 0)
                            {
                                echo "\n<h3>Dude, you're lost!</h3>";
                                header("Location:index.php");
                                
                            }
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
                                        <div class='pull-right' id='user_shares'>You own: $user_shares</div>
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
                        //do the graph thing now
                        ?>

                        
                        <!-- Resources -->
                        <script src="js/amcharts.js"></script>
                        <script src="js/serial.js"></script>
                        <script src="js/export.min.js"></script>
                        <link rel="stylesheet" href="css/export.css" type="text/css" media="all" />
                        <script src="js/dark.js"></script>


                        <div id="chartdiv"></div>					
                        
                        
                        <script>
                            
                            var chart = AmCharts.makeChart("chartdiv", {
                            "type": "serial",
                            "theme": "dark",
                            "marginRight": 40,
                            "marginLeft": 40,
                            "autoMarginOffset": 20,
                            "mouseWheelZoomEnabled":true,
                            "dataDateFormat": "YYYY-MM-DD HH:NN:SS",
                            "valueAxes": [{
                                "id": "v1",
                                "axisAlpha": 0,
                                "position": "left",
                                "ignoreAxisWidth":true
                            }],
                            "balloon": {
                                "borderThickness": 1,
                                "shadowAlpha": 0
                            },
                            "graphs": [{
                                "id": "g1",
                                "balloon":{
                                  "drop":true,
                                  "adjustBorderColor":false,
                                  "color":"#ffffff"
                                },
                                "bullet": "round",
                                "bulletBorderAlpha": 1,
                                "bulletColor": "#FFFFFF",
                                "bulletSize": 5,
                                "hideBulletsCount": 50,
                                "lineThickness": 2,
                                "title": "red line",
                                "useLineColorForBulletBorder": true,
                                "valueField": "value",
                                "balloonText": "<span style='font-size:18px;'>[[value]]</span>"
                            }],
                            "chartScrollbar": {
                                "graph": "g1",
                                "oppositeAxis":false,
                                "offset":30,
                                "scrollbarHeight": 80,
                                "backgroundAlpha": 0,
                                "selectedBackgroundAlpha": 0.1,
                                "selectedBackgroundColor": "#888888",
                                "graphFillAlpha": 0,
                                "graphLineAlpha": 0.5,
                                "selectedGraphFillAlpha": 0,
                                "selectedGraphLineAlpha": 1,
                                "autoGridCount":true,
                                "color":"#AAAAAA"
                            },
                            "chartCursor": {
                                "pan": true,
                                "valueLineEnabled": true,
                                "valueLineBalloonEnabled": true,
                                "cursorAlpha":1,
                                "cursorColor":"#258cbb",
                                "limitToGraph":"g1",
                                "valueLineAlpha":0.2,
                                "valueZoomable":true
                            },
                            "valueScrollbar":{
                              "oppositeAxis":false,
                              "offset":50,
                              "scrollbarHeight":10
                            },
                            "categoryField": "date",
                            "categoryAxis": {
                                "minPeriod": "mm",
                                "parseDates": true,
                                "dashLength": 1,
                                "minorGridEnabled": true
                            },
                            "export": {
                                "enabled": true,
                                "dateFormat": "YYYY-MM-DD HH:NN:SS"
                            },
                            "dataProvider": [
                        <?php
                        
                        $query_graph = "SELECT price, time FROM price_variation WHERE company_id = $company_id";
                        if($run_graph = mysqli_query($conn, $query_graph))
                        {
                            
                            while($array_graph = mysqli_fetch_assoc($run_graph))
                            {
                                $time = $array_graph['time'];
                                $price = $array_graph['price'];
                                
                                echo "{
                                    'date':'".date("Y-m-d H:i:s", strtotime($time))."',
                                    'value': $price
                                },";
                            }
                        }
                        
                        
                        
                        ?>
                                
                                ]
                                });

                                chart.addListener("rendered", zoomChart);

                                zoomChart();

                                function zoomChart() {
                                    chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
                                }
                        </script>
                    </div>
                </div>
                <br>
                
                    <div class="col-lg-12">
                        <h3>Past Transactions with <?php echo $name; ?></h3>
                        <table class="table-fill">
                            <thead>
                            <tr>
                            <th>Time</th>
                            <th>Buy/Sell</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                <?php
                                
                                $query = "SELECT quantity, price, time FROM transactions WHERE user_id =".$user->id." AND company_id = $company_id";
                                
                                if($run = mysqli_query($conn, $query))
                                {
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "<tr><td colspan='5'>No transactions to show</td></tr>";
                                    }
                                    else
                                    {
                                        
                                        while($array = mysqli_fetch_assoc($run))
                                        {
                                            
                                            $quantity = $array['quantity'];
                                            $time = $array['time'];
                                            $price = $array['price'];
                                            

                                            echo "<tr>
                                                    <td>$time</td>
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
                                                    <td>".abs($quantity)."</td>
                                                    <td>$price</td>
                                                    <td class='$class'>";
                                                    if($quantity < 0)
                                                        echo "+";
                                                    echo number_format(-$quantity*$price)."</td>
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
