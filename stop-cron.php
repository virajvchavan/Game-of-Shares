<?php
include "classes.inc.php";
include "conn.inc.php";

$time = time();
$session = "off";

if(mysqli_query($conn, "UPDATE admin SET session = '$session', time = '$time' WHERE 1"))
    echo "Success";
else
    echo "Fail";

?>