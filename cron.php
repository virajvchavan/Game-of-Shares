<?php

include "classes.inc.php";
include "conn.inc.php";
include "functions.index.php";   

//change the share price of companies (from functions.index.php)
changePrices($conn, $time_limit_for_company, $price_limit_for_company);

echo "Cron zala!";

?>