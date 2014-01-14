<?php

class WPQore_Adminbar_Menu{
  
function WPQore_Adminbar_Menu(){
  add_action( 'admin_bar_menu', array( $this, "wpqore_adminbar_links" ), 999 );
}

  /**
   * Add's new global menu, if $href is false menu is added but registered as sub-menu
   *
   * $name String
   * $id String
   * $href Bool/String
   *
   * @return void
   * @author Janez Troha
   * @author Aaron Ware
   **/

  function add_root_menu($name, $id, $href = FALSE){
    global $wp_admin_bar;
    if ( !is_super_admin() || !is_admin_bar_showing() )
        return;

    $wp_admin_bar->add_menu( array(
        'id'   => $id,
        'meta' => array(),
        'title' => $name,
        'href' => $href ) );
  }

  /**
   * Add's new sub-menu where additional $meta specifies class, id, target or onclick parameters
   *
   * $name String
   * $link String
   * $root_menu String
   * $id String
   * $meta Array
   *
   * @return void
   * @author Janez Troha
   **/
  function wpqore_add_submenu($name, $link, $root_menu, $id, $meta = FALSE)
  {
      global $wp_admin_bar;
      if ( ! is_super_admin() || ! is_admin_bar_showing() )
          return;
    
      $wp_admin_bar->add_menu( array(
          'parent' => $root_menu,
          'id' => $id,
          'title' => $name,
          'href' => $link,
          'meta' => $meta
      ) );
  }

  function wpqore_adminbar_links() {
      $this->add_root_menu( __( "WP Qore", 'wp-qore' ), "wpqabl" );
      $this->wpqore_add_submenu( __( "WP Qore Options", 'wp-qore' ), "admin.php?page=wp-qore/functions.php", "wpqabl", "wpqablp" );

     //add cache menu
      if (get_option("wpqorefunc_cache_assistance")=='checked') {
          $this->wpqore_add_submenu( __( "Cache Assistance", 'wp-qore' ), "admin.php?page=Cache_AssistanceOptions", "wpqabl", "wpqabla" );
      }
      
     //add dropbox menu
      if (get_option("wpqorefunc_dropbox_mod")=='checked') {
          $this->wpqore_add_submenu( __( "Backup Settings", 'wp-qore' ), "admin.php?page=b2dbx", "wpqabl", "wpqablbus" );
          $this->wpqore_add_submenu( __( "Backup Monitor", 'wp-qore' ), "admin.php?page=b2dbx-monitor", "wpqabl", "wpqablbum" );
      }

     //add security advisor menu
      if (get_option("wpqorefunc_sec_advisor")=='checked') {
          $this->wpqore_add_submenu( __( "Security Advisor", 'wp-qore' ), "admin.php?page=sec-advisor", "wpqabl", "wpqabli" );
      }
  }

}
add_action( "init", "WPQore_Adminbar_MenuInit" );
function WPQore_Adminbar_MenuInit() {
    global $WPQore_Adminbar_Menu;
    $WPQore_Adminbar_Menu = new WPQore_Adminbar_Menu();
}

?>
