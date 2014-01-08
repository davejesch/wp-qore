<?php //wp-admin dashboard : begin 

    if (get_option("wpqorefunc_dashboard_switch")=='checked') { 
        echo get_option("wpqorefunc_custom_dashboard");
    } else {

//convert memory usage
function convert($size){
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2);
}

// number of posts
add_shortcode('wpqore_posts','wpqore_posts');
function wpqore_posts() {
	global $wpqore_count_options;
	$count_posts = wp_count_posts();
	return $wpqore_count_options['count_posts_before'] . $count_posts->publish . $wpqore_count_options['count_posts_after'];
}

// number of pages
function wpqore_pages() {
	global $wpqore_count_options;
	$count_pages = wp_count_posts('page');
	return $wpqore_count_options['count_pages_before'] . $count_pages->publish . $wpqore_count_options['count_pages_after'];
}

// number of drafts
function wpqore_drafts() {
	global $wpqore_count_options;
	$count_drafts = wp_count_posts();
	return $wpqore_count_options['count_drafts_before'] . $count_drafts->draft . $wpqore_count_options['count_drafts_after'];
}

// number of comments (total)
function wpqore_comments() {
	global $wpqore_count_options;
	$count_comments = wp_count_comments();
	return $wpqore_count_options['count_comments_before'] . $count_comments->total_comments . $wpqore_count_options['count_comments_after'];
}

// number of comments (moderated)
function wpqore_moderated() {
	global $wpqore_count_options;
	$count_moderated = wp_count_comments();
	return $wpqore_count_options['count_moderated_before'] . $count_moderated->moderated . $wpqore_count_options['count_moderated_after'];
}

// number of comments (approved)
function wpqore_approved() {
	global $wpqore_count_options;
	$count_approved = wp_count_comments();
	return $wpqore_count_options['count_approved_before'] . $count_approved->approved . $wpqore_count_options['count_approved_after'];
}

// number of users
function wpqore_users() {
	global $wpqore_count_options;
	$count_users = count_users();
	return $wpqore_count_options['count_users_before'] . $count_users['total_users'] . $wpqore_count_options['count_users_after'];
}

// number of categories
function wpqore_cats() {
	global $wpqore_count_options;
	$cats = wp_list_categories('title_li=&style=none&echo=0');
	$cats_parts = explode('<br />', $cats);
	$cats_count = count($cats_parts) - 1;
	return $wpqore_count_options['count_cats_before'] . $cats_count . $wpqore_count_options['count_cats_after'];
}

// number of tags
function wpqore_tags() {
	global $wpqore_count_options;
	return $wpqore_count_options['count_tags_before'] . wp_count_terms('post_tag') . $wpqore_count_options['count_tags_after'];
}

// last updated posts
function wpqore_updated($d = '') {
	global $wpqore_count_options;
	$count_posts = wp_count_posts();
	$published_posts = $count_posts->publish; 
	$recent = new WP_Query("showposts=1&orderby=date&post_status=publish");
	if ($recent->have_posts()) {
		while ($recent->have_posts()) {
			$recent->the_post();
			$last_update = get_the_modified_date($d);
		}
		return $wpqore_count_options['site_updated_before'] . $last_update . $wpqore_count_options['site_updated_after'];
	} else {
		return $wpqore_count_options['site_updated_before'] . 'awhile ago' . $wpqore_count_options['site_updated_after'];
	}
}

// uploads space
function wp_upload_space() {
    $upload_dir     = wp_upload_dir(); 
    $upload_space   = wpqore_foldersize( $upload_dir['basedir'] );
    $content_space  = wpqore_foldersize( WP_CONTENT_DIR );
    $wp_space       = wpqore_foldersize( ABSPATH );

    echo wpqore_format_size( $upload_space ); 
}

// wp-content space
function wp_content_space() {
    $upload_dir     = wp_upload_dir(); 
    $upload_space   = wpqore_foldersize( $upload_dir['basedir'] );
    $content_space  = wpqore_foldersize( WP_CONTENT_DIR );
    $wp_space       = wpqore_foldersize( ABSPATH );

    echo wpqore_format_size( $content_space );  
}

// wp total space
function wp_wp_space() {
    $upload_dir     = wp_upload_dir(); 
    $upload_space   = wpqore_foldersize( $upload_dir['basedir'] );
    $content_space  = wpqore_foldersize( WP_CONTENT_DIR );
    $wp_space       = wpqore_foldersize( ABSPATH ); 

    echo wpqore_format_size( $wp_space );   
}

function wpqore_foldersize( $path ) {
    $total_size = 0;
    $files = scandir( $path );
    $cleanPath = rtrim( $path, '/' ) . '/';

    foreach( $files as $t ) {
        if ( '.' != $t && '..' != $t ) {
            $currentFile = $cleanPath . $t;
            if ( is_dir( $currentFile ) ) {
                $size = wpqore_foldersize( $currentFile );
                $total_size += $size;
            } else {
                $size = filesize( $currentFile );
                $total_size += $size;
            }
        }   
    }

    return $total_size;
}

