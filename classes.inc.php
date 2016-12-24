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
        $query = "UPDATE users SET balance = $new_balance WHERE id = $this->id";
        
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
        
        //first check if this is a valid order to be placed
        
        //check if has enough balance to buy
        if($type == "buy")
        {
            //first get price of the share
            $company = new Company($company_id);
            $price = $company->get_company_price($conn);

            if($this->balance < $quantity*$price)
            {
                echo "<script>alert('You do not have enough balance to place this order'); </script>";
                return false;
            }
        }
        elseif($type == "sell")
        {
            //check if has enough shares to sell

            if($quantity > $this->get_user_quantity($conn, $company_id))
            {
                echo "<script>alert('You do not own enough shares to place this order'); </script>";
                return false;
            }
        
        }
        //set a random future timestamp for this order
        //get a random amount of time bet min and max
        $rand = rand(0, 10);
        $time = time();
        $time += $rand;

        $query = "INSERT INTO orders(user_id, company_id, quantity, type, limit_or_market, limit_price, time) VALUES('$this->id','$company_id','$quantity','$type','$limit_or_market','$limit_price','$time')";

        if(mysqli_query($conn, $query))
        {
            echo "Success";
            return true;
        }
        else
            echo "Error placing the order";
    }
    
    //get the quantity of shares for a company
    function get_user_quantity($conn, $company_id)
    {
        $query = "SELECT quantity FROM shares WHERE user_id = '$this->id' AND company_id = '$company_id'";
        if($run = mysqli_query($conn, $query))
        {
            if(mysqli_num_rows($run) < 1)
            {
                return 0;
            }
            while($array = mysqli_fetch_assoc($run))
            {
                $quantity = $array['quantity'];
            }
            
            return $quantity;
            
        }
    }
    
    //execute the orders for this user
    function executeOrders($conn)
    {
        //check with all the orders in the table orders
        $query_get_orders = "SELECT * FROM orders WHERE user_id = '$this->id'";
        if($run_get_orders = mysqli_query($conn, $query_get_orders))
        {
            if(mysqli_num_rows($run_get_orders) < 1)
            {
                return;
            }
            while($array = mysqli_fetch_assoc($run_get_orders))
            {
                $order_id = $array['id'];
                $company_id = $array['company_id'];
                $type = $array['type'];
                $quantity = $array['quantity'];
                $limit_or_market = $array['limit_or_market'];
                $limit_price = $array['limit_price'];
                $time = $array['time'];
                
                //get price of the share
                $company = new Company($company_id);
                $price = $company->get_company_price($conn);
                
               
                
                if($time > time())
                {
                    continue;
                }
                //check validity for limit orders
                if($limit_or_market == "limit" && (($type == "sell" && $limit_price  < $price) || ($type == "buy" && $limit_price >$price )))    
                {
                    continue;
                }
                
                $total_price = $quantity*$price;
                
                if($type == "buy")
                {
                    $this->balance -= $total_price;
                
                }
                if($type == "sell")
                {
                    $this->balance += $total_price;
                }
                
                //update user balance
                $this->set_balance($conn, $this->balance);
                
                //insert into transactions table
                if($type == "buy")
                    $query = "INSERT INTO transactions(user_id, company_id, quantity, price) VALUES ('$this->id', '$company_id', '$quantity', '$price')";
                elseif($type == "sell")
                    $query = "INSERT INTO transactions(user_id, company_id, quantity, price) VALUES ('$this->id', '$company_id', '-$quantity', '$price')";
                if(mysqli_query($conn, $query))
                {
                    echo "Transaction added. \n";
                }
                else
                    echo "Error transaction add";
                
                
                //insert into or update shares table
                
                    //check if some shares already there
                    $query = "SELECT * FROM shares WHERE company_id = '$company_id' AND user_id = '$this->id'"; 
                
                    if($run = mysqli_query($conn, $query))
                    {
                    
                        if(mysqli_num_rows($run) == 1)
                        {
                            //update shares quantity
                            if($type == "buy")
                                $query_update = "UPDATE shares SET quantity = quantity + '$quantity' WHERE company_id = '$company_id' AND user_id = '$this->id'";
                            elseif($type == "sell")
                                $query_update = "UPDATE shares SET quantity = quantity - '$quantity' WHERE company_id = '$company_id' AND user_id = '$this->id'";

                            if(mysqli_query($conn, $query_update))
                            {
                                echo "Updated quantity\n";
                            }
                            else
                                echo "Error updating shares";
                        }
                        elseif(mysqli_num_rows($run) == 0)
                        {

                            //insert new entry
                            if($type == "buy")
                            {
                                $query = "INSERT INTO shares(user_id, company_id, quantity) VALUES ('$this->id', '$company_id', '$quantity')";

                                if(mysqli_query($conn, $query))
                                {
                                    echo "Inserted shares";
                                }
                                else
                                    echo "Couldnt insert shares";
                            }
                        }
                  
                    }
                
                
                //now delete from the orders table
                $query_delete = "DELETE FROM orders WHERE id = '$order_id'";
                
                if(mysqli_query($conn, $query_delete))
                {
                    echo "Deleted from orders";
                }
                else
                {
                    echo "Error deleting order";
                }
                
                
            }
        }
    }
    
}



class Company
{
    var $id;
    var $name;
    var $price;
    
    function __construct($new_id)
    {
        $this->id = $new_id;
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
    
    function get_company_name($conn)
    {   
        $query = "SELECT name FROM companies WHERE id = $this->id";

        if($run = mysqli_query($conn, $query))
        {
            while($array = mysqli_fetch_assoc($run))
            {
                $name = $array['name'];
            }

            return $name;
        }
    }
    
     //get company price from its id
    function get_company_price($conn)
    {
        $query = "SELECT price FROM companies WHERE id='$this->id'";
        if($run = mysqli_query($conn, $query))
        {
            while($array = mysqli_fetch_assoc($run))
            {
                $price = $array['price'];
            }
            return $price;
        }
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