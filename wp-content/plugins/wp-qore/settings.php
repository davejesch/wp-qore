<?php

function wpqorefunc_fp_settings() {

$wpconfigchange='
//change "files" to whatever you want and then rename wp-content folder to this name 
define(\'WP_CONTENT_FOLDERNAME\', \'files\');
define(\'WP_CONTENT_DIR\', ABSPATH . WP_CONTENT_FOLDERNAME );
define(\'WP_CONTENT_URL\', \''.home_url('/').'\'.WP_CONTENT_FOLDERNAME);
define(\'WP_PLUGIN_DIR\', WP_CONTENT_DIR . \'/\'.\'modules\' ); //change "modules" to new name
//then rename your plugins directory to new name.
define(\'WP_PLUGIN_URL\', WP_CONTENT_URL.\'/\'.\'modules\'); //change "modules" to new name
';

wpqorefunc_is_checked("wpqorefunc_showadminbar");
wpqorefunc_is_checked("wpqorefunc_reregjquery");
wpqorefunc_is_checked("wpqorefunc_removeversion");
wpqorefunc_is_checked("wpqorefunc_rmheader");
wpqorefunc_is_checked("wpqorefunc_2steplogin");
wpqorefunc_is_checked("wpqorefunc_compresshtml");
wpqorefunc_is_checked("wpqorefunc_dashboard");
wpqorefunc_is_checked("wpqorefunc_wphidenag");
wpqorefunc_is_checked("wpqorefunc_plug-edit");
wpqorefunc_is_checked("wpqorefunc_shortcode");
wpqorefunc_is_checked("wpqorefunc_exportwidget");
wpqorefunc_is_checked("wpqorefunc_hide-adminbar-sub");
wpqorefunc_is_checked("wpqorefunc_phpwidget");
wpqorefunc_is_checked("wpqorefunc_post_revisions");
wpqorefunc_is_checked("wpqorefunc_coreupdate");
wpqorefunc_string_setting("wpqorefunc_secret_arg",'secretkey1');
wpqorefunc_string_setting("wpqorefunc_login_logo_url",'');
wpqorefunc_string_setting("wpqorefunc_theme_directory",'templates');
wpqorefunc_string_setting("wpqorefunc_exceptional_url",'');
wpqorefunc_is_checked("wpqorefunc_login_logo");
wpqorefunc_is_checked("wpqorefunc_forbid_wpadmin");
wpqorefunc_is_checked("wpqorefunc_sec_advisor");
//wpqorefunc_is_checked("wpqorefunc_cache_assistance");
wpqorefunc_is_checked("wpqorefunc_fold_menu");
wpqorefunc_is_checked("wpqorefunc_dashboard_switch");
wpqorefunc_string_setting("wpqorefunc_custom_dashboard",'');
wpqorefunc_is_checked("wpqorefunc_allow_major_auto_core_updates");
wpqorefunc_is_checked("wpqorefunc_allow_minor_auto_core_updates");
wpqorefunc_is_checked("wpqorefunc_auto_update_plugin");
wpqorefunc_is_checked("wpqorefunc_auto_update_theme");
wpqorefunc_is_checked("wpqorefunc_auto_core_update_send_email");
wpqorefunc_is_checked("wpqorefunc_automatic_updates_send_debug_email");
wpqorefunc_is_checked("wpqorefunc_auto_update_translation");
wpqorefunc_is_checked("wpqorefunc_dropbox_mod");
wpqorefunc_is_checked("wpqorefunc_wpadminbar_opacity");
wpqorefunc_is_checked("wpqorefunc_dash_stats");
wpqorefunc_is_checked("wpqorefunc_dash_controls");
wpqorefunc_is_checked("wpqorefunc_dash_posts");
wpqorefunc_is_checked("wpqorefunc_dash_pages");
wpqorefunc_is_checked("wpqorefunc_dash_serverinfo");
wpqorefunc_is_checked("wpqorefunc_dash_about");
//wpqorefunc_string_setting("wpqorefunc_1st_pass"); //disabled since 1.0.0

?>

<div class="wrap">
<div id="icon-options-general" class="icon32"><br></div><h2><?php _e( 'WP Qore Options', 'wp-qore' ); ?></h2>

<form action="" method="post">

<div id="poststuff">
<div class="postbox">
<table class="form-table">
<tbody>

<h3><?php _e( 'Login Settings', 'wp-qore' ); ?></h3>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Customize login page', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_login_logo">
<div class="switch toggle3">
<input name="wpqorefunc_login_logo" type="checkbox" id="wpqorefunc_login_logo" value="1" <?php echo get_option("wpqorefunc_login_logo");?>>
<label><i></i></label>
</div>
<i><?php _e( 'Change login page logo and link (Default is, WordPress)', 'wp-qore' ); ?></i>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Login logo image url', 'wp-qore' ); ?></label>
</th>

<td>
<input type="text" name="wpqorefunc_login_logo_url" style="width:420px" value="<?php echo get_option("wpqorefunc_login_logo_url");?>" placeholder="http://">
<br><?php _e( 'Type url of image for login page. Make sure it is approximately 320px width x 80px height.', 'wp-qore' ); ?>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Conceal login page', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_2steplogin">
<div class="switch toggle3">
<input name="wpqorefunc_2steplogin" type="checkbox" id="wpqorefunc_2steplogin" value="1" <?php echo get_option("wpqorefunc_2steplogin");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to lock login page", 'wp-qore' ); ?></i><br><br>
<?php _e( 'Once enabled, your example.com/wp-admin url will return a 404 error. The user who knows the secret key can access. For example, when example.com/wp-admin returns 404, example.com/wp-admin?secretkey1 will work. Remember, you should  change the secret key from secretkey1 to something unique and that you can always change this secret key in the future.', 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Login secret key', 'wp-qore' ); ?></label> (<a target="_blank" href="<?php echo WP_PLUGIN_URL.'/'.basename( dirname( __FILE__ ) ).'/example/screenshot_01.png'; ?>" title="<?php _e( 'click to see example...', 'wp-qore' ); ?>"><b>?</b></a>)</th>
<td>
 <input type="text" name="wpqorefunc_secret_arg" value="<?php echo get_option("wpqorefunc_secret_arg");?>" placeholder="<?php _ex( 'for url get parameter', 'placeholder text', 'wp-qore' ); ?>">

<br><?php
printf(
	__('Only %s will work, otherwise, included default wp-admin address will return 404.', 'wp-qore'),
	home_url().'/wp-admin?'. get_option("wpqorefunc_secret_arg") );
?><br>
	<br>
	<a href="javascript://" id="wpqorefunc_cr_exc_url"><?php _e( 'Add custom login page exception', 'wp-qore' ); ?></a>
	<br>
<div id="wpqorefunc_cr_exc_div" style="display:none;margin-top:4px">
<?php _e( 'If you have custom login page (for example if you are using any community plugin such as Buddypress and your users log in to their profile via custom url such as <i>http://example.com/myloginpage</i> ) and you want that url continue working properly without wpqorefunc secret key, then just add that url to following input field.', 'wp-qore' ); ?>
	<br><input style="margin-bottom:6px;width:300px" type="text" name="wpqorefunc_exceptional_url" placeholder="http://" value="<?php echo get_option("wpqorefunc_exceptional_url");?>"><br>

<?php _e( 'And then if you want to block your wp-admin for logged in <i>subscriber users</i>, check the following checkbox', 'wp-qore' ); ?><br>

<label for="wpqorefunc_forbid_wpadmin" >
<div class="switch toggle3">
	<input type="checkbox" name="wpqorefunc_forbid_wpadmin" value="1" <?php echo get_option("wpqorefunc_forbid_wpadmin");?>>
<label><i></i></label>
</div>
<i><?php _e( 'Block access to wp-admin for Logged in subscribers (all their requests will be redirected to homepage).', 'wp-qore' ); ?></i></label> </div>

<script>
jQuery("#wpqorefunc_cr_exc_url").click(function () {

jQuery("#wpqorefunc_cr_exc_div").toggle("slow");

});
</script>
</label>
</td>
</tr>

</tbody></table>
</div></div>

<p style="border-bottom: 1px dashed #CCCCCC;padding-bottom: 20px">
<input type="hidden" name="wpqorefunc_settings" value="1">
<input type="submit" class="button button-primary" value="Save changes">
</p>

<div id="poststuff">
<div class="postbox">
<table class="form-table">
<tbody>

<h3><?php _e( 'Source Obfuscation', 'wp-qore' ); ?></h3>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'New theme directory', 'wp-qore' ); ?></label></th>
<td>
<input type="text" name="wpqorefunc_theme_directory" style="width:420px" value="<?php echo get_option("wpqorefunc_theme_directory");?>" placeholder="<?php _ex( 'theme directory', 'placeholder text', 'wp-qore' ); ?>">
<br><?php 
printf(
	__( 'Create folder called <i>%s</i> in %s folder and then paste there any inactive theme folder you will need to use.', 'wp-qore' ),
	get_option("wpqorefunc_theme_directory"),
	WP_CONTENT_DIR
);
?>	
<br><br>
<b><?php _e( 'Warning!', 'wp-qore' ); ?></b>
<?php
printf( 
	__( "Don't cut/paste active theme from <i>themes</i> folder to <i>%s</i> folder. Do it only with inactive themes. First deactivate theme, cut & paste, then reactivate.", 'wp-qore' ),
	get_option("wpqorefunc_theme_directory")
);
?>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Unique source code', 'wp-qore' ); ?></label></th>
<td>
<?php
printf( 
	__( "Go to your file manager (or ftp), open your wp-config.php file and add the following lines there. Then, you will need to rename the wp-content and plugin folders to the new names in which you set in the code below. Copy the following code (below) and paste the code onto the top of your wp-config.php file, place it just after the initial opening &lt;?php tag (see <a target=\"_blank\" href=\"%s\" >before</a> and <a target=\"_blank\" href=\"%s\" >after</a> examples).<br><br> <b>Please be attentive. If you don't know what something is for, please don't do it.</b>", 'wp-qore' ),
WP_PLUGIN_URL.'/'.basename( dirname( __FILE__ ) ).'/example/screenshot_02.png',
WP_PLUGIN_URL.'/'.basename( dirname( __FILE__ ) ).'/example/screenshot_03.png'
); ?>

<pre style="margin-top:10px;background:#FFFFFF;padding:10px;border: 1px dashed #CCCCCC;"><?php echo $wpconfigchange; ?>
</pre>
</td>
</tr>
</tbody></table>
</div></div>

<p style="border-bottom: 1px dashed #CCCCCC;padding-bottom: 20px">
<input type="hidden" name="wpqorefunc_settings" value="1">
<input type="submit" class="button button-primary" value="Save changes">
</p>

<div id="poststuff">
<div class="postbox">
<table class="form-table">
<tbody>

<h3><?php _e( 'Dashboard Settings', 'wp-qore' ); ?></h3>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Replace dashboard', 'wp-qore' ); ?></label> (<a target="_blank" href="<?php echo WP_PLUGIN_URL.'/'.basename( dirname( __FILE__ ) ).'/example/screenshot_04.png'; ?>" title="<?php _e( 'click to see example...', 'wp-qore' ); ?>"><b>?</b></a>)</th>
<td>
<label for="wpqorefunc_dashboard">
<div class="switch toggle3">
<input name="wpqorefunc_dashboard" type="checkbox" id="wpqorefunc_dashboard" value="1" <?php echo get_option("wpqorefunc_dashboard");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable (Recommended)", 'wp-qore' ); ?></i><br><br>
<?php _e( "When 'On', this will replace the standard wp-admin dashboard with a new one that is clean and lightweight.", 'wp-qore' ); ?>
<br />
<div style="border: 1px dashed #CCCCCC;margin-top:10px;padding:10px">
<b>Dashboard Sections:</b> (check to hide)<br />
<br />
<input name="wpqorefunc_dash_stats" type="checkbox" id="wpqorefunc_dash_stats" value="1" <?php echo get_option("wpqorefunc_dash_stats");?>> Stats

<input name="wpqorefunc_dash_controls" type="checkbox" id="wpqorefunc_dash_controls" value="1" <?php echo get_option("wpqorefunc_dash_controls");?>> Controls

<input name="wpqorefunc_dash_posts" type="checkbox" id="wpqorefunc_dash_posts" value="1" <?php echo get_option("wpqorefunc_dash_posts");?>> Posts

<input name="wpqorefunc_dash_pages" type="checkbox" id="wpqorefunc_dash_pages" value="1" <?php echo get_option("wpqorefunc_dash_pages");?>> Pages

<input name="wpqorefunc_dash_serverinfo" type="checkbox" id="wpqorefunc_dash_serverinfo" value="1" <?php echo get_option("wpqorefunc_dash_serverinfo");?>> System

<input name="wpqorefunc_dash_about" type="checkbox" id="wpqorefunc_dash_about" value="1" <?php echo get_option("wpqorefunc_dash_about");?>> About
</div>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Custom dashboard', 'wp-qore' ); ?></label></th>
<td>
<div class="switch toggle3">
<input name="wpqorefunc_dashboard_switch" type="checkbox" id="wpqorefunc_dashboard_switch" value="1" <?php echo get_option("wpqorefunc_dashboard_switch");?>> 
<label><i></i></label>
</div>
<i><?php _e( "When 'On', this will override the WPQORE dashboard with the one you may create below.", 'wp-qore' ); ?></i>
<br /><br />
<a href="javascript://" id="wpqorefunc_cr_exc_url2"><?php _e( 'Customize dashboard', 'wp-qore' ); ?></a>
<br />
<div id="wpqorefunc_cr_exc_div2" style="display:none;margin-top:4px">
<?php

function WPQORE_TinyMCE($in){
    $in['theme_advanced_buttons1'] = 
    'formatselect,forecolor,|,bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull';
    return $in;
}
add_filter('tiny_mce_before_init', 'WPQORE_TinyMCE' );
$settings = array( 'media_buttons' => false, 'quicktags' => true );    
$content = get_option("wpqorefunc_custom_dashboard");                     
$editor_id = 'wpqorefunc_custom_dashboard';
wp_editor( $content, $editor_id, $settings );  

?>
</div>
<script>
jQuery("#wpqorefunc_cr_exc_url2").click(function () {

jQuery("#wpqorefunc_cr_exc_div2").toggle("slow");

});
</script>
</td>
</tr>
</tbody></table>
</div></div>

<p style="border-bottom: 1px dashed #CCCCCC;padding-bottom: 20px">
<input type="hidden" name="wpqorefunc_settings" value="1">
<input type="submit" class="button button-primary" value="Save changes">
</p>

<div id="poststuff">
<div class="postbox">
<table class="form-table">
<tbody>

<h3><?php _e( 'Automatic Updates', 'wp-qore' ); ?></h3>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Major Updates', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_allow_major_auto_core_updates">
<div class="switch toggle3">
<input name="wpqorefunc_allow_major_auto_core_updates" type="checkbox" id="wpqorefunc_allow_major_auto_core_updates" value="1" <?php echo get_option("wpqorefunc_allow_major_auto_core_updates");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable", 'wp-qore' ); ?></i><br><br>
<?php _e( "When 'On', this will enable automatic updates of all major WordPress core versions.", 'wp-qore' ); ?><br>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Minor Updates', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_allow_minor_auto_core_updates">
<div class="switch toggle3">
<input name="wpqorefunc_allow_minor_auto_core_updates" type="checkbox" id="wpqorefunc_allow_minor_auto_core_updates" value="1" <?php echo get_option("wpqorefunc_allow_minor_auto_core_updates");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable", 'wp-qore' ); ?></i><br><br>
<?php _e( "When 'On', this will enable automatic updates of all minor WordPress core versions.", 'wp-qore' ); ?><br>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Plugin Updates', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_auto_update_plugin">
<div class="switch toggle3">
<input name="wpqorefunc_auto_update_plugin" type="checkbox" id="wpqorefunc_auto_update_plugin" value="1" <?php echo get_option("wpqorefunc_auto_update_plugin");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable", 'wp-qore' ); ?></i><br><br>
<?php _e( "When 'On', this will enable automatic updates of all plugins.", 'wp-qore' ); ?><br>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Theme Updates', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_auto_update_theme">
<div class="switch toggle3">
<input name="wpqorefunc_auto_update_theme" type="checkbox" id="wpqorefunc_auto_update_theme" value="1" <?php echo get_option("wpqorefunc_auto_update_theme");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable", 'wp-qore' ); ?></i><br><br>
<?php _e( "When 'On', this will enable automatic updates of all themes.", 'wp-qore' ); ?><br>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Translation Updates', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_auto_update_translation">
<div class="switch toggle3">
<input name="wpqorefunc_auto_update_translation" type="checkbox" id="wpqorefunc_auto_update_translation" value="1" <?php echo get_option("wpqorefunc_auto_update_translation");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable", 'wp-qore' ); ?></i><br><br>
<?php _e( "When 'On', this will enable automatic updates of all translations.", 'wp-qore' ); ?><br>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Send Emails', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_auto_core_update_send_email">
<div class="switch toggle3">
<input name="wpqorefunc_auto_core_update_send_email" type="checkbox" id="wpqorefunc_auto_core_update_send_email" value="1" <?php echo get_option("wpqorefunc_auto_core_update_send_email");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable", 'wp-qore' ); ?></i><br><br>
<?php _e( "When 'On', this will send the admin an email every time automatic updates are performed.", 'wp-qore' ); ?><br>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Debug Emails', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_automatic_updates_send_debug_email">
<div class="switch toggle3">
<input name="wpqorefunc_automatic_updates_send_debug_email" type="checkbox" id="wpqorefunc_automatic_updates_send_debug_email" value="1" <?php echo get_option("wpqorefunc_automatic_updates_send_debug_email");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable", 'wp-qore' ); ?></i><br><br>
<?php _e( "When 'On', this will send the admin emails with debug information every time automatic updates are performed.", 'wp-qore' ); ?><br>
</td>
</tr>

</tbody></table>
</div></div>

<p style="border-bottom: 1px dashed #CCCCCC;padding-bottom: 20px">
<input type="hidden" name="wpqorefunc_settings" value="1">
<input type="submit" class="button button-primary" value="Save changes">
</p>

<div id="poststuff">
<div class="postbox">
<table class="form-table">
<tbody>

<h3><?php _e( 'General Settings', 'wp-qore' ); ?></h3>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Security advisor', 'wp-qore' ); ?></label> (<a target="_blank" href="<?php echo WP_PLUGIN_URL.'/'.basename( dirname( __FILE__ ) ).'/example/screenshot_06.jpg'; ?>" title="<?php _e( 'click to see example...', 'wp-qore' ); ?>"><b>?</b></a>)</th>
<td>
<label for="wpqorefunc_sec_advisor">
<div class="switch toggle3">
<input name="wpqorefunc_sec_advisor" type="checkbox" id="wpqorefunc_sec_advisor" value="1" <?php echo get_option("wpqorefunc_sec_advisor");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable (Recommended)", 'wp-qore' ); ?></i><br><br>
<?php _e( "When checked, this will turn on Security Advisor. Security Advisor offers you protection from security threats, such as: virus, malicious code, and security exploits. Once enabled, a submenu labeled 'Security Advisor' will appear within the wp-admin > WP Qore > submenu. If you don't see it, then go to the dashboard first and it will initialize.", 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Nag updates', 'wp-qore' ); ?></label> (<a target="_blank" href="<?php echo WP_PLUGIN_URL.'/'.basename( dirname( __FILE__ ) ).'/example/screenshot_05.png'; ?>" title="<?php _e( 'click to see example...', 'wp-qore' ); ?>"><b>?</b></a>)</th>
<td>
<label for="wpqorefunc_wphidenag">
<div class="switch toggle3">
<input name="wpqorefunc_wphidenag" type="checkbox" id="wpqorefunc_wphidenag" value="1" <?php echo get_option("wpqorefunc_wphidenag");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to disable", 'wp-qore' ); ?></i><br><br>
<?php _e( "This disables the annoying WordPress nag updates banner from appearing. This doesn't prevent updates, just conceals the update banner from the top of the wp-admin.", 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Core updates', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_coreupdate">
<div class="switch toggle3">
<input name="wpqorefunc_coreupdate" type="checkbox" id="wpqorefunc_coreupdate" value="1" <?php echo get_option("wpqorefunc_coreupdate");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to disable", 'wp-qore' ); ?></i><br><br>
<?php _e( 'This completely disables new WordPress version notifications and updates from being done.<br>
<b>Warning:</b> It is highly recommended that on your production environment you keep this option off. This option is provided for development environments only.', 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Post revisions', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_post_revisions">
<div class="switch toggle3">
<input name="wpqorefunc_post_revisions" type="checkbox" id="wpqorefunc_post_revisions" value="1" <?php echo get_option("wpqorefunc_post_revisions");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to disable", 'wp-qore' ); ?></i><br><br>
<?php _e( 'This disables saving multiple post revisions. Having multiple post revisions could cause bloat on your database. Disabling them will prevent such.', 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Admin bar removal', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_showadminbar">
<div class="switch toggle3">
<input name="wpqorefunc_showadminbar" type="checkbox" id="wpqorefunc_showadminbar" value="1" <?php echo get_option("wpqorefunc_showadminbar");?> >
<label><i></i></label>
</div>
<i><?php _e( 'Remove admin bar from the frontend', 'wp-qore' ); ?></i>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Admin bar opacity', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_wpadminbar_opacity">
<div class="switch toggle3">
<input name="wpqorefunc_wpadminbar_opacity" type="checkbox" id="wpqorefunc_wpadminbar_opacity" value="1" <?php echo get_option("wpqorefunc_wpadminbar_opacity");?> >
<label><i></i></label>
</div>
<i><?php _e( 'Adds opacity to admin bar on the frontend', 'wp-qore' ); ?></i>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Collapse admin menu', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_fold_menu">
<div class="switch toggle3">
<input name="wpqorefunc_fold_menu" type="checkbox" id="wpqorefunc_fold_menu" value="1" <?php echo get_option("wpqorefunc_fold_menu");?> >
<label><i></i></label>
</div>
<i><?php _e( 'This keeps the wp-admin menu on the left collapsed.', 'wp-qore' ); ?></i>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Change default jQuery', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_reregjquery">
<div class="switch toggle3">
<input name="wpqorefunc_reregjquery" type="checkbox" id="wpqorefunc_reregjquery" value="1" <?php echo get_option("wpqorefunc_reregjquery");?>>
<label><i></i></label>
</div>
<i><?php _e( "Use Google hosted jQuery instead of Wordpress core.</i><br><br>
<b>Attention:</b> If this doesn't play well with your theme, then simply turn it off.", 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Shortcode in widgets', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_shortcode">
<div class="switch toggle3">
<input name="wpqorefunc_shortcode" type="checkbox" id="wpqorefunc_shortcode" value="1" <?php echo get_option("wpqorefunc_shortcode");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable", 'wp-qore' ); ?></i><br><br>
<?php _e( 'This enables shortcode in widgets. In the instance you need to use shortcode within your widgets, then enabling this will help you.', 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Php in widgets', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_phpwidget">
<div class="switch toggle3">
<input name="wpqorefunc_phpwidget" type="checkbox" id="wpqorefunc_phpwidget" value="1" <?php echo get_option("wpqorefunc_phpwidget");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable", 'wp-qore' ); ?></i><br><br>
<?php _e( "This enables php code in widgets. In the instance you need to use php within your widgets, then enabling this will help you.<br><br>
<b>Warning!</b> Turning this on may be a security risk. It's not recommended you use this unless you are very careful and know what you are doing. Use at your own risk.", 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Import/export widgets', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_exportwidget">
<div class="switch toggle3">
<input name="wpqorefunc_exportwidget" type="checkbox" id="wpqorefunc_exportwidget" value="1" <?php echo get_option("wpqorefunc_exportwidget");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable", 'wp-qore' ); ?></i><br><br>
<?php _e( "This allows you to import and export your widget settings. Once enabled, a submenu labeled 'Export Widgets' and 'Import Widgets' will appear within the wp-admin > Tools menu.", 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Clean source code', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_removeversion">
<div class="switch toggle3">
<input name="wpqorefunc_removeversion" type="checkbox" id="wpqorefunc_removeversion" value="1" <?php echo get_option("wpqorefunc_removeversion");?> >
<label><i></i></label>
</div>
<i><?php _e( 'Remove WP version from source code (Recommended)', 'wp-qore' ); ?></i>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Dropbox Backups', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_dropbox_mod">
<div class="switch toggle3">
<input name="wpqorefunc_dropbox_mod" type="checkbox" id="wpqorefunc_dropbox_mod" value="1" <?php echo get_option("wpqorefunc_dropbox_mod");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable (Recommended)", 'wp-qore' ); ?></i><br><br>
<?php _e( "This will enable the Dropbox module. Once enabled, two sub-menus called 'Backup Settings' and 'Backup Monitor' will appear under the WP Qore menu. You still need to authorize your Dropbox account before backups can begin.", 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Minify your html', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_compresshtml">
<div class="switch toggle3">
<input name="wpqorefunc_compresshtml" type="checkbox" id="wpqorefunc_compresshtml" value="1" <?php echo get_option("wpqorefunc_compresshtml");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to enable", 'wp-qore' ); ?></i><br><br>
<?php _e( 'This will minify the HTML of your source code. This makes your website load much faster and makes it harder for people to view the source code of your website.', 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Remove wp meta tags', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_rmheader">
<div class="switch toggle3">
<input name="wpqorefunc_rmheader" type="checkbox" id="wpqorefunc_rmheader" value="1" <?php echo get_option("wpqorefunc_rmheader");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to remove from source code", 'wp-qore' ); ?></i><br><br>
<?php _e( 'Remove manifest, generator meta tags, adjacent posts rel links, parent post rel links, feed links, rsd link and index rel links from the source code.', 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="home"><?php _e( 'Plugin/theme editor', 'wp-qore' ); ?></label></th>
<td>
<label for="wpqorefunc_plug-edit">
<div class="switch toggle3">
<input name="wpqorefunc_plug-edit" type="checkbox" id="wpqorefunc_plug-edit" value="1" <?php echo get_option("wpqorefunc_plug-edit");?>>
<label><i></i></label>
</div>
<i><?php _e( "Turn 'On' to disable", 'wp-qore' ); ?></i><br><br>
<?php _e( 'This disables the theme and plugin editor from within WordPress. This is a good idea for production environments.', 'wp-qore' ); ?>
</label>
</td>
</tr>

<tr valign="top" style="background-color:#373737" class="wpqorefunc_dbopt">
<th scope="row"><label for="home"><?php _e( 'Database audit', 'wp-qore' ); ?></label></th>
<td>
<p>
<?php _e( 'This checks your Database and then gives you suggestions about security and making your website faster.', 'wp-qore' ); ?>
<br> 
<input style="cursor:pointer" class="wpqorefunc_dbreport" type="button" value="Begin Audit &#8594;" id="wpqorefunc_dbreport">
</p>
<div id="wpqorefunc_report_result"></div>
</td>
</tr>
</tbody></table>
</div></div>

<p style="">
<input type="hidden" name="wpqorefunc_settings" value="1">
<input type="submit" class="button button-primary" value="<?php _e( 'Save changes', 'wp-qore' ); ?>">
</p>

</form>
</div>

<style type="text/css">
th,td{border-left: 1px solid #e1e1e1;border-right: 1px solid #e1e1e1;border-top: 1px solid #e1e1e1;}
h3{color:#464646;}
.wpqorefunc_dbopt label,.wpqorefunc_dbopt p,.wpqorefunc_dbopt pre  {color:#FFFFFF;}
.wpqorefunc_gray{color:#00FF00;}
.wpqorefunc_red{color:#FF5050;}
.wpqorefunc_dbreport{float:right;margin-top: -20px;color: #FFFFFF;font-size: 12px;background-color: #FF0000;border-color: #FF0000;text-shadow: 0 -1px 0 rgba(0,0,0,.3);padding: 5px;}
.wpqorefunc_dbreport:hover{color: #FFCCCC;}
input{font-size: 14px}
.form-table{margin-top: 0px;}
</style>

<?php }

// your wp-admin copyright
include_once('functions/branding.php');

?>