function wpqore_format_size($size) {
    $units = explode( ' ', 'B KB MB GB TB PB' );
    $mod = 1024;

    for ( $i = 0; $size > $mod; $i++ )
        $size /= $mod;

    $endIndex = strpos( $size, "." ) + 3;
    return substr( $size, 0, $endIndex ) . ' ' . $units[$i];
}

//HDD Free Space Function
function get_hdd() {
    exec("df -h",$a);
        if ($start=strpos($a[1], '%')) {
        $b = substr($a[1], $start-2, 3);
        echo $b;
        unset ($a, $b);
        } else
echo '0';
}

//Speedtest Function
function get_speedtest() {
    exec("/usr/bin/wget -O /dev/null http://cachefly.cachefly.net/1mb.test 2>&1",$output);
    if(preg_match('/\(([0-9.]+) (..)\/s\)/', $output[count($output) - 2], $m)){
        return $m[1];
    }
    return array();
}

?>

<link rel="stylesheet" href="<?php echo plugins_url( '../css/layout.css' , __FILE__ ); ?>" type="text/css" media="screen" />
<!--[if lt IE 9]>
<link rel="stylesheet" href="<?php echo plugins_url( '../css/ie.css' , __FILE__ ); ?>" type="text/css" media="screen" />
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
	
<script type="text/javascript">
$(document).ready(function() { 
    $(".tablesorter").tablesorter(); 
});
$(document).ready(function() {

//When page loads...
$(".tab_content").hide(); //Hide all content
$("ul.tabs li:first").addClass("active").show(); //Activate first tab
$(".tab_content:first").show(); //Show first tab content

//On Click Event
$("ul.tabs li").click(function() {
    $("ul.tabs li").removeClass("active"); //Remove any "active" class
    $(this).addClass("active"); //Add "active" class to selected tab
    $(".tab_content").hide(); //Hide all tab content
    var activeTab = $(this).find("a").attr("href"); //Find the href attribute value to identify the active tab + content
    $(activeTab).fadeIn(); //Fade in the active ID content
    return false;
    });
});

$(function(){
    $('.column').equalHeight();
});
</script>

<script type="text/javascript">
//Memory Chart
google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawChart);

function drawChart() {
    var data = google.visualization.arrayToDataTable([
    ['<?php _e( 'Task', 'wp-qore' ); ?>', '<?php _e( 'PHP Memory', 'wp-qore' ); ?>'],
    ['<?php _e( 'Limit', 'wp-qore' ); ?>', <?php echo convert(ini_get('memory_limit')); ?>],
    ['<?php _e( 'Usage', 'wp-qore' ); ?>', <?php echo convert(memory_get_usage(true)); ?>],
    ]);

    var options = {
    title: '<?php _e( 'PHP Memory', 'wp-qore' ); ?>',
    pieHole: 0.3,
    };

    var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
    chart.draw(data, options);
    }
</script>

<script type="text/javascript">
    google.load('visualization', '1', {packages: ['gauge']});

      function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['MB/s', <?php print_r(get_speedtest());echo "\n"; ?>]
        ]);
      
        // Create and draw the visualization.
        new google.visualization.Gauge(document.getElementById('visualization')).
            draw(data);
      }
      
    google.setOnLoadCallback(drawVisualization);
</script>

<section id="secondary_bar">
<div class="breadcrumbs_container">	

<article class="breadcrumbs">
<a href="#"><?php _e( 'Dashboard', 'wp-qore' ); ?></a> 
<div class="breadcrumb_divider"></div> 
<a class="current"><?php _e( 'General', 'wp-qore' ); ?></a>
</article>

</div>
</section>
	
<section id="main" class="column">
		
<h4 class="alert_info"><?php _e( 'Welcome to the admin backend', 'wp-qore' ); ?>.</h4>
<!--
<h4 class="alert_warning"><?php _e( 'A Warning Alert', 'wp-qore' ); ?></h4>
<h4 class="alert_error"><?php _e( 'An Error Message', 'wp-qore' ); ?></h4>
<h4 class="alert_success"><?php _e( 'A Success Message', 'wp-qore' ); ?></h4>
-->		

<article class="module width_full" id="module_width_full">
<header><h3><?php _e( 'Stats', 'wp-qore' ); ?></h3></header>
<div class="module_content">
<div class="module_content_stats">

<article class="stats_graph" style="width:450px;">
<div style="float:left;font-size:10px;color:#000000;font-weight:bold"><?php _e( 'Speed Test', 'wp-qore' ); ?></div>
<div id="visualization" style="float:left;width:200px;height:120px;margin-top:20px;margin-left:-50px;"></div>
<div id="donutchart" style="float:left;width:280px;height:170px;margin-left:-40px;margin-top:-10px;margin-bottom:-20px;"></div>
</article>

