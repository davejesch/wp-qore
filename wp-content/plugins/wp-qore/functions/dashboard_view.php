<?php //wp-admin dashboard : begin 

?>

<div class="wrap about-wrap" id="pbody">
<div style="float:right;font-size:12px;font-weight:normal;color:#333">WP Qore: <b><?php wpqoreplugv(); ?></b></div>
 <h1><?php bloginfo('name'); ?></h1><br />
   <h2 class="nav-tab-wrapper">
      <a href="#" class="nav-tab nav-tab-active"><?php _e( 'General' ); ?></a>
      <a target="_blank" href="<?php echo bloginfo('wpurl'); ?>" title="Visit <?php bloginfo('name'); ?> Site" class="nav-tab"><?php _e( 'Visit Site' ); ?></a>
   </h2>

<div style="margin-top:0px">

<div class="dashboard_icons"><div  class="dash_text">All Posts</div><a href="edit.php" title="View All Posts"><img src="<?php echo plugins_url( '../images/dashboard/edit.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Add Posts</div><a href="post-new.php" title="Add New Post"><img src="<?php echo plugins_url( '../images/dashboard/add.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Categories</div><a href="edit-tags.php?taxonomy=category" title="Categories"><img src="<?php echo plugins_url( '../images/dashboard/category.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Tags</div><a href="edit-tags.php?taxonomy=post_tag" title="Tags"><img src="<?php echo plugins_url( '../images/dashboard/tags.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Media Library</div><a href="upload.php" title="Media Library"><img src="<?php echo plugins_url( '../images/dashboard/media.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Add Media</div><a href="media-new.php" title="Add New Media"><img src="<?php echo plugins_url( '../images/dashboard/media_add.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Comments</div><a href="edit-comments.php" title="Comments"><img src="<?php echo plugins_url( '../images/dashboard/comments.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Add Page</div><a href="edit.php?post_type=page" title="Add New Page"><img src="<?php echo plugins_url( '../images/dashboard/add_page.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Themes</div><a href="themes.php" title="Themes"><img src="<?php echo plugins_url( '../images/dashboard/themes.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Theme Editor</div><a href="theme-editor.php" title="Theme Editor"><img src="<?php echo plugins_url( '../images/dashboard/theme_editor.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Widgets</div><a href="widgets.php" title="Widgets"><img src="<?php echo plugins_url( '../images/dashboard/widgets.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Menus</div><a href="nav-menus.php" title="Menus"><img src="<?php echo plugins_url( '../images/dashboard/menus.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Plugins</div><a href="plugins.php" title="Plugins"><img src="<?php echo plugins_url( '../images/dashboard/plugins.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Add Plugin</div><a href="plugin-install.php" title="Add New Plugin"><img src="<?php echo plugins_url( '../images/dashboard/add-plugin.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Plugins Editor</div><a href="plugin-editor.php" title="Plugin Editor"><img src="<?php echo plugins_url( '../images/dashboard/edit-plugin.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Users</div><a href="users.php" title="Users"><img src="<?php echo plugins_url( '../images/dashboard/users.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Add User</div><a href="user-new.php" title="Add New User"><img src="<?php echo plugins_url( '../images/dashboard/add-user.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">My Profile</div><a href="profile.php" title="My Profile"><img src="<?php echo plugins_url( '../images/dashboard/profile.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Import</div><a href="import.php" title="Import Content"><img src="<?php echo plugins_url( '../images/dashboard/import.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Export</div><a href="export.php" title="Export Content"><img src="<?php echo plugins_url( '../images/dashboard/export.png' , __FILE__ ); ?>"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Settings</div><a href="options-general.php" title="General Settings"><img src="<?php echo plugins_url( '../images/dashboard/general.png' , __FILE__ ); ?>"/></a></div>

</div>
</div>

<?php //wp-admin dashboard : end 
 ?>
