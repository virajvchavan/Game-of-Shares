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
                    <a href="companies_admin.php">Companies</a>
                </li>
                <li>
                    <a href="users.php"  class="active">Users</a>
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
                        <br><br>
                        <form action="export_users.php" method="post" name="export_excel">
                             <div class="control-group">
                                <div class="controls">
                                    <button type="submit" id="export" name="export" class="btn btn-primary button-loading" data-loading-text="Loading...">Download Excel File</button>
                                </div>
                            </div>
                        </form>
                        <br>
                        <table class="table-fill">
                            <thead>
                            <tr>
                            <th>No.</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            </tr>
                            </thead>
                            <tbody class="table-hover">
                                
                                <?php
                                
                                $query = "SELECT * FROM users ORDER BY id";
                                
                                if($run = mysqli_query($conn, $query))
                                {
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "<tr><td colspan='6'>No users to show</td></tr>";
                                    }
                                    else
                                    {
                                        $no = 0;
                                        while($array = mysqli_fetch_assoc($run))
                                        {
                                            $no++;
                                            $user_id = $array['id'];
                                            $fname = $array['first_name'];
                                            $lname = $array['last_name'];
                                            $phone = $array['phone'];
                                            $email = $array['email'];

                                            echo "<tr>
                                                    <td>".$no."</td>
                                                    <td>$fname $lname</td>
                                                    <td>$email</td>
                                                    <td>$phone</td>
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

</body>

</html>


<?php




?>
