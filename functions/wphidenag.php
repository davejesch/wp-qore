<?php

function wphidenag_updates(){
    remove_action( 'admin_notices', 'update_nag', 3 );
}

add_action('admin_menu','wphidenag_updates');

?>
