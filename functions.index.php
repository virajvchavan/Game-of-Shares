<?php

//add entry in the users table
function register($conn, $first_name, $last_name, $email, $phone, $password)
{
    //set initial balance for user
    $initial_balance = 500000;
    
    $ok = true;
    
	$first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
    $last_name = filter_var($last_name, FILTER_SANITIZE_STRING);
	$email = filter_var($email, FILTER_SANITIZE_EMAIL);
	
	
    //check if email already registered
	$query_email_check = "SELECT id FROM users WHERE email = '$email'";
	if($run = mysqli_query($conn, $query_email_check))
	{
		if(mysqli_num_rows($run) == 1)
		{
			echo "<script>alert('Email already registered.');</script>";
			$ok = false;
            header("refresh:0,register.php");
		}
	}
	
	if($ok)
	{
		$query_resgister = "INSERT INTO users(first_name,last_name, email, phone, password, balance) VALUES('$first_name','$last_name', '$email', '$phone', '$password', '$initial_balance')";

		if(mysqli_query($conn, $query_resgister))
		{	
			if(login($conn, $email, $password))
            {
                //header("refresh:0,help.php");
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
    $_SESSION['gos_user_id'] = $id;
    $_SESSION['gos_user_name'] = $first_name;
    
}

//start or close the market according to the current time
function market_start_or_stop($conn)
{
    $current_time = time();

    //for converting GMT time to IST, add this to GMT (19800)
    $GMT_to_IST = 19800;

    $current_time += $GMT_to_IST;

    $hours = gmdate("H:i:s", (int)$current_time);

    $hours = substr($hours, 0,2);


    $time = time();

    //session chalu kar
    if($hours >=8 && $hours < 22)
    {
        $session = "on";
    }
    //session band kar
    else
    {
        $session = "off";
    }

    mysqli_query($conn, "UPDATE admin SET session = '$session', time = '$time' WHERE 1");
}


//commented out because: Leagues are now stopped

//starts a new league
/*function start_new_league($conn)
{
    //first save all the neccessary data 
    //then reset the game for each user
    //then start a new league
    
    $query_get_users = "SELECT id, rank, highest_rank, balance FROM users";
    if($run_get_users = mysqli_query($conn, $query_get_users))
    {
        while($array_users = mysqli_fetch_assoc($run_get_users))
        {
            $user_id = $array_users['id'];
            $current_rank = $array_users['rank'];
            $highest_rank = $array_users['highest_rank'];
            $balance = $array_users['balance'];
            
            $temp_user = new User($user_id, $conn);
            $shares_valuation = $temp_user->get_valuation($conn);
            
            //update user badges(gold/silver/bronze etc)
            if($current_rank == 1)
            {
                mysqli_query($conn, "UPDATE users SET gold = gold + 1 WHERE id = '$user_id'");
            }
            elseif($current_rank == 2)
            {
                mysqli_query($conn, "UPDATE users SET silver = silver + 1 WHERE id = '$user_id'");
            }
            elseif($current_rank == 3)
            {
                mysqli_query($conn, "UPDATE users SET bronze = bronze + 1 WHERE id = '$user_id'");
            }
            elseif($current_rank >3 && $current_rank <= 10)
            {
                mysqli_query($conn, "UPDATE users SET top_10 = top_10 + 1 WHERE id = '$user_id'");
            }
            elseif($current_rank >10 && $current_rank <= 30)
            {
                mysqli_query($conn, "UPDATE users SET top_30 = top_30 + 1 WHERE id = '$user_id'");
            }
            
            //get the current league id (the max one has to be the current one)
            if($run_league = mysqli_query($conn, "SELECT MAX(id) as league_id FROM leagues"))
            {
                while($array_league = mysqli_fetch_assoc($run_league))
                {
                    $league_id = $array_league['league_id'];
                }
            }
            
            //now save things into the leagues_performances table
            $query_perf = "INSERT INTO leagues_performances(league_id, user_id, rank, highest_rank, balance, valuation_shares) VALUES('$league_id','$user_id','$current_rank', '$highest_rank','$balance','$shares_valuation')";
            
            if(!mysqli_query($conn, $query_perf))
                echo mysqli_error($conn)."<br>";
            
            //works so far 
            mysqli_query($conn, "UPDATE users SET rank ='500', highest_rank = '500' ");
        }   
         
    }
    
            //taking a backup of table transactions
            $query_backup_transactions_1 = "CREATE TABLE transactions_b_$league_id LIKE transactions"; 
            $query_backup_transactions_2 = "INSERT INTO transactions_b_$league_id SELECT * FROM transactions";
            
            if(!mysqli_query($conn, $query_backup_transactions_1))
                echo "a<br>";
            
            if(!mysqli_query($conn, $query_backup_transactions_2))
                echo "b<br>";
    
            //one week into the future from now
            $end_time = time() + 604800;
            
            //after restarting, start a new league
            $query_new_league = "INSERT INTO leagues(end_time) VALUES('$end_time')";            
            if(mysqli_query($conn, $query_new_league))
            {
               // echo "new league is here!<hr>";
            }
            else
                echo mysqli_error($conn);
    
             //delete all the things done by user, set user's balance to 500000
            $query_all = "DELETE FROM shares; DELETE FROM transactions; DELETE FROM orders; UPDATE users SET balance = 500000, message='Reset Successfull.', highest_rank = '500'";

            if(!mysqli_multi_query($conn, $query_all))
            {
                echo mysqli_error($conn);
                
            }
    
    
}*/
?>
