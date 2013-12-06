<?php
/*
Plugin Name: WP Qore
Plugin URI: http://wpqore.com/
Description: WP Qore, formerly known as Qore Functions, is a WordPress plugin that provides additional security, performance functionality, developer tools that can be turned on or off at any time.
Version: 1.3.7
Author: Jason Jersey
Author URI: http://twitter.com/degersey
License: GNU GPL 3.0
License URI: http://www.gnu.org/licenses/gpl.html
*/


/**
 * THERE IS NO WARRANTY FOR THIS PLUGIN, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT 
 * WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE 
 * PLUGIN "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, 
 * BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A 
 * PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PLUGIN IS 
 * WITH YOU. SHOULD THE PLUGIN PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY 
 * SERVICING, REPAIR OR CORRECTION.
 * 
 */


// wp-qore version
function wpqoreplugv() {
    echo '1.3.7';
}

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// load settings and functions
require_once(dirname(__FILE__)."/settings.php");
require_once(dirname(__FILE__)."/functions.php");

// login logo
if (get_option("wpqorefunc_login_logo")=='checked') {
    add_filter( 'login_headerurl', 'wpqorefunc_my_login_logo_url' );
    add_filter( 'login_headertitle', 'wpqorefunc_my_login_logo_url_title' );
    add_action( 'login_enqueue_scripts', 'wpqorefunc_my_login_logo' );
}

// restrict wp-admin access
if (get_option("wpqorefunc_forbid_wpadmin")=='checked') {
    add_action('init', 'wpqorefunc_redirect_to_front');
}

// remove version tags from js & css
if (get_option("wpqorefunc_removeversion")=='checked') {
    add_filter( 'style_loader_src', 'wpqorefunc_remove_cssjs_ver', 10, 2 );
    add_filter( 'script_loader_src', 'wpqorefunc_remove_cssjs_ver', 10, 2 );
}

if (get_option("wpqorefunc_theme_directory")!='' and file_exists(WP_CONTENT_DIR.'/'.get_option("wpqorefunc_theme_directory")) )  wpqorefunc_theme_directory_fun();
//  add_action("init","wpqorefunc_theme_directory_fun");

// admin bar removal
if (get_option("wpqorefunc_showadminbar")=='checked') add_action("init","wpqorefunc_sabf");

// remove wp tags from meta
if (get_option("wpqorefunc_showadminbar")=='checked'){
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'index_rel_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'start_post_rel_link', 10, 0);
    remove_action('wp_head', 'parent_post_rel_link', 10, 0);
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
}

// change default jquery
if (get_option("wpqorefunc_reregjquery")=='checked') add_action( 'wp_enqueue_scripts', 'wpqorefunc_my_scripts_method' );

// enable wp-admin menu
add_action('admin_menu', 'wpqorefunc_fp_admin');

// on & off checkboxes
if (get_option("wpqorefunc_2steplogin")=='checked') add_action( 'init', 'wpqorefunc_redirect_admin');
if (get_option("wpqorefunc_compresshtml")=='checked') include_once('functions/compress_html.php');
if (get_option("wpqorefunc_dashboard")=='checked') include_once('functions/dashboard_func.php');
if (get_option("wpqorefunc_gzip")=='checked') include_once('functions/gzip.php');
if (get_option("wpqorefunc_wphidenag")=='checked') include_once('functions/wphidenag.php');
if (get_option("wpqorefunc_plug-edit")=='checked') define('DISALLOW_FILE_EDIT', true);
if (get_option("wpqorefunc_shortcode")=='checked') include_once('functions/shortcode.php');
if (get_option("wpqorefunc_phpwidget")=='checked') include_once('functions/php-widget.php');
if (get_option("wpqorefunc_coreupdate")=='checked') include_once('functions/core-update.php');
if (get_option("wpqorefunc_post_revisions")=='checked') define('WP_POST_REVISIONS', false);
if (get_option("wpqorefunc_force_ssl_admin")=='checked') define('FORCE_SSL_ADMIN', true);
if (get_option("wpqorefunc_sec_advisor")=='checked') include_once('sec-advisor.php');

