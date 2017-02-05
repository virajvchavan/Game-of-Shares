<?php

include "classes.inc.php";
include "conn.inc.php";
include "functions.index.php";   

if($session_db != "off")
{
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
            
            //append to thee already present message/notification
            if($message != "") 
            {
                $query_message = "UPDATE users SET message = CONCAT_WS(CHAR(10 USING UTF8), message, '$message') WHERE id = '$user_id'";
                mysqli_query($conn, $query_message);
            }
        }
    }
}


//now start or close the market according to the current time
market_start_or_stop($conn);

start_new_league($conn);  
?>