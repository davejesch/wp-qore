<?php
/*
Plugin Name: WP Qore
Plugin URI: http://wpqore.com/
Description: WP Qore, formerly known as Qore Functions, is a WordPress plugin that provides additional security, performance functionality, developer tools that can be turned on or off at any time.
Version: 1.1.1
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
    echo '1.1.1';
}

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// load settings and functions
require_once(dirname(__FILE__)."/settings.php");
require_once(dirname(__FILE__)."/functions.php");

// load defaults
wpqorefunc_load_defaults();

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
if (get_option("wpqorefunc_login-sec")=='checked') include_once('login-security.php');
if (get_option("wpqorefunc_dashboard")=='checked') include_once('functions/dashboard_func.php');
if (get_option("wpqorefunc_gzip")=='checked') include_once('functions/gzip.php');
if (get_option("wpqorefunc_wphidenag")=='checked') include_once('functions/wphidenag.php');
if (get_option("wpqorefunc_plug-edit")=='checked') define('DISALLOW_FILE_EDIT', true);
if (get_option("wpqorefunc_shortcode")=='checked') include_once('functions/shortcode.php');
if (get_option("wpqorefunc_phpwidget")=='checked') include_once('functions/php-widget.php');

// enable import/export widgets
if (get_option("wpqorefunc_exportwidget")=='checked') {
	require('functions/widget-expodata.php');
	add_action( 'init', array( 'Widget_EXPOData', 'init' ) );
}
