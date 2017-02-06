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
                            <th>Balance</th>
                            <th>Stocks Valuation</th>
                            <th>Total</th>
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                
                                <?php
                                
                                $array_sorted = array();
                                
                                $query = "SELECT * FROM users ORDER BY balance DESC";
                                
                                if($run = mysqli_query($conn, $query))
                                {
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "<tr><td colspan='5'>No users to show</td></tr>";
                                    }
                                    else
                                    {
                                        
                                        //for each user
                                        while($array = mysqli_fetch_assoc($run))
                                        {
                                            $user_id = $array['id'];
                                            $fname = $array['first_name'];
                                            $lname = $array['last_name'];
                                            $balance = $array['balance'];
                                            $highest_rank = $array['highest_rank'];
                                            
                                            $temp_user = new User($user_id, $conn);
                                            
                                            //get user's shares and valuate them
                                            $total_valuation = 0;
                                            $valuation_in_shares = $temp_user->get_valuation($conn);
                                            
                                            
                                            $total_valuation = $balance + $valuation_in_shares;
                                            
                                            
                                            $array['total'] = $total_valuation;
                                            $array['stock_v'] = $valuation_in_shares;
                                            
                                            //push all that data to a new array so that we can sort it according to the newly calculated  total valuation
                                            array_push($array_sorted, $array);
                                                         
                                        }
                                    }
                                }
                                
                                //needed for usort()
                                function sortByTotal($a, $b)
                                {
                                    $a = $a['total'];
                                    $b = $b['total'];

                                    if ($a == $b)
                                    {
                                        return 0;
                                    }

                                    return ($a > $b) ? -1 : 1;
                                }
                                
                                //sort by total_valuation
                                usort($array_sorted, 'sortByTotal');
                                
                                $no = 0;
                                                                
                                //now print the leaderboard table rows
                                foreach($array_sorted as $key=>$value)
                                {
                                    //this is the current rank of this user
                                    $no++;
                                                                       
                                    
                                    $id = $value['id'];
                                    $fname = $value['first_name'];
                                    $lname = $value['last_name'];
                                    $balance = $value['balance'];
                                    $total = $value['total'];
                                    $stock_v = $value['stock_v'];
                                    $highest_r = $value['highest_rank'];
   
                                    //update rank
                                    mysqli_query($conn, "UPDATE users SET rank = '$no' WHERE id = '$id'");
                                    
                                    if($no < $highest_r)
                                    {
                                        $highest_r= $no;
                                        
                                        //update rank
                                        mysqli_query($conn, "UPDATE users SET highest_rank = '$highest_r' WHERE id = '$id'");
                                    
                                    }
                                    
                                    //show his stats to the user
                                    if($id == $user->id)
                                    {
                                        if($no < $highest_r)
                                        {
                                            $highest_r = $no;
                                            if(mysqli_query($conn, "UPDATE users SET highest_rank = $highest_r WHERE id = $id"))
                                                echo "";
                                            else
                                                echo "Errr";
                                        }
                                        
                                        echo "<div id='note'><span class='pull-left' style='margin-left: 40px; padding: 0px 10px 0px 10px;' id = 'user_color'>Rank: $no</span>";
                                        
                                        if($no == 1)
                                        {
                                            echo "Who's the boss? You're the boss!! First on the leaderboard!";
                                        }
                                        elseif($no == 2)
                                        {
                                            echo "You are amazing! Second on the leaderboard!";
                                        }
                                        elseif($no == 3)
                                        {
                                            echo "Keep it up, buddy! Third on the leaderboard!";
                                        }
                                        elseif(3<$no && $no < 11)
                                        {
                                            echo "You're in top 10. A little more and you'll be in top 3.";
                                        }
                                        elseif(11<=$no && $no < 31)
                                        {
                                            echo "You're in top 30. You can easily be in top 10 now!";
                                        }
                                        else
                                            echo "Keep investing!";
                                        
                                        $u_color = "user_color";
                                        
                                        echo "<span class='pull-right' id = 'user_color' style='margin-right: 40px;padding: 0px 10px 0px 10px;'><div id='shareBtn' class='btn btn-facebook'>
                        <span class='fa fa-facebook'></span>&nbsp;Tell Your Friends</div>
                    Your Highest: $highest_r</span></div>";
                                        ?>
                                   <script>
                                        document.getElementById('shareBtn').onclick = function() {
                                          FB.ui({
                                            method: 'share',
                                            display: 'popup',
                                            href: 'http://gameofshares.esy.es',
                                           quote: "Beat me if you can! Achieved Rank <?php echo $highest_r; ?> on Game Of Shares! Game of Shares is a fun stock market game where you compete with other people and progress through the Leaderboard."
                                          }, function(response){});
                                        }
                                    </script>
                                <?php
                                        
                                    }      
                                    else
                                        $u_color = "nothing";
                                    if($no == 1)
                                        $color = "golden";
                                    elseif($no == 2)
                                        $color = "silver";
                                    elseif($no == 3)
                                        $color = "bronz";
                                    else
                                        $color = "";
                                    
                                    echo "<tr id = '$u_color'>
                                            <td id='$color'>".$no."</td>
                                            <td id='$color'><a href='profile.php?id=$id'>$fname $lname</a></td>
                                            <td>".number_format($balance)."</td>
                                            <td>".number_format($stock_v)."</td>
                                            <td>".number_format($total)."</td>
                                    </tr>";
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

</body>

</html>


<?php



?>
