<!DOCTYPE html>
<html lang="en">

<?php
include "classes.inc.php";
include "conn.inc.php";
include "functions.index.php"; 
           
//leave if not logged in
if(!isLoggedIn())
{
    header("Location:login.php");
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
                font-size: 14px;
            }
            
            #golden{
                background: gold;
            }    
            #silver{
                background: silver;
            }
            #bronz{
                background: #CD7F32;
            }
            #user_color{
                border: solid #004D40 2px;
                padding-left: 15px;
                padding-right: 15px;
                margin-left: 30px;
            }
    
    </style>
</head>

<body>

    
    <div id="fb-root"></div>
    <script>
    window.fbAsyncInit = function() {
    FB.init({appId: '1859297917624873', status: true, cookie: true,
    xfbml: true});
    };
    (function() {
    var e = document.createElement('script'); e.async = true;
    e.src = document.location.protocol +
    '//connect.facebook.net/en_US/all.js';
    document.getElementById('fb-root').appendChild(e);
    }());
    </script>
                    
    
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
                        <br>
                        <table class="table-fill">
                            <thead>
                            <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Gold</th>
                            <th>Silver</th>
                            <th>Bronze</th>
                            <th>Top 10</th>
                            <th>Top 30</th>
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                <?php
                                $user_rank = 500;
                                
                                $query = "SELECT id, first_name, last_name, gold, silver, bronze, top_10, top_30 FROM users WHERE gold > 0 or silver > 0 or bronze > 0 or top_10 > 0 or top_30 > 0 ORDER BY gold DESC, silver DESC, bronze DESC, top_10 DESC, top_30 DESC";
                                
                                if($run = mysqli_query($conn, $query))
                                {
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "<tr><td colspan='7'>No Winners Yet. Let a league end.</td></tr>";
                                    }
                                    $rank = 0;
                                    while($array = mysqli_fetch_assoc($run))
                                    {
                                        $rank++;
                                        
                                        if($user->id == $array['id'])
                                        {
                                            $user_rank = $rank;
                                        }
                                        
                                        echo "<tr";
                                        if($user->id == $array['id'])
                                        {
                                            echo " id = 'user_color'";
                                        }
                                        echo ">
                                        <td>$rank</td>
                                        <td><a href='profile.php?id=".$array['id']."'>".$array['first_name']." ".$array['last_name']."</a></td>
                                        <td>".$array['gold']."</td>
                                        <td>".$array['silver']."</td>
                                        <td>".$array['bronze']."</td>
                                        <td>".$array['top_10']."</td>
                                        <td>".$array['top_30']."</td>
                                        </tr>";
                                    }
                                }
                                
                                if($user_rank != 500)
                                {
                                    echo "<div id='note'><span id='user_color' class='pull-left'>Rank: $user_rank</span> &nbsp;Congratulations! You are in the top 30 of all the users! <div id='shareBtn' class='btn btn-facebook pull-right' style='margin: 5px;'>
                                    <span class='fa fa-facebook'></span>&nbsp;Tell Your Friends</div></div>";
                                    
                                }
                                else
                                {
                                    echo "<div id='note'>Be on top 30 in the leaderboard of a league to appear on this page!</div>";
                                }
                          ?>
                                <script>
                                        document.getElementById('shareBtn').onclick = function() {
                                          FB.ui({
                                            method: 'share',
                                            display: 'popup',
                                            href: 'http://gameofshares.esy.es',
                                           quote: "Beat me if you can! Winner on Game Of Shares with rank <?php echo $user_rank; ?>! Game of Shares is a fun stock market game where you compete with other people and progress through the Leaderboard."
                                          }, function(response){});
                                        }
                                    </script>
                                
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

</body>

</html>


<?php



?>
