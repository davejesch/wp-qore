<?php


// shortcode in widgets
if(current_user_can("manage_options"))
{
    add_filter('widget_text', 'do_shortcode', 11);
}


?>