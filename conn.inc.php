<?php

ob_start();
session_start();


//u390374791_gos
//u390374791_viraj
//mysql.hostinger.in

$servername = "127.0.0.1";
$username_db = "root";
$password = "";
$dbname = "game_of_shares";
// Create connection

//limits for changing the stock prices

$time_limit_for_company = 120;      //the max time after the price will change
$price_limit_for_company = 10.0;    //the max amount that the price will change

$conn = new mysqli($servername, $username_db, $password, $dbname);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

//check if market is open or not, show the message if not
$query = "SELECT session, time FROM admin WHERE id = 1";
if($run = mysqli_query($conn, $query))
{
    while($array = mysqli_fetch_assoc($run))
    {
        $session_db = $array['session'];
        $start_time = $array['time'];
    }
    
    if($session_db == "off")
    {
        echo "<div id='note'>The market is closed at this moment. The Market Opens at 08:00 hours and Closes at 22:00 hours.</div>";
    }    
}


//checks if a user is logged in or not, 
//returns a boolean
function isLoggedIn()
{
	if(isset($_SESSION['gos_user_id']) && !empty($_SESSION['gos_user_id']))
	{   
		return true;
	}
	else
	{
		return false;
	}
}

//store user_id and balance of the logged in user
if(isLoggedIn())
{
    $balance = getBalance($conn, $_SESSION['gos_user_id']);
        
    //create the User object
    $user = new User($_SESSION['gos_user_id'], $conn);
}

function getBalance($conn, $id)
{
    //write a query and return the balance of the user with user_id = id
    $query = "SELECT balance FROM users WHERE id = $id";
    if($run = mysqli_query($conn, $query))
    {
        if($array = mysqli_fetch_assoc($run))
        {
            $balance = $array['balance'];
        }
    }
    return $balance;
}

//check if prices for companies need to be changed, and change them
function changePrices($conn, $time_limit_for_company, $price_limit_for_company)
{ 
    $query = "SELECT id, time, price, high, low FROM companies";
    if($run = mysqli_query($conn, $query))
    {
        while($array = mysqli_fetch_assoc($run))
        {
            $time = $array['time'];
            
            if($time > time())
            {
                continue;
            }
            
            $company_id = $array['id'];
            $price = $array['price'];
            $high = $array['high'];
            $low = $array['low'];
            
            //set a random amount of time for the future
            $rand_time = rand(0, $time_limit_for_company);
            $time = time();
            $time += $rand_time;

            //set a random change in price
            $rand_price = random_float(-$price_limit_for_company, $price_limit_for_company);
            $new_price = round($price + $rand_price, 1);
            if($new_price < 0)
            {
                $new_price += round(abs(2*$rand_price), 1);
            }
            
            if($new_price > $high)
                $high = $new_price;
            elseif($new_price < $low)
                $low = $new_price;
            
            $query_update = "UPDATE companies SET price = $new_price, time = $time, prev_price = $price, high = $high, low = $low WHERE id = $company_id";
            
            if(mysqli_query($conn, $query_update))
            {
                //insert into 'price_variation' table
                $query_price = "INSERT INTO price_variation(company_id, price) VALUES('$company_id', '$new_price')";
                if(mysqli_query($conn, $query_price))
                {		

                }
                else
                    echo "Error price table";
            }
            else
                echo "Err";
            
        }
    }
}

function random_float ($min,$max) {
    return ($min + lcg_value()*(abs($max - $min)));
}
?>

