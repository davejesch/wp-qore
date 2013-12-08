<?php
/*
 * Cache Assistance
 * @Since 1.3.9
 *
 */ 

        // Add required functions
	include_once('functions/cache.php');
        
	$wpfc = new Cache_Assistance();

	if(is_admin()){

	    // Add options panel
	    $wpfc->add_OptionsPanel();

	}else{

	    // Begin caching
	    $wpfc->doCache();

	}