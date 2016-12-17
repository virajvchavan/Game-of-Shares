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
    
    function set_balance($new_balance)
    {
        $this->balance = $new_balance;
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
        echo $this->id;
    }
    
    function get_name()
    {
        echo $this->name;
    }
    
    function get_balance()
    {
        echo $this->balance;
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
        echo $this->id;
    }
    
    function get_name()
    {
        echo $this->name;
    }
    
    function get_price()
    {
        echo $this->price;
    }
    
    function set_price($new_price)
    {
        $this->price = $new_price;
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
    
    function get_limit_price()
    {
        echo $this->limit_price;
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