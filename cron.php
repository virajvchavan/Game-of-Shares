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


//notification for feedbcak text
//We'd love to have your feedback! <a href='feedback.php'>Click here and Tell us what you think!</a>

//now start or close the market according to the current time

$current_time = time();

//for converting GMT time to IST, add this to GMT (19800)
$GMT_to_IST = 19800;

$current_time += $GMT_to_IST;

$hours = gmdate("H:i:s", (int)$current_time);

$hours = substr($hours, 0,2);


$time = time();

//session chalu kar
if($hours >=8 && $hours < 20)
{
    $session = "on";
}
//session band kar
else
{
    $session = "off";
}

if(mysqli_query($conn, "UPDATE admin SET session = '$session', time = '$time' WHERE 1"))
    echo "Success";
else
    echo "Fail";
    
?>