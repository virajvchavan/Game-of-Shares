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
    if($hours >=8 && $hours < 20)
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

function start_new_league($conn)
{
    //first save all the neccessary data 
    //then reset the game for each user
    //then start a new league
    
    $query = "SELECT id, rank, highest_rank, balance FROM users";

    $all_users = array();
    //get data of each user
    if($run = mysqli_query($conn, $query))
    {
        //for each user
        while($array = mysqli_fetch_assoc($run))
        {
            array_push($all_users, $array);
        }
        mysqli_free_result($run);
    }
    
        foreach($all_users as $key=>$value)
        {
                
            $user_id = $value['id'];
            $current_rank = $value['rank'];
            $highest_rank = $value['highest_rank'];
            $user_balance = $value['balance'];
            
            //update user badges(gold/silver/bronze etc)
            if($current_rank == 1)
            {
                mysqli_query($conn, "UPDATE users SET gold = gold + 1");
            }
            elseif($current_rank == 2)
            {
                mysqli_query($conn, "UPDATE users SET silver = silver + 1");
            }
            elseif($current_rank == 3)
            {
                mysqli_query($conn, "UPDATE users SET bronze = bronze + 1");
            }
            elseif($current_rank >3 && $current_rank <= 10)
            {
                mysqli_query($conn, "UPDATE users SET top_10 = top_10 + 1");
            }
            elseif($current_rank >10 && $current_rank <= 30)
            {
                mysqli_query($conn, "UPDATE users SET top_10 = top_10 + 1");
            }
            
            //get the current league id (the max one has to be the current one)
            if($run_league = mysqli_query($conn, "SELECT MAX(id) as league_id FROM leagues"))
            {
                while($array_league = mysqli_fetch_assoc($run_league))
                {
                    $league_id = $array_league['league_id'];
                }
                mysqli_free_result($run_league);
            }
            
            $temp_user = new User($user_id, $conn);
            
            $shares_valuation = $temp_user->get_valuation($conn);
            
            //now save things into the leagues_performances table
            $query_perf = "INSERT INTO leagues_performances(league_id, user_id, rank, highest_rank, balance, valuation_shares) VALUES('$league_id','$user_id','$current_rank', '$highest_rank','$user_balance','$shares_valuation')";
            
            if(mysqli_query($conn, $query_perf))
            {
                
            }
            else
               // echo mysqli_error($conn);
            
            
            //$query_backup_transactions = "CREATE TABLE transactions_b_$league_id LIKE transactions";
            
            //$query_backup_transactions = "CREATE TABLE 'transactions_b_$league_id' SELECT * FROM transactions; CREATE TABLE 'shares_b_$league_id' SELECT * FROM shares; CREATE TABLE 'orders_b_$league_id' SELECT * FROM orders; ";
            
           // if(mysqli_query($conn, $query_backup_transactions))
            {
                
            }
            
            
            //this deletes all the transactions, shares, orders of all users
            $temp_user->restartGame($conn);
            
            //one week into the future from now
            $end_time = time() + 604800;
            
            
            //after restarting, start a new league
            $query_new_league = "INSERT INTO leagues(end_time) VALUES('$end_time')";            
            if(mysqli_query($conn, $query_new_league))
            {
                
            }
            else
            {
               // echo mysqli_error($conn);
            }

        
        }
}


?>


