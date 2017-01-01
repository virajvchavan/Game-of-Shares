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
    

    
if(!isset($_POST['edit_id']) || empty($_POST['edit_id']))
{
    header("Location:admin.php");
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
                    <a href="admin.php"  class="active">Companies</a>
                </li>
                <li>
                    <a href="users.php">Users</a>
                </li>
                <li>
                    <a href="password.php">Change Password</a>
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
                        
                                
                                <?php
                                
                                $query = "SELECT * FROM companies WHERE id = ".$_POST['edit_id'];
                                
                                if($run = mysqli_query($conn, $query))
                                {
                                    if(mysqli_num_rows($run) < 1)
                                    {
                                        echo "This Company does noy exist.";
                                    }
                                    else
                                    {
                                        
                                        while($array = mysqli_fetch_assoc($run))
                                        {
                                           
                                            $company_id = $_POST['edit_id'];
                                            $name = $array['name'];
                                            $abbr = $array['abbr'];
                                            $price = $array['price'];
                                            $description = $array['description'];
                                            
                                          ?>
                                            <form action="admin.php?id=<?php echo $company_id; ?>" method="post">
                                                <div class="form-group">
                                                    <label for="name">Name:</label>
                                                    <input type="text" class="form-control" name="name" value="<?php echo $name; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="abbr">Abbr:</label>
                                                    <input type="text" class="form-control" name="abbr" value="<?php echo $abbr; ?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Description:</label>
                                                    <textarea class="form-control" rows = "6" name="description"><?php echo $description; ?></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label for="price">Price</label>
                                                    <input type='text' class="form-control" name="price" value="<?php echo $price ?>"> </div>
                                                
                                                <input type="submit" class="btn btn-success">
                                            </form>

                                    <?php        
                                        }
                                    }
                                }   
                                
                                ?>
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