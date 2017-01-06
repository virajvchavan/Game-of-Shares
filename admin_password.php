<!DOCTYPE html>
<html lang="en">

<?php
include "classes.inc.php";
include "conn.inc.php";

    
//leave if admin not logged in
if(!isset($_SESSION['gos_admin']))
{
    header("Location:login.php");
}
    
//change the share price of companies (from functions.index.php)
changePrices($conn, $time_limit_for_company, $price_limit_for_company);
    
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

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id='sidebar-wrapper'>
            <ul class='sidebar-nav'>
                <li id='balance'>
                    Admin
                </li>
                <li>
                    <a href='admin.php'>Session</a>
                </li>
                <li>
                    <a href="companies_admin.php">Companies</a>
                </li>
                <li>
                    <a href='users.php'>Users</a>
                </li>
                <li>
                    <a href='admin_password.php' class='active'>Change Password</a>
                </li>
                <li>
                    <a href='logout.php'>Logout</a>
                </li>
            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="admin.php" method="post" onsubmit="return validateForm()" name="password">
                            <br><br>
                            <div class="form-group">
                                <label for="name">Current Password</label>
                                <input type="text" class="form-control" name="current_p" required>
                            </div>
                            <div class="form-group">
                                <label for="abbr">New Password:</label>
                                <input type="text" class="form-control" name="new_p" required>
                            </div>
                            <div class="form-group">
                                <label for="price">Retype New Password</label>
                                <input type='text' class="form-control" name="new_confirm_p" required>
                            </div>
                                <input type="submit" class="btn btn-success" value="Change Password">
                        </form>
                        
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


<?php




?>
