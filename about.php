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

//change the share price of companies (from functions.index.php)    
if($session_db != "off")    
    changePrices($conn, $time_limit_for_company, $price_limit_for_company);
    
//check for any messages    
//$user->checkMessages($conn);

    
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

            h3{
                color: #004D40;
            }
    
    </style>

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
                        <div id="links_bottom">
                        Contact/Feedback: &nbsp;&nbsp;&nbsp;&nbsp;
                        <a href="http://github.com/virajvchavan" target='_blank'>Github</a>
                        &nbsp;&nbsp;&nbsp;<a href="http://linkedin.com/in/virajvchavan" target='_blank'>LinkedIn</a>
                        &nbsp;&nbsp;&nbsp;<a href="http://facebook.com/virajvchavan" target='_blank'>Facebook</a>
                        &nbsp;&nbsp;&nbsp;Call: 8975201655    
                        </div>
                        
                        <div id="note">Love solving puzzles? Visit <a href="http://puzzlepedia.esy.es/" target='_blank'>PuzzlePedia</a> to play now!</div>
                        
                        <br>
                        <h3>Devepoled as a Mini Project by:</h3>
                        <h4>
                            <ul>
                                <li>Shubham Hapse</li>
                                <li>Viraj Chavan &nbsp;&nbsp;&nbsp;
                                </li>
                                <li>Varun Maheshwari</li>
                            </ul>
                        </h4>
                        <hr>
                        <h3>
                            Under the guidance of:</h3> <h4><ul><li>Mr. Mayur Rathi<br><br> Department of Information Technology,<br>
                        Walchand College of Engineering, Sangli.<br></li></ul></h4>
                       
                        <hr>
                        
                        <h3>Concept by:</h3>
                        <h4><ul><li>Chani Bhate<br><br>
                            Chintamanrao Institute of Management Development And Research, 
                            <br>Sangli
                            </li></ul></h4><br>
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
