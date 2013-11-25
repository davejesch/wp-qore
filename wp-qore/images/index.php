<?php 
/* STOP SNOOPY EYES FROM VIEWING THE INDEX */
?>

<h1>Access Denied. Your IP has been logged!</h1>

<?php 
 //Gets the IP address
 $ip = getenv("REMOTE_ADDR") ; 
 echo "Your IP is " . $ip; 
 ?> 