<html>
    <head>
        <title>Login | Game Of Shares</title>
        <link type="text/css" href="css/login.css" rel="stylesheet">
    </head>

<?php
include "classes.inc.php";
include "conn.inc.php";
    
//leave if already not logged in
if(isLoggedIn())
{
    header("Location:index.php");
}    
    
    
?>    
<body>
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h1 class="text-center">Welcome</h1>
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
                         <span class="pull-right"><a href="register.php">Register</a></span><span><a href="#">Forgot Password</a></span>
                     </div>
                     <div class="form-group">
                       <span class="center"> <a href="admin_login.php">Admin Access</a></span>
                     </div>
                </form>
             </div>
        </div>
     </div>

</body>
</html>