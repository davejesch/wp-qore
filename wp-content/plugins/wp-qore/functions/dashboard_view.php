<?php //wp-admin dashboard : begin 

    if (get_option("wpqorefunc_dashboard_switch")=='checked') { 
        echo get_option("wpqorefunc_custom_dashboard");
    } else {

?>

<div class="wrap about-wrap" id="pbody">
 <h1><?php bloginfo('name'); ?></h1><br />
   <h2 class="nav-tab-wrapper">
      <a href="#" class="nav-tab nav-tab-active"><?php _e( 'General', 'wp-qore' ); ?></a>
<?php if (get_option("wpqorefunc_dash_tabs")=='checked') { ?>
      <a href="admin.php?page=wp-qore/functions.php" title="WP Qore Settings" class="nav-tab"><?php _e( 'WP Qore Settings', 'wp-qore' ); ?></a>
<?php if (get_option("wpqorefunc_cache_assistance")=='checked') { ?>
      <a href="admin.php?page=Cache_AssistanceOptions" title="Cache Assistance" class="nav-tab"><?php _e( 'Cache Assistance', 'wp-qore' ); ?></a>
<?php }        
      
      if (get_option("wpqorefunc_sec_advisor")=='checked') { ?>
      <a href="admin.php?page=sec-advisor" title="Security Advisor" class="nav-tab"><?php _e( 'Security Advisor', 'wp-qore' ); ?></a>
<?php } } ?>
      <a target="_blank" href="<?php echo bloginfo('wpurl'); ?>" title="Visit <?php bloginfo('name'); ?> Site" class="nav-tab"><?php _e( 'Visit Site', 'wp-qore' ); ?></a>
   </h2>

<div style="margin-top:0px">

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

<?php } //wp-admin dashboard : end 
 ?>
