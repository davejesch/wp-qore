<?php

function failed_login() {
     return __( 'The login information you have entered is incorrect.', 'wp-qore' );
}
 
add_filter('login_errors', 'failed_login');

?>