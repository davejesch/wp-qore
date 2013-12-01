<?php

// load defaults
function wpqorefunc_load_defaults  (){

    if (get_option("wpqorefunc_secret_arg")===false) update_option("wpqorefunc_secret_arg","secretkey1");
    if (get_option("wpqorefunc_showadminbar")===false) update_option("wpqorefunc_showadminbar","0");
    if (get_option("wpqorefunc_reregjquery")===false) update_option("wpqorefunc_reregjquery","0");
    if (get_option("wpqorefunc_removeversion")===false) update_option("wpqorefunc_removeversion","checked");
    if (get_option("wpqorefunc_rmheader")===false) update_option("wpqorefunc_rmheader","0");
    if (get_option("wpqorefunc_2steplogin")===false) update_option("wpqorefunc_2steplogin","0");
    if (get_option("wpqorefunc_compresshtml")===false) update_option("wpqorefunc_compresshtml","0");
    if (get_option("wpqorefunc_dashboard")===false) update_option("wpqorefunc_dashboard","0");
    if (get_option("wpqorefunc_gzip")===false) update_option("wpqorefunc_gzip","0");
    if (get_option("wpqorefunc_wphidenag")===false) update_option("wpqorefunc_wphidenag","0");
    if (get_option("wpqorefunc_plug-edit")===false) update_option("wpqorefunc_plug-edit","0");
    if (get_option("wpqorefunc_shortcode")===false) update_option("wpqorefunc_shortcode","0");
    if (get_option("wpqorefunc_phpwidget")===false) update_option("wpqorefunc_phpwidget","0");
    if (get_option("wpqorefunc_coreupdate")===false) update_option("wpqorefunc_coreupdate","0");
    if (get_option("wpqorefunc_exportwidget")===false) update_option("wpqorefunc_exportwidget","0");
    if (get_option("wpqorefunc_login_logo")===false) update_option("wpqorefunc_login_logo","0");
    if (get_option("wpqorefunc_theme_directory")===false) update_option("wpqorefunc_theme_directory","templates");
    if (get_option("wpqorefunc_login_logo_url")===false) update_option("wpqorefunc_login_logo_url",home_url()."/wp-admin/images/wordpress-logo.png");

}

// login logo
function wpqorefunc_my_login_logo() { ?><style type="text/css">body.login div#login h1 a {background-image: url(<?php echo get_option('wpqorefunc_login_logo_url'); ?>);}</style>

<?php }

function wpqorefunc_my_login_logo_url() {
    return get_bloginfo( 'url' );
}

function wpqorefunc_my_login_logo_url_title() {
    return get_bloginfo( 'name' );
}

// remove version strings from css & js
function wpqorefunc_remove_cssjs_ver( $src ) {
    if( strpos( $src, '?ver=' ) )

    $src = remove_query_arg( 'ver', $src );
    return $src;
}

// enable jquery cdn
function wpqorefunc_my_scripts_method() {
    wp_deregister_script('jquery'); 
    wp_enqueue_script(
    'jquery',
    'http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js'
    );
}

// enable wp-admin menus
function wpqorefunc_fp_admin() {
    if(current_user_can('manage_options'))  
    add_menu_page('WP Qore Settings', 'WP Qore', 'manage_options', __FILE__, 'wpqorefunc_fp_settings');
    add_submenu_page( 'wp-qore/functions.php', 'WP Qore Settings', 'WP Qore Settings', 'manage_options', __FILE__, 'wpqorefunc_fp_settings');
}

function wpqorefunc_theme_directory_fun (){
    register_theme_directory( WP_CONTENT_DIR.'/'.get_option("wpqorefunc_theme_directory") );
}

// hides admin bar from frontend
function wpqorefunc_sabf () {
    //echo  WP_CONTENT_DIR.'/templates';
    if (!is_admin() or !is_user_logged_in() ){ 
    wp_deregister_script('admin-bar');
    wp_deregister_style('admin-bar');
    remove_action('wp_footer','wp_admin_bar_render',1000);
    show_admin_bar( false );
  }
}

// prevents access to wp-admin
function wpqorefunc_redirect_admin() {

    //if (is_admin()) 
    $cururl= $_SERVER["REQUEST_URI"];
    $refurl=$_SERVER["HTTP_REFERER"];	

    //update_option("wpqorefunc_secret_arg","aaa");//temporary
    if ( (strpos($cururl,'wp-admin')!==false or strpos($cururl,'wp-login')!==false) and !is_user_logged_in() ) 

    $wpqorefuncredirect=1;else $wpqorefuncredirect=0;

    if ($wpqorefuncredirect==1) {
    if (isset($_GET[get_option("wpqorefunc_secret_arg")])) {

/*
    // first step pass
    if (isset($_POST["firststeppass"]) and $_POST["firststeppass"]==get_option("wpqorefunc_1st_pass")) {

    $wpqorefuncredirect=0;

    } else {

    echo '<style>.centered{left: 50%;margin-top:-50px;margin-left: -250px;position: fixed;top: 50%;width: 500px}</style><form action="" class="centered" method="post"><input type="text" name="firststeppass" value="" placeholder="1st step pass"><input type="submit" value="Continue"></form>';

    die();

    }
*/

    }
}

$current_url_no_par = explode("?", $_SERVER['REQUEST_URI']);

// secret argument
if ($wpqorefuncredirect==1 and strpos($cururl,get_option("wpqorefunc_secret_arg"))===false 

		and strpos($refurl,get_option("wpqorefunc_secret_arg"))===false  and strpos($current_url_no_par[0],'admin-ajax.php')===false  and  

		($refurl!=get_option("wpqorefunc_exceptional_url") or get_option("wpqorefunc_exceptional_url")=='') ) {

			global $wp_query;
			remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 ); 
 			$wp_query->set_404();
  			status_header( 404 );
  			get_template_part( 404 ); 
  			exit();

  		}	
}

