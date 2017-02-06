<!DOCTYPE html>
<html lang="en">

<?php
include "classes.inc.php";
include "conn.inc.php";

//change the share price of companies (from functions.index.php)    
if($session_db != "off")    
    changePrices($conn, $time_limit_for_company, $price_limit_for_company);
    
//leave if admin not logged in
if(!isset($_SESSION['gos_admin']))
{
    header("Location:login.php");
}
    
//edit the company details    
if(isset($_GET['id']) && isset($_POST['name']) && isset($_POST['abbr']) && isset($_POST['description']) && isset($_POST['price']))
{

    $id = $_GET['id'];
    $name = $_POST['name'];
    $abbr = $_POST['abbr'];
    $description = nl2br($_POST['description']);
    $price = $_POST['price'];
    

    
    $query = "UPDATE companies SET name = '$name', abbr = '$abbr', description = '$description' WHERE id = $id";
    
    
    $company = new Company($id);
    if($company->set_price($conn, $price) && mysqli_query($conn, $query))
    {
        echo "<div id='note'>Details updated for $name. <a id='close' class='pull-right'>[Close]&nbsp;</a></div>";
    }
    else
        echo "Gandlo";
}
   
//change the password for admin    
if(isset($_POST['current_p']) && isset($_POST['new_p']) && isset($_POST['new_confirm_p']))
{
    $query = "SELECT password FROM admin";
    if($run = mysqli_query($conn, $query))
    {
        $array = mysqli_fetch_assoc($run);
        
        $real_p = $array['password'];
    }
    
    if(md5($_POST['current_p']) != $real_p)
    {
        echo "<script>alert('Wrong current password.')</script>";
        header("refresh:0,url=admin_password.php");       
    }
    $query_change_p = "UPDATE admin SET password = '".md5($_POST['new_p'])."'";
    if(mysqli_query($conn, $query_change_p))
    {
         echo "<div id='note'>Password Changed Successfuly. <a id='close' class='pull-right'>[Close]&nbsp;</a></div>";
    }
    
}
  
//adding the new company
if(isset($_POST['new_name']) && isset($_POST['new_abbr']) && isset($_POST['new_description']) && isset($_POST['new_price']))
{
    $name = $_POST['new_name'];
    $abbr = $_POST['new_abbr'];
    $description = nl2br($_POST['new_description']);
    $price = $_POST['new_price'];
    
    //set a random amount of time for the future
    $rand_time = rand(0, $time_limit_for_company);
    $time = time();
    $time += $rand_time;
    
    //insert into 'companies' table
    $query_resgister = "INSERT INTO companies(name,abbr, description, price, high, low, time) VALUES('$name','$abbr', '$description', '$price', '$price', '$price', '$time')";

		if(mysqli_query($conn, $query_resgister))
		{
            $last_id = mysqli_insert_id($conn);
            
            //insert into 'price_variation' table
            $query_price = "INSERT INTO price_variation(company_id, price) VALUES('$last_id', '$price')";
            if(mysqli_query($conn, $query_price))
            {		

            }
            else
                echo "Error price table";
            
			echo "<div id='note'>New Company Added: $name<a id='close' class='pull-right'>[Close]&nbsp;</a></div>";
		}
		else
			echo "Error Registering.";
    
    
}
    
//start or stop the session    
if(isset($_SESSION['gos_admin']) && isset($_GET['session']) && !empty($_GET['session']))
{
    $session = $_GET['session'];
    $time = time();
    $query_session = "UPDATE admin SET session = '$session', time = '$time' WHERE 1";
    if(mysqli_query($conn, $query_session))
    {
        header("Location:admin.php");
    }
    else
        echo "Falure";    
}
    
//check if session is on or off and set the timer
$query = "SELECT session, time FROM admin WHERE id = 1";
if($run = mysqli_query($conn, $query))
{
    while($array = mysqli_fetch_assoc($run))
    {
        $session_db = $array['session'];
        $start_time = $array['time'];
    }
    
    if($session_db == "on")
        $switch = "off";
    elseif($session_db == "off")
        $switch = "on";
    
    if($session_db == "on")
    {
        $duration = time() - $start_time;
        $timer =  gmdate("H:i:s", (int)$duration);
        
    }
    else
        $timer = "";
    
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
            
        .center {
            margin-top: 18%;
            text-align: center;
            }
    
    </style>
    

</head>

<body>

    <div id="wrapper">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav">
                <li id="balance">
                    Admin
                </li>
                <li>
                    <a href="admin.php"  class="active">Session</a>
                </li>
                <li>
                    <a href="companies_admin.php">Companies</a>
                </li>
                <li>
                    <a href="users.php">Users</a>
                </li>
                <li>
                    <a href="admin_password.php">Change Password</a>
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
                <div class="row">
                    <div class="col-lg-12">
                        <div class="center">
                        <?php
                        
                        ?>
                        <a href="admin.php?session=<?php echo $switch; ?>" class="btn btn-<?php if($switch == "on") echo "success"; elseif($switch == "off") echo "danger"; ?> btn-lg"><?php if($switch == "on") echo "Start"; elseif($switch == "off") echo "Stop"; ?> the Session</a><br><br>
                        <?php
                        if($timer != "")
                        {
                            ?>
                        <h1><span id="realtime"><?php echo $timer; ?></span></h1>
                   <?php }
                        ?>
                        </div>
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
    $(document).ready (function () {
    startCount();
});
 
function startCount()
{
	timer = setInterval(count,1000);
}
function count()
{
	var time_shown = $("#realtime").text();
        var time_chunks = time_shown.split(":");
        var hour, mins, secs;
 
        hour=Number(time_chunks[0]);
        mins=Number(time_chunks[1]);
        secs=Number(time_chunks[2]);
        secs++;
            if (secs==60){
                secs = 0;
                mins=mins + 1;
               } 
              if (mins==60){
                mins=0;
                hour=hour + 1;
              }
              if (hour==13){
                hour=1;
              }
 
        $("#realtime").text(hour +":" + plz(mins) + ":" + plz(secs));
 
}
 
function plz(digit){
 
    var zpad = digit + '';
    if (digit < 10) {
        zpad = "0" + zpad;
    }
    return zpad;
}
Posted 
    
    
    </script>
    
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