<article class="stats_overview" id="stats_overview" style="margin-top:10px;margin-bottom:10px;padding-top:25px;padding-bottom:20px;">
<div class="overview_today">
    <p class="overview_count"><?php echo wpqore_posts(); ?></p>
    <p class="overview_type"><?php _e( 'posts', 'wp-qore' ); ?></p>
    <p class="overview_count"><?php echo wpqore_comments(); ?></p>
    <p class="overview_type"><?php _e( 'comments', 'wp-qore' ); ?></p>
</div>
<div class="overview_previous">
    <p class="overview_count"><?php echo wpqore_pages(); ?></p>
    <p class="overview_type"><?php _e( 'pages', 'wp-qore' ); ?></p>
    <p class="overview_count"><?php $result = count_users(); echo $result['total_users']; ?></p>
    <p class="overview_type"><?php _e( 'users', 'wp-qore' ); ?></p>
</div>
</article>
			
<article class="stats_overview" id="stats_overview_2" style="margin-top:10px;margin-bottom:10px;margin-right:20px;padding-top:25px;padding-bottom:20px;">
<div class="overview_today">
    <p class="overview_count"><?php echo wp_upload_space(); ?></p>
    <p class="overview_type"><?php _e( 'uploads', 'wp-qore' ); ?></p>
    <p class="overview_count"><?php echo wp_content_space(); ?></p>
    <p class="overview_type"><?php _e( 'wp-content', 'wp-qore' ); ?></p>
</div>
<div class="overview_previous">
    <p class="overview_count"><?php echo wp_wp_space(); ?></p>
    <p class="overview_type"><?php _e( 'wp total', 'wp-qore' ); ?></p>
    <p class="overview_count"><?php echo get_hdd(); ?></p>
    <p class="overview_type"><?php _e( 'disk usage', 'wp-qore' ); ?></p>
</div>
</article>

<div class="clear"></div>
</div>
</div>
</article>

