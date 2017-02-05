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
                <li><a href="winners.php" <?php if(basename($_SERVER['PHP_SELF']) == "winners.php") echo "class='active'"; ?>>Winners</a></li>
                <br>
                <li>
                    <a class="active" href="#" data-toggle="modal" data-target="#leagues"><div style="font-size: 11px;">League ends in:</div> <span id="countdown" style="font-size: 22px; padding-left:20px;" class="timer"></span><span style="font-size: 9px;"> &nbsp;&nbsp;(Click to Know)</span></a>
                </li>
 
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