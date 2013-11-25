<?php ?>

<h1>Access Denied. Your IP has been logged!</h1>

<?php 
 //Gets the IP address
 $ip = getenv("REMOTE_ADDR") ; 
 Echo "Your IP is " . $ip; 
 ?> 