// enable import/export widgets
if (get_option("wpqorefunc_exportwidget")=='checked') {
	require('functions/widget-expodata.php');
	add_action( 'init', array( 'Widget_EXPOData', 'init' ) );
}

// enqueue dashboard css
function load_dash_wp_admin_style(){
    wp_register_style( 'custom_wp_admin_css', plugins_url( 'css/dashboard.css' , __FILE__ ), false, '1.0.0' );
    wp_enqueue_style( 'custom_wp_admin_css' );
}
add_action('admin_enqueue_scripts', 'load_dash_wp_admin_style');

// enqueue settings page css
function load_settings_wp_admin_style(){
    wp_register_style( 'custom2_wp_admin_css', plugins_url( 'css/on-off.css' , __FILE__ ), false, '1.0.0' );
    wp_enqueue_style( 'custom2_wp_admin_css' );
}
add_action('admin_enqueue_scripts', 'load_settings_wp_admin_style');

// runs on wp-qore activation
function wpqore_plug_activate() {

    update_option("wpqorefunc_secret_arg","secretkey1");
    update_option("wpqorefunc_showadminbar","0");
    update_option("wpqorefunc_reregjquery","0");
    update_option("wpqorefunc_removeversion","checked");
    update_option("wpqorefunc_rmheader","0");
    update_option("wpqorefunc_2steplogin","0");
    update_option("wpqorefunc_compresshtml","0");
    update_option("wpqorefunc_dashboard","checked");
    update_option("wpqorefunc_gzip","0");
    update_option("wpqorefunc_wphidenag","0");
    update_option("wpqorefunc_plug-edit","0");
    update_option("wpqorefunc_shortcode","0");
    update_option("wpqorefunc_phpwidget","0");
    update_option("wpqorefunc_post_revisions","0");
    update_option("wpqorefunc_force_ssl_admin","0");
    update_option("wpqorefunc_coreupdate","0");
    update_option("wpqorefunc_exportwidget","0");
    update_option("wpqorefunc_login_logo","0");
    update_option("wpqorefunc_sec_advisor","checked");
    update_option("wpqorefunc_dash_tabs","checked");
    update_option("wpqorefunc_theme_directory","templates");
    update_option("wpqorefunc_login_logo_url", "");

}
register_activation_hook( __FILE__, 'wpqore_plug_activate' );

// runs on wp-qore deactivation
function wpqore_plug_deactivate() {

    update_option("wpqorefunc_secret_arg","");
    update_option("wpqorefunc_showadminbar","");
    update_option("wpqorefunc_reregjquery","");
    update_option("wpqorefunc_removeversion","");
    update_option("wpqorefunc_rmheader","");
    update_option("wpqorefunc_2steplogin","");
    update_option("wpqorefunc_compresshtml","");
    update_option("wpqorefunc_dashboard","");
    update_option("wpqorefunc_gzip","");
    update_option("wpqorefunc_wphidenag","");
    update_option("wpqorefunc_plug-edit","");
    update_option("wpqorefunc_shortcode","");
    update_option("wpqorefunc_phpwidget","");
    update_option("wpqorefunc_post_revisions","");
    update_option("wpqorefunc_force_ssl_admin","");
    update_option("wpqorefunc_coreupdate","");
    update_option("wpqorefunc_exportwidget","");
    update_option("wpqorefunc_login_logo","");
    update_option("wpqorefunc_sec_advisor","");
    update_option("wpqorefunc_dash_tabs","");
    update_option("wpqorefunc_theme_directory","");
    update_option("wpqorefunc_login_logo_url", "");

}
register_deactivation_hook( __FILE__, 'wpqore_plug_deactivate' );
