<?php

function failed_login() {
     return 'The login information you have entered is incorrect.';
}
 
add_filter('login_errors', 'failed_login');

?>