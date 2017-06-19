<?php
if($run_league_no = mysqli_query($conn, "SELECT MAX(id) as league_n FROM leagues"))
{
    while($array = mysqli_fetch_assoc($run_league_no))
    {
        $league_no = $array['league_n'];
    }
}

?>

<ul class="sidebar-nav">
                <li id="balance">
                    Balance: <?php echo number_format($user->get_balance($conn)); ?>
                </li>
                <li id="balance" style="font-size: 19px;">
                    Total Value: <?php echo number_format($user->get_valuation($conn) + $user->balance)  ; ?>
                </li>
                <li>
                    <a href="index.php" <?php if(basename($_SERVER['PHP_SELF']) == "index.php") echo "class='active'"; ?>>Dashboard</a>
                    
                </li>
                <li>
                    <a href="orders.php" <?php if(basename($_SERVER['PHP_SELF']) == "orders.php") echo "class='active'"; ?>>Pending Orders</a>
                </li>
                <li>
                    <a href="trades.php" <?php if(basename($_SERVER['PHP_SELF']) == "trades.php") echo "class='active'"; ?>>Trade Book</a>
                </li>
                <li>
                    <a href="leaders.php" <?php if(basename($_SERVER['PHP_SELF']) == "leaders.php") echo "class='active'"; ?>>LeaderBoard</a>
                </li>
                <li><a href="winners.php" <?php if(basename($_SERVER['PHP_SELF']) == "winners.php") echo "class='active'"; ?>>League Winners</a></li>
                <br>
                <li>
                    <a class="active" href="#" data-toggle="modal" data-target="#leagues"><div style="font-size: 13px;">Leagues are now closed!</a>
                </li>
                <br>
                <li>
                    <a href="help.php" <?php if(basename($_SERVER['PHP_SELF']) == "help.php") echo "class='active'"; ?>>Help</a>
                </li>
                <li>
                    <a href="about.php" <?php if(basename($_SERVER['PHP_SELF']) == "about.php") echo "class='active'"; ?>>About</a>
                </li>
                <li>
                    <a href="profile.php" <?php if(basename($_SERVER['PHP_SELF']) == "profile.php") echo "class='active'"; ?>>Your Profile</a>
                </li>
                <li>
                    <a href="feedback.php" <?php if(basename($_SERVER['PHP_SELF']) == "feedback.php") echo "class='active'"; ?>>Feedback</a>
                </li>
                <li>
                    <a href="logout.php" >Logout (<?php echo $user->get_name($conn); ?>)</a>
                </li>
            </ul>