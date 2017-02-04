<html>
    <head>
        <title>Login | Game Of Shares</title>
        <link type="text/css" href="css/login.css" rel="stylesheet">
        <style>
        #link{
                margin-top: 10px;
                font-size: 15px;
                padding: 10px;
            }
        </style>
            <script>
    function validateForm()
                {
                    var pass1 = document.forms["password"]["new_p"].value;
                    var pass2 = document.forms["password"]["new_confirm_p"].value;
                    if(pass1 != pass2)
                        {
                            alert("Passwords do not match.");
                            return false;
                        }
                    if(pass1.length < 6)
                        {
                            alert("Password must be at least 6 characters.");
                            return false;
                        }
                }
   
    </script>
        
    </head>

<?php
include "classes.inc.php";
include "conn.inc.php";

//generate a random recovery code
function generateRandomString($length = 10) 
{
    
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
   
//leave if already logged in
if(isLoggedIn())
{
    header("Location:index.php");
}    

if(isset($_POST['new_p']) && !empty($_POST['new_p'] && isset($_POST['email_user']) && !empty($_POST['email_user']))
{
    $new_p = md5($_POST['new_p']);
    $email_user = $_POST['email_user'];
    
    if(mysqli_query($conn, "UPDATE users SET password = '$new_p' WHERE email = '$email_user'"))
        echo "success";
    else
        echo "not sucess";
}
    
//for showing the form to change the password
//user came here from the link sent to his email
if(isset($_GET['rec']) && !empty($_GET['rec']) && isset($_GET['user_email']) && !empty($_GET['user_email']))
{
    $user_rec_code = $_GET['rec'];
    $user_email = $_GET['user_email'];
    
    $query_check_code = "SELECT id FROM users WHERE email = '$user_email' AND recover_code = '$user_rec_code'";
    if(mysqli_num_rows(mysqli_query($conn, $query_check_code)) >= 1)
    {
    
    ?>
    <div class="col-lg-6">
        <h3>Enter Your New Password</h3>
                        <form action="recover.php" method="post" onsubmit="return validateForm()" name="password">
                            <br><br>
                            <div class="form-group">
                                <label for="abbr">New Password:</label>
                                <input type="password" class="form-control" name="new_p" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Retype New Password</label>
                                <input type='password' class="form-control" name="new_confirm_p" required>
                            </div>
                            <input type="email" value="<?php echo $user_email ?>" hidden name="email_user">
                                <input type="submit" class="btn btn-success" value="Change Password">
                        </form>                 
    </div>
    
<?php
    }
    else
        echo "<h3>Dude, you are lost!</h3>";
    
}    
//send the email for password recovery
elseif(isset($_POST['email']) && !empty($_POST['email']))
{
    $email_address = $_POST['email'];
    
    $to = $email_address;
    $subject = "Game Of Shares | Account Recovery";
    
    $recovery_code = generateRandomString();

    //store the recovery code in database
    $query = "UPDATE users SET recover_code = '$recovery_code' WHERE email = '$email_address'";
    if(!mysqli_query($conn, $query))
        echo "Reeere";
    $txt = "
    <p>Click on the below link to change your account's password.<br><br>
    
    <a href='http://gameofshares.esy.es/recover.php?rec=$recovery_code&user_email=$email_address'>http://gameofshares.esy.es/recover.php?rec=$recovery_code&user_email=$email_address
    </a>
    
    </p>
    ";
    $headers = "From: virajc@live.com" . "\r\n" .
    "CC: viraj.c014@gmail.com";
    
    // Always set content-type when sending HTML email
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    mail($to,$subject,$txt,$headers);
?>
<h2 class="center">Please check your Inbox of your Email.<br></h2>
<h3>Also check your spam folder.<hr>Visit the link sent to your email to change your password.</h3>
    
<?php    
}
else    
{ 
?>    
<body>
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h1 class="text-center">Account Recovery</h1>
            </div>
             <div class="modal-body">
                 <form name="login" action="recover.php" method="post">

                     <div class="form-group">
                         <input type="email" class="form-control input-lg" name="email" placeholder="Enter Registered Email"/>
                     </div>

                     <div class="form-group">
                         <input type="submit" class="btn btn-block btn-lg btn-primary" value="Send Link to your Email"/><br>
                         <span class="pull-right"><a href="login.php" id="link">Back to Login</a></span>                    
                     </div>
                </form>
             </div>
        </div>
     </div>

</body>
    
<?php
}
?>
</html>

