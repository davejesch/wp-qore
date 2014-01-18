<?php

add_action('admin_menu','hide_admin_notices');
function hide_admin_notices() {
    remove_action( 'admin_notices', 'update_nag', 3 );
}

add_action('admin_menu', 'remove_menus', 102);
function remove_menus() {
	global $submenu;
	remove_submenu_page ( 'index.php', 'update-core.php' );
}

remove_action( 'load-update-core.php', 'wp_update_core' );
add_filter( 'pre_site_transient_update_core', create_function( '$a', "return null;" ) );
remove_action( 'load-update-core.php', 'wp_update_themes' );
add_filter( 'pre_site_transient_update_themes', create_function( '$a', "return null;" ) );
remove_action( 'load-update-core.php', 'wp_update_plugins' );
add_filter( 'pre_site_transient_update_plugins', create_function( '$a', "return null;" ) );

?>