function wpqorefunc_is_checked($par){
    if (isset($_POST["wpqorefunc_settings"])) { if(isset($_POST[$par])) $k='checked';else $k=''; update_option($par,$k);} 
}

function wpqorefunc_string_setting($par,$def){
    if (isset($_POST[$par])) { if(isset($_POST[$par]) and $_POST[$par]!='' ) $k=$_POST[$par];else $k=$def; update_option($par,$k);} 
}

add_action( 'admin_footer', 'wpqorefunc_dbguide_ajax' );

function wpqorefunc_dbguide_ajax() {

?>
<script type="text/javascript" >
jQuery("#wpqorefunc_dbreport").click(function() {
    jQuery("#wpqorefunc_report_result").html("Generating the report, please wait...");
	var data = {
		action: 'my_action',
		whatever: 1234
	};
    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(response) {
    //alert('Got this from the server: ' + response);
    jQuery("#wpqorefunc_report_result").html(response);
	});
});</script>
<?php }

    add_action('wp_ajax_my_action', 'wpqorefunc_ajax_db_callback');

function wpqorefunc_ajax_db_callback() {

    global $wpdb; // this is how you get access to the database
    global $table_prefix;
    echo '<p><b>1. Database prefix</b><br>';

    if ($table_prefix!='wp_') echo '<span class="wpqorefunc_gray">Database table prefix is '.$table_prefix.' . It is ok.</span>'; 

    else echo '<span class="wpqorefunc_red">Your current WP database table prefix is '.$table_prefix.' . It is not secure. We advice you to change it.<br> It is very easy before install, but as you have already installed website, please do the following for fixing it: <br> </span>Solution: This <a target="_blank" href="http://www.wpbeginner.com/wp-tutorials/how-to-change-the-wordpress-database-prefix-to-improve-security/">link</a> will explain to you what you can do for changing wp_ prefix. Alternatively, you may consider trying <a target="" href="http://wordpress.org/plugins/db-prefix-change/">Change DB Prefix</a> from the WordPress.org plugins directory. Its super easy!';

    echo '</p><p><b>2. WP admin username</b><br>';

    $user = get_userdatabylogin('admin');

    if ($user->ID=='') echo '<span class="wpqorefunc_gray">It is OK. You don\'t use admin as administrator username. </span>';

    else { echo '<span class="wpqorefunc_red">Warning: You have username called admin. It is too bad for security. You should not use admin username for administrator user. </span><br>Solution: Run the following sql in phpmyadmin, then refresh your wp-admin and re-login.<i> <pre>UPDATE '.$wpdb->users.' set user_login=\'Desired username here\' where user_login=\'admin\' </pre></i>';} // prints the id of the user

    //echo $table_prefix;
        echo '</p><p><b>3. Post revisions</b><br>';

        $rev_count = $wpdb->get_row( $wpdb->prepare( "SELECT count(*) as cnt FROM $wpdb->posts WHERE post_status = 'revision'" ) );

    if ($rev_count->cnt==0) echo '<span class="wpqorefunc_gray">You have no revision posts in your sql tables. It is good for performance. But remember that revisions are for keeping old versions of posts</span>'; 

    else echo '<span class="wpqorefunc_red">You have '.$rev_count->cnt.' revision posts in your sql tables. It is not good for performance. But remember that revisions are for keeping old versions of posts If you don\'t need them and want to make your database work faster, then just run this query in your phpmyadmin: <i><pre>DELETE a,b,c FROM '.$wpdb->posts.' a WHERE a.post_type = \'revision\' LEFT JOIN '.$wpdb->term_relationships.' b ON (a.ID = b.object_id) LEFT JOIN '.$wpdb->postmeta.' c ON (a.ID = c.post_id); </pre></i></span>';

    echo '</p><p><b>4. Unused tags</b><br>';

    $untag_count = $wpdb->get_row( $wpdb->prepare( "SELECT count(*) as cnt FROM $wpdb->terms WHERE term_id IN (SELECT term_id FROM wp_term_taxonomy WHERE count = 0) " ) );

    if ($untag_count->cnt==0) echo '<span class="wpqorefunc_gray">You have no unused tags. It is good result. </span>'; 

    else echo '<span class="wpqorefunc_red">You have '.$untag_count->cnt.' unused tags. We advise to remove all of them with following sql query. <i><pre>DELETE FROM '.$wpdb->terms.' WHERE term_id IN (SELECT term_id FROM '.$wpdb->term_taxonomy.' WHERE count = 0 ); <br />DELETE FROM '.$wpdb->term_taxonomy.' WHERE term_id not IN (SELECT term_id FROM '.$wpdb->terms.'); <br />DELETE FROM '.$wpdb->term_relationships.' WHERE term_taxonomy_id not IN (SELECT term_taxonomy_id FROM '.$wpdb->term_taxonomy.');</pre> </i> </span>';

echo '</p>';

    die(); // this is required to return a proper result
}

function wpqorefunc_redirect_to_front() {

    if(is_user_logged_in() and is_admin() and current_user_can('subscriber') ){

    wp_redirect( home_url() ); exit; 
        } 
    }

?>
