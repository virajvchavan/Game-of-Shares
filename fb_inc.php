<link href="css/bootstrap-social.css" rel="stylesheet">
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.8&appId=1859297917624873";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>

<div class="fb-share-button" id="facebook_share" data-href="http://gameofshares.esy.es" data-layout="button" data-size="large" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fdevelopers.facebook.com%2Fdocs%2Fplugins%2F&amp;src=sdkpreparse">Invite Your friends to play!</a></div>

<?php

//get the time in which league is going to end
if($run = mysqli_query($conn, "SELECT id, end_time FROM leagues ORDER BY id DESC LIMIT 1"))
{
    while($array = mysqli_fetch_assoc($run))
    {
        $end_time = $array['end_time'];
    }
}
else
    $end_time = $current_time;

$current_time = time();

if($end_time < $current_time)
{
    start_new_league($conn);
    
    $end_time += 604800;
}

$league_duration = $end_time - $current_time;

?>

<script>
var upgradeTime = <?php echo $league_duration; ?>;
var seconds = upgradeTime;
function timer() {
    var days        = Math.floor(seconds/24/60/60);
    var hoursLeft   = Math.floor((seconds) - (days*86400));
    var hours       = Math.floor(hoursLeft/3600);
    var minutesLeft = Math.floor((hoursLeft) - (hours*3600));
    var minutes     = Math.floor(minutesLeft/60);
    var remainingSeconds = seconds % 60;
    if (remainingSeconds < 10) {
        remainingSeconds = "0" + remainingSeconds; 
    }
    document.getElementById('countdown').innerHTML = days + ":" + hours + ":" + minutes + ":" + remainingSeconds;
    if (seconds == 0) {
        clearInterval(countdownTimer);
        document.getElementById('countdown').innerHTML = "Completed";
    } else {
        seconds--;
    }
}
var countdownTimer = setInterval('timer()', 1000);
</script>


<!-- Modal -->
<div id="leagues" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">What is a League?</h4>
      </div>
      <div class="modal-body">
        <p>A League is a <b>One Week</b> long Competition.<br>At the end of each League, you'll get a badge if you have a good Rank on the Leaderboard.<br><br>At the end of each league, every user's game will be reset.<br><br>
          The balance will be set to 500000 and Shares will be zero for everyone.<br><br>
          Your performance will be recorded for each league.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-dismiss="modal">Got it!</button>      
      </div>
    </div>

  </div>
</div>

<style>
    #facebook_share{
       bottom: 5px;
       right: 5px;
       position: fixed;
       z-index: 3000;
    }
</style>