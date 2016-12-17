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

?>