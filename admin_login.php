<html>
    <head>
        <title>Login | Game Of Shares</title>
        <link type="text/css" href="css/login.css" rel="stylesheet">
    </head>

<?php
include "classes.inc.php";
include "conn.inc.php";

    
//logging in the admin
if(isset($_POST['password']) && !empty($_POST['password']))
{
    if(admin_login($conn, md5($_POST['password'])))
    {
        header("Location:admin.php");
    }
    else
    {
        
        
    }
}
   
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
              <h1 class="text-center">Enter Admin Password</h1>
            </div>
             <div class="modal-body">
                 <form name="login" action="admin_login.php" method="post">

                     <div class="form-group">
                         <input type="password" class="form-control input-lg" name="password" placeholder="Password"/>
                     </div>

                     <div class="form-group">
                         <input type="submit" class="btn btn-block btn-lg btn-primary" value="Login"/>
                         <span class="pull-right"><a href="login.php">User Login</a></span>
                     </div>
                </form>
             </div>
        </div>
     </div>

</body>
</html>

<?php
//login the admin
function admin_login($conn, $password)
{
    $query_login = "SELECT password from admin WHERE password = '$password'";
    if($run = mysqli_query($conn, $query_login))
    {
        if(mysqli_num_rows($run) == 1)
        {
            $array = mysqli_fetch_assoc($run);
            //log in the user
            admin_session_start("Admin");
                
            return true;
        
        }
        else
        {
            echo "Invalid Username/Password combination.";
            return false;
        }
    }
}

function admin_session_start($admin)
{
    $_SESSION['admin'] = $admin;
    
}

?>