<article class="module width_full">
<header><h3><?php _e( 'Control', 'wp-qore' ); ?></h3></header>
<div class="module_content">
<div style="display:table;height:100%;padding-left:40px;padding-bottom:30px;">
<?php if (get_option("wpqorefunc_plug-edit")=='checked') { ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'All Posts', 'wp-qore' ); ?></div><a href="edit.php" title="View All Posts"><img src="<?php echo plugins_url( '../images/dashboard/edit.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Posts', 'wp-qore' ); ?></div><a href="post-new.php" title="Add New Post"><img src="<?php echo plugins_url( '../images/dashboard/add.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Categories', 'wp-qore' ); ?></div><a href="edit-tags.php?taxonomy=category" title="Categories"><img src="<?php echo plugins_url( '../images/dashboard/category.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Tags', 'wp-qore' ); ?></div><a href="edit-tags.php?taxonomy=post_tag" title="Tags"><img src="<?php echo plugins_url( '../images/dashboard/tags.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Media Library', 'wp-qore' ); ?></div><a href="upload.php" title="Media Library"><img src="<?php echo plugins_url( '../images/dashboard/media.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Media', 'wp-qore' ); ?></div><a href="media-new.php" title="Add New Media"><img src="<?php echo plugins_url( '../images/dashboard/media_add.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Comments', 'wp-qore' ); ?></div><a href="edit-comments.php" title="Comments"><img src="<?php echo plugins_url( '../images/dashboard/comments.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Page', 'wp-qore' ); ?></div><a href="edit.php?post_type=page" title="Add New Page"><img src="<?php echo plugins_url( '../images/dashboard/add_page.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Themes', 'wp-qore' ); ?></div><a href="themes.php" title="Themes"><img src="<?php echo plugins_url( '../images/dashboard/themes.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Widgets', 'wp-qore' ); ?></div><a href="widgets.php" title="Widgets"><img src="<?php echo plugins_url( '../images/dashboard/widgets.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Menus', 'wp-qore' ); ?></div><a href="nav-menus.php" title="Menus"><img src="<?php echo plugins_url( '../images/dashboard/menus.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Plugins', 'wp-qore' ); ?></div><a href="plugins.php" title="Plugins"><img src="<?php echo plugins_url( '../images/dashboard/plugins.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Plugin', 'wp-qore' ); ?></div><a href="plugin-install.php" title="Add New Plugin"><img src="<?php echo plugins_url( '../images/dashboard/add-plugin.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Users', 'wp-qore' ); ?></div><a href="users.php" title="Users"><img src="<?php echo plugins_url( '../images/dashboard/users.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add User', 'wp-qore' ); ?></div><a href="user-new.php" title="Add New User"><img src="<?php echo plugins_url( '../images/dashboard/add-user.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'My Profile', 'wp-qore' ); ?></div><a href="profile.php" title="My Profile"><img src="<?php echo plugins_url( '../images/dashboard/profile.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Import', 'wp-qore' ); ?></div><a href="import.php" title="Import Content"><img src="<?php echo plugins_url( '../images/dashboard/import.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Export', 'wp-qore' ); ?></div><a href="export.php" title="Export Content"><img src="<?php echo plugins_url( '../images/dashboard/export.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Settings', 'wp-qore' ); ?></div><a href="options-general.php" title="General Settings"><img src="<?php echo plugins_url( '../images/dashboard/general.png' , __FILE__ ); ?>"/></a></div>
<?php }else{ ?>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'All Posts', 'wp-qore' ); ?></div><a href="edit.php" title="View All Posts"><img src="<?php echo plugins_url( '../images/dashboard/edit.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Posts', 'wp-qore' ); ?></div><a href="post-new.php" title="Add New Post"><img src="<?php echo plugins_url( '../images/dashboard/add.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Categories', 'wp-qore' ); ?></div><a href="edit-tags.php?taxonomy=category" title="Categories"><img src="<?php echo plugins_url( '../images/dashboard/category.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Tags', 'wp-qore' ); ?></div><a href="edit-tags.php?taxonomy=post_tag" title="Tags"><img src="<?php echo plugins_url( '../images/dashboard/tags.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Media Library', 'wp-qore' ); ?></div><a href="upload.php" title="Media Library"><img src="<?php echo plugins_url( '../images/dashboard/media.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Media', 'wp-qore' ); ?></div><a href="media-new.php" title="Add New Media"><img src="<?php echo plugins_url( '../images/dashboard/media_add.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Comments', 'wp-qore' ); ?></div><a href="edit-comments.php" title="Comments"><img src="<?php echo plugins_url( '../images/dashboard/comments.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Page', 'wp-qore' ); ?></div><a href="edit.php?post_type=page" title="Add New Page"><img src="<?php echo plugins_url( '../images/dashboard/add_page.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Themes', 'wp-qore' ); ?></div><a href="themes.php" title="Themes"><img src="<?php echo plugins_url( '../images/dashboard/themes.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Theme Editor', 'wp-qore' ); ?></div><a href="theme-editor.php" title="Theme Editor"><img src="<?php echo plugins_url( '../images/dashboard/theme_editor.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Widgets', 'wp-qore' ); ?></div><a href="widgets.php" title="Widgets"><img src="<?php echo plugins_url( '../images/dashboard/widgets.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Menus', 'wp-qore' ); ?></div><a href="nav-menus.php" title="Menus"><img src="<?php echo plugins_url( '../images/dashboard/menus.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Plugins', 'wp-qore' ); ?></div><a href="plugins.php" title="Plugins"><img src="<?php echo plugins_url( '../images/dashboard/plugins.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add Plugin', 'wp-qore' ); ?></div><a href="plugin-install.php" title="Add New Plugin"><img src="<?php echo plugins_url( '../images/dashboard/add-plugin.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Plugins Editor', 'wp-qore' ); ?></div><a href="plugin-editor.php" title="Plugin Editor"><img src="<?php echo plugins_url( '../images/dashboard/edit-plugin.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Users', 'wp-qore' ); ?></div><a href="users.php" title="Users"><img src="<?php echo plugins_url( '../images/dashboard/users.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Add User', 'wp-qore' ); ?></div><a href="user-new.php" title="Add New User"><img src="<?php echo plugins_url( '../images/dashboard/add-user.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'My Profile', 'wp-qore' ); ?></div><a href="profile.php" title="My Profile"><img src="<?php echo plugins_url( '../images/dashboard/profile.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Import', 'wp-qore' ); ?></div><a href="import.php" title="Import Content"><img src="<?php echo plugins_url( '../images/dashboard/import.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Export', 'wp-qore' ); ?></div><a href="export.php" title="Export Content"><img src="<?php echo plugins_url( '../images/dashboard/export.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text"><?php _e( 'Settings', 'wp-qore' ); ?></div><a href="options-general.php" title="General Settings"><img src="<?php echo plugins_url( '../images/dashboard/general.png' , __FILE__ ); ?>"/></a></div>
<?php } ?>
</div>
</div>
</article>

<!--
<article class="module width_full">
<header><h3><?php _e( 'Box One', 'wp-qore' ); ?></h3></header>
<div class="module_content">
123
</div>
</article>

<article class="module width_full">
<header><h3><?php _e( 'Box Two', 'wp-qore' ); ?></h3></header>
<div class="module_content">
123
</div>
</article>
-->
		
<div class="clear"></div>		
<div class="spacer"></div>
</section>

<?php } //wp-admin dashboard : end 
 ?>
