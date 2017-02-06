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
                    <a href="admin.php">Session</a>
                </li>
                <li>
                    <a href="companies_admin.php"  class="active">Companies</a>
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
                    <div class="col-lg-12"><br><br>
                        <a href="new_company.php" class="btn btn-primary btn-md">Add a new Company</a><br><br>
                        <table class="table-fill">
                            <thead>
                            <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Abbr</th>
                            <th>Descripition</th>
                            <th>Price</th>
                            <th>Action</th>
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                
                                <?php
                                
                                $query = "SELECT * FROM companies";
                                
                                if($run = mysqli_query($conn, $query))
                                {
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "<tr><td colspan='6'>No Companies to show</td></tr>";
                                    }
                                    else
                                    {
                                        $no = 0;
                                        while($array = mysqli_fetch_assoc($run))
                                        {
                                            $no++;
                                            $company_id = $array['id'];
                                            $name = $array['name'];
                                            $abbr = $array['abbr'];
                                            $price = $array['price'];
                                            $description = $array['description'];

                                            echo "<tr>
                                                    <td>".$no."</td>
                                                    <td>$name</td>
                                                    <td>$abbr</td>
                                                    <td>".substr($description, 0,42);
                                            if(strlen($description)> 42)
                                                echo "...";
                                            echo "</td>
                                                    <td>$price</td>
                                                    <td>
                                                        <form action='company_edit.php' method = 'post'><input type='text' value='$company_id' name='edit_id' hidden><input type='submit' class='btn btn-sm btn-primary' value='Edit'></form>
                                                    <td>
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
