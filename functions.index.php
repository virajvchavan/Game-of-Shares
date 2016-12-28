<?php

//add entry in the users table
function register($conn, $first_name, $last_name, $email, $phone, $password)
{
    //set initial balance for user
    $initial_balance = 5000;
    
    $ok = true;
    
	$first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
    $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
	$email = filter_var($email, FILTER_SANITIZE_EMAIL);
	
	
    //check if email already registered
	$query_email_check = "SELECT id FROM users WHERE email = '$email'";
	if($run = mysqli_query($conn, $query_email_check))
	{
		if(mysqli_num_rows($run) >= 1)
		{
			echo "<script>alert('Email already registered.');</script>";
			$ok = false;
		}
	}
	
	if($ok)
	{
		$query_resgister = "INSERT INTO users(first_name,last_name, email, phone, password, balance) VALUES('$first_name','$last_name', '$email', '$phone', '$password', '$initial_balance')";

		if(mysqli_query($conn, $query_resgister))
		{
			
			
			if(login($conn, $email, $password))
            {
                return true;
            }
            else
            {
                echo "Error logging in.";   
            }
			

			//header("refresh:0,url=index.php");
		}
		else
			echo "Error Registering.";
	}
    else
        return false;
}


//login the user
function login($conn, $email, $password)
{
    $query_login = "SELECT id, first_name from users WHERE email = '$email' AND password = '$password'";
    if($run = mysqli_query($conn, $query_login))
    {
        if(mysqli_num_rows($run) == 1)
        {
            $array = mysqli_fetch_assoc($run);
            //log in the user
            login_session_start($array['id'], $array['first_name']);
                
            return true;
        
        }
        else
        {
            echo "Invalid Username/Password combination.";
            return false;
        }
    }
}

function login_session_start($id, $first_name)
{
    $_SESSION['user_id'] = $id;
    $_SESSION['user_name'] = $first_name;
    
}

//check if prices for companies need to be changed, and change them
function changePrices($conn)
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
            $rand_time = rand(0, 5);
            $time = time();
            $time += $rand_time;

            //set a random change in price
            $rand_price = random_float(-5.0, 5.0);
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