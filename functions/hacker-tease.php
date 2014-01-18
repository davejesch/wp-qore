<?php //login 404 page : begin

echo '
<div style="position: absolute;top: 0;bottom: 0;left: 0;right: 0;width: 50%;height: 90%;margin: auto">

    <div style="font-size:24px;text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;padding-bottom:20px">
    Uh oh, you got caught with your hand in the cookie jar. Try again!
    </div>

    <iframe width="640" height="480" src="//www.youtube.com/embed/qpMvS1Q1sos?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>

';

//Gets the IP address
 $ip = getenv("REMOTE_ADDR") ; 
 echo "<div style='text-align:center;padding-top:20px'>Your IP address is <b>" . $ip; 
 echo '</b></div></div>';

//login 404 page : end

?>