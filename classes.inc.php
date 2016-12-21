<?php

class User
{
    var $id;
    var $name;
    var $balance;
    
    function __construct($new_name, $new_id, $new_balance)
    {
        $this->id = $new_id;
        $this->name = $new_name;
        $this->balance = $new_balance;
    }
    
    function set_balance($conn, $new_balance)
    {
        $this->balance = $new_balance;
        
        //also need to update in the database
        $query = "UPDATE users SET balance = $new_balance WHERE id=$id";
        
        if(mysqli_query($conn, $query))
        {
            echo "Balance Updated";
        }
        else
            echo "Error balance update in db";
    }
    
    /*function set_id($new_id)
    {
        $this->id = $new_id;
    }
    
    function set_name($new_name)
    {
        $this->name = $new_name;
    }
    
    function set_balance($new_balance)
    {
        $this->balance = $new_balance;
    }*/
        
    function get_id()
    {
        return $this->id;
    }
    
    function get_name()
    {
        return $this->name;
    }
    
    function get_balance()
    {
        return $this->balance;
    }
    
    
    //function to place the order
    function placeOrder($conn, $type, $company_id, $quantity, $limit_or_market, $limit_price)
    {

        //get a random amount of time bet min and max
        $rand = rand(0, 120);
        $time = time();
        $time += $rand;

        $query = "INSERT INTO orders(user_id, company_id, quantity, type, limit_or_market, limit_price, time) VALUES('$this->id','$company_id','$quantity','$type','$limit_or_market','$limit_price','$time')";

        if(mysqli_query($conn, $query))
        {
            echo "Success";
        }
        else
            echo "Error placing the order";
    }
    
    //execute the orders for this user
    function executeOrders($conn)
    {
        //check with all the orders in the table orders
        $query = "SELECT * FROM orders WHERE user_id = $this->id";
        
        if($run = mysqli_query($conn, $query))
        {
            while($array = mysqli_fetch_assoc($run))
            {
                $company_id = $array['company_id'];
                $type = $array['type'];
                $quantity = $array['quantity'];
                $limit_or_market = $array['limit_or_market'];
                $limit_price = $array['limit_price'];
                
                $price = get_company_price($company_id);
            }
        }
    }
    
}



class Company
{
    var $id;
    var $name;
    var $price;
    
    function __construct($new_name, $new_id, $new_price)
    {
        $this->id = $new_id;
        $this->name = $new_name;
        $this->price = $new_price;
    }
    
    function get_id()
    {
        return $this->id;
    }
    
    function get_name()
    {
        return $this->name;
    }
    
    function get_price()
    {
        return $this->price;
    }
    
    function set_price($conn, $new_price)
    {
        $this->price = $new_price;
        
        //also need to update in the database
        $query = "UPDATE companies SET price = $new_price WHERE id=$id";
        
        if(mysqli_query($conn, $query))
        {
            echo "Price Updated";
        }
        else
            echo "Error price update in db";
    }
    
}



class Order
{
    var $id;
    var $user_id;
    var $company_id;
    var $quantity;
    var $type;
    var $limit_price;
    
    function __construct($new_id, $new_user_id, $new_company_id, $new_quantity, $new_type, $new_limit_price)
    {
        $this->id = $new_id;
        $this->company_id = $new_company_id;
        $this->user_id = $new_user_id;
        $this->type = $new_type;
        $this->limit_price = $new_limit_price;
    }
    
    function get_id()
    {
        return $this->id;
    }
    
    function get_company_id()
    {
        return $this->company_id;
    }
    
    function get_user_id()
    {
        return $this->user_id;
    }
    
    function get_limit_price()
    {
        return $this->limit_price;
    }
    
}


class Transaction
{
    var $company_id;
    var $id;
    var $user_id;
    var $quantity;
    var $price;
    
    function __construct($new_id, $new_user_id, $new_company_id, $new_quantity, $new_type, $new_price)
    {
        $this->id = $new_id;
        $this->company_id = $new_company_id;
        $this->user_id = $new_user_id;
        $this->type = $new_type;
        $this->price = $new_price;
        $this->quantity = $new_quantity;
    }
    
    function get_id()
    {
        echo $this->id;
    }
    
    function get_company_id()
    {
        echo $this->company_id;
    }
    
    function get_user_id()
    {
        echo $this->user_id;
    }
    
    function getprice()
    {
        echo $this->price;
    }
    function get_quantity()
    {
        echo $this->quantity;
    }
}
?>