<html>
    <head>
        <title>Login | Game Of Shares</title>
        <meta name="description" content="Game of shares is a Share market game/Stock market game where users compete with each other to stay at the top of the leader board." />
        <meta name="keywords" content="stock market, share market, game, learn stocks, begginer, virtual market, virtual shares, virtual stock market" />
        <meta name="author" content="Viraj Chavan"/>
        <meta name="robots" content="index, follow" />
        
        <link type="text/css" href="css/login.css" rel="stylesheet">
        <style>
            #link{
                margin-top: 10px;
                font-size: 18px;
                padding: 10px;
            }
        
        </style>
    </head>

<?php
include "classes.inc.php";
include "conn.inc.php";
    
//leave if already not logged in
if(isLoggedIn())
{
    header("Location:index.php");
}    

//change the share price of companies (from functions.index.php)
if($session_db != "off")
    changePrices($conn, $time_limit_for_company, $price_limit_for_company);

?>    
<body>
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h1 class="text-center">Game of Shares</h1>
            </div>
             <div class="modal-body">
                 <form name="login" action="index.php" method="post">
                     <div class="form-group">
                         <input type="text" class="form-control input-lg" name="email" placeholder="Email"/>
                     </div>

                     <div class="form-group">
                         <input type="password" class="form-control input-lg" name="password" placeholder="Password"/>
                     </div>

                     <div class="form-group">
                         <input type="submit" class="btn btn-block btn-lg btn-primary" value="Login"/>
                         <span class="pull-right" id="link"><a href="register.php">Register</a></span>
                         <span id="link" class="pull-left"><a href="recover.php">Forgot Password</a></span><br>
                         
                     </div>
                </form>
             </div>
        </div>
     </div>

</body>
</html>