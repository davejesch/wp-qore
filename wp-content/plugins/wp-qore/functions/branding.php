<?php

// customize left wp-admin footer text
function custom_admin_footer() {
    echo __( 'Copyright ', 'wp-qore' );
    echo date('Y');
    echo ' ';
    bloginfo('name');
    echo ' | '; 
    bloginfo('description'); 
} 
add_filter('admin_footer_text', 'custom_admin_footer');

// remove wp version from admin footer
function replace_footer_version() {
    echo '<a target="_blank" href="http://wpqore.com/" title="goto wpqore.com">';
    echo __( 'WP Qore: ', 'wp-qore' );
    wpqoreplugv();
    echo '</a>';
}
add_filter( 'update_footer', 'replace_footer_version', '1234');

?>