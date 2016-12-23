<?php

ob_start();
session_start();

$servername = "127.0.0.1";
$username_db = "root";
$password = "";
$dbname = "game_of_shares";
// Create connection

$conn = new mysqli($servername, $username_db, $password, $dbname);
// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

function isLoggedIn()
{
	if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))
	{   
		return true;
	}
	else
	{
		return false;
	}
}

if(isLoggedIn())
{
    $balance = getBalance($conn, $_SESSION['user_id']);
        
    //create the User object
    $user = new User($_SESSION['user_name'], $_SESSION['user_id'], $balance);
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

?>