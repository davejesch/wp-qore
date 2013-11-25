<?php

// WordPress Administration Bootstrap
require_once( ABSPATH . 'wp-load.php' );
require_once( ABSPATH . 'wp-admin/admin.php' );
require_once( ABSPATH . 'wp-admin/admin-header.php' );

?>

<div class="wrap about-wrap" id="pbody">
<div style="float:right;font-size:12px;font-weight:normal;color:#333">WP Qore: <b><?php wpqoreplugv(); ?></b></div>
 <h1><?php bloginfo('name'); ?></h1><br />
   <h2 class="nav-tab-wrapper">
      <a href="#" class="nav-tab nav-tab-active"><?php _e( 'General' ); ?></a>
      <a target="_blank" href="<?php echo bloginfo('wpurl'); ?>" title="Visit <?php bloginfo('name'); ?> Site" class="nav-tab"><?php _e( 'Visit Site' ); ?></a>
   </h2>

<div style="margin-top:0px">

<div class="dashboard_icons"><div  class="dash_text">Posts</div><a href="edit.php" title="Posts"><img src="../wp-content/plugins/wp-qore/images/dashboard/edit.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Add Posts</div><a href="post-new.php" title="Add New Post"><img src="../wp-content/plugins/wp-qore/images/dashboard/add.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Categories</div><a href="edit-tags.php?taxonomy=category" title="Categories"><img src="../wp-content/plugins/wp-qore/images/dashboard/category.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Tags</div><a href="edit-tags.php?taxonomy=post_tag" title="Tags"><img src="../wp-content/plugins/wp-qore/images/dashboard/tags.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Media Library</div><a href="upload.php" title="Media Library"><img src="../wp-content/plugins/wp-qore/images/dashboard/media.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Add Media</div><a href="media-new.php" title="Add New Media"><img src="../wp-content/plugins/wp-qore/images/dashboard/media_add.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Comments</div><a href="edit-comments.php" title="Comments"><img src="../wp-content/plugins/wp-qore/images/dashboard/comments.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Add Page</div><a href="edit.php?post_type=page" title="Add New Page"><img src="../wp-content/plugins/wp-qore/images/dashboard/add_page.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Themes</div><a href="themes.php" title="Themes"><img src="../wp-content/plugins/wp-qore/images/dashboard/themes.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Widgets</div><a href="widgets.php" title="Widgets"><img src="../wp-content/plugins/wp-qore/images/dashboard/widgets.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Menus</div><a href="nav-menus.php" title="Menus"><img src="../wp-content/plugins/wp-qore/images/dashboard/menus.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Theme Editor</div><a href="theme-editor.php" title="Theme Editor"><img src="../wp-content/plugins/wp-qore/images/dashboard/theme_editor.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Plugins</div><a href="plugins.php" title="Plugins"><img src="../wp-content/plugins/wp-qore/images/dashboard/plugins.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Add Plugin</div><a href="plugin-install.php" title="Add New Plugin"><img src="../wp-content/plugins/wp-qore/images/dashboard/add-plugin.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Plugins Editor</div><a href="plugin-editor.php" title="Plugin Editor"><img src="../wp-content/plugins/wp-qore/images/dashboard/edit-plugin.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Users</div><a href="users.php" title="Users"><img src="../wp-content/plugins/wp-qore/images/dashboard/users.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Add User</div><a href="user-new.php" title="Add New User"><img src="../wp-content/plugins/wp-qore/images/dashboard/add-user.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">My Profile</div><a href="profile.php" title="My Profile"><img src="../wp-content/plugins/wp-qore/images/dashboard/profile.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Import</div><a href="import.php" title="Import Content"><img src="../wp-content/plugins/wp-qore/images/dashboard/import.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Export</div><a href="export.php" title="Export Content"><img src="../wp-content/plugins/wp-qore/images/dashboard/export.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">General</div><a href="options-general.php" title="General Settings"><img src="../wp-content/plugins/wp-qore/images/dashboard/general.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Writing</div><a href="options-writing.php" title="Writing Settings"><img src="../wp-content/plugins/wp-qore/images/dashboard/writing-settings.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Reading</div><a href="options-reading.php" title="Reading Settings"><img src="../wp-content/plugins/wp-qore/images/dashboard/reading-settings.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Discussion</div><a href="options-discussion.php" title="Discussion Settings"><img src="../wp-content/plugins/wp-qore/images/dashboard/discussion-settings.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Media</div><a href="options-media.php" title="Media Settings"><img src="../wp-content/plugins/wp-qore/images/dashboard/media-settings.png"/></a></div>
<div class="dashboard_icons"><div  class="dash_text">Permalink</div><a href="options-permalink.php" title="Permalink Settings"><img src="../wp-content/plugins/wp-qore/images/dashboard/permalink.png"/></a></div>

</div>
</div>

<style type="text/css" media="all" >
@import url("../wp-content/plugins/wp-qore/css/dashboard.css");
</style>

<?php include( ABSPATH . 'wp-admin/admin-footer.php' ); ?>