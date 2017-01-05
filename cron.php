<?php

include "classes.inc.php";
include "conn.inc.php";
include "functions.index.php";   

//change the share price of companies (from functions.index.php)
changePrices($conn, $time_limit_for_company, $price_limit_for_company);

//execute orders for all the users
$query = "SELECT id FROM users";

if($run = mysqli_query($conn, $query))
{
    while($array = mysqli_fetch_assoc($run))
    {
        $user_id = $array['id'];
        
        $temp_user = new User($user_id, $conn);
        
        $message = $temp_user->executeOrders($conn);
        
        $query_message = "UPDATE users SET message = '$message' WHERE id = '$user_id'";
        
        mysqli_query($conn, $query_message);
    }
}
?>