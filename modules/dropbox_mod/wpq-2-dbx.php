<?php
/*
        Module by: Michael De Wildt
        Modify by: Jason Jersey

        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License, version 2, as
        published by the Free Software Foundation.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('WPQORE_2_DROPBOX_VERSION', '01.13.14');//Date Module Created
define('WPQORE_2_DROPBOX_DATABASE_VERSION', '2');
define('CHUNKED_UPLOAD_THREASHOLD', 10485760); //10 MB
define('MINUMUM_PHP_VERSION', '5.2.16');

if (function_exists('spl_autoload_register')){
    spl_autoload_register('wpq2dbx_autoload');
} else {
    require_once 'Dropbox/Dropbox/API.php';
    require_once 'Dropbox/Dropbox/OAuth/Consumer/ConsumerAbstract.php';
    require_once 'Dropbox/Dropbox/OAuth/Consumer/Curl.php';
    require_once 'Classes/Processed/Base.php';
    require_once 'Classes/Processed/Files.php';
    require_once 'Classes/Processed/DBTables.php';
    require_once 'Classes/DatabaseBackup.php';
    require_once 'Classes/FileList.php';
    require_once 'Classes/DropboxFacade.php';
    require_once 'Classes/Config.php';
    require_once 'Classes/BackupController.php';
    require_once 'Classes/Logger.php';
    require_once 'Classes/Factory.php';
    require_once 'Classes/UploadTracker.php';
}

function wpq2dbx_autoload($className){
    $fileName = str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    if (preg_match('/^WPQ2DBX/', $fileName)) {
        $fileName = 'Classes' . str_replace('WPQ2DBX', '', $fileName);
    } elseif (preg_match('/^Dropbox/', $fileName)) {
        $fileName = 'Dropbox' . DIRECTORY_SEPARATOR . $fileName;
    } else {
        return false;
    }

    $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . $fileName;

    if (file_exists($path)) {
        require_once $path;
    }
}

function wpq2dbx_style(){
    //Register stylesheet
    wp_register_style('wpq2dbx-style', plugins_url('wpq-2-dbx.css', __FILE__) );
    wp_enqueue_style('wpq2dbx-style');
}

/**
 * A wrapper function that adds an options page to setup Dropbox Backup
 * @return void
 */
function b_2_dbx_admin_menu(){

    $text = __('Backup Settings', 'wp-qore');
    add_submenu_page('wp-qore/functions.php', $text, $text, 'activate_plugins', 'b2dbx', 'b_2_dbx_admin_menu_contents');

    if (version_compare(PHP_VERSION, MINUMUM_PHP_VERSION) >= 0) {
        $text = __('Backup Monitor', 'wp-qore');
        add_submenu_page('wp-qore/functions.php', $text, $text, 'activate_plugins', 'b2dbx-monitor', 'b_2_dbx_monitor');

    }
}

/**
 * A wrapper function that includes the backup to Dropbox options page
 * @return void
 */
function b_2_dbx_admin_menu_contents(){
    $uri = rtrim(WP_PLUGIN_URL, '/') . '/wp-qore/modules/dropbox_mod';

    if(version_compare(PHP_VERSION, MINUMUM_PHP_VERSION) >= 0) {
        include 'Views/wpq2dbx-options.php';
    } else {
        include 'Views/wpq2dbx-deprecated.php';
    }
}

/**
 * A wrapper function that includes the backup to Dropbox monitor page
 * @return void
 */
function b_2_dbx_monitor(){
    if (!WPQ2DBX_Factory::get('dropbox')->is_authorized()) {
        b_2_dbx_admin_menu_contents();
    } else {
        $uri = rtrim(WP_PLUGIN_URL, '/') . '/wp-qore/modules/dropbox_mod';
        include 'Views/wpq2dbx-monitor.php';
    }
}

/**
 * A wrapper function for the file tree AJAX request
 * @return void
 */
function b_2_dbx_file_tree(){
    include 'Views/wpq2dbx-file-tree.php';
    die();
}

/**
 * A wrapper function for the progress AJAX request
 * @return void
 */
function b_2_dbx_progress(){
    include 'Views/wpq2dbx-progress.php';
    die();
}

/**
 * A wrapper function that executes the backup
 * @return void
 */
function execute_drobox_backup(){
    WPQ2DBX_Factory::get('logger')->delete_log();
    WPQ2DBX_Factory::get('logger')->log(sprintf(__('Backup started on %s.', 'wp-qore'), date("l F j, Y", strtotime(current_time('mysql')))));

    $time = ini_get('max_execution_time');
    WPQ2DBX_Factory::get('logger')->log(sprintf(
        __('Your time limit is %s and your memory limit is %s'),
        $time ? $time . ' ' . __('seconds', 'wp-qore') : __('unlimited', 'wp-qore'),
        ini_get('memory_limit')
    ));

    if (ini_get('safe_mode')) {
        WPQ2DBX_Factory::get('logger')->log(__("Safe mode is enabled on your server so the PHP time and memory limit cannot be set by the backup process. So if your backup fails it's highly probable that these settings are too low.", 'wp-qore'));
    }

    WPQ2DBX_Factory::get('config')->set_option('in_progress', true);

    if (defined('WPQ2DBX_TEST_MODE')) {
        run_dropbox_backup();
    } else {
        wp_schedule_single_event(time(), 'run_dropbox_backup_hook');
        wp_schedule_event(time(), 'every_min', 'monitor_dropbox_backup_hook');
    }
}

/**
 * @return void
 */
function monitor_dropbox_backup(){
    $config = WPQ2DBX_Factory::get('config');
    $mtime = filemtime(WPQ2DBX_Factory::get('logger')->get_log_file());

    //5 mins to allow for socket timeouts and long uploads
    if ($config->get_option('in_progress') && ($mtime < time() - 300)) {
        WPQ2DBX_Factory::get('logger')->log(sprintf(__('There has been no backup activity for a long time. Attempting to resume the backup.' , 'wp-qore'), 5));
        $config->set_option('is_running', false);

        wp_schedule_single_event(time(), 'run_dropbox_backup_hook');
    }
}

/**
 * @return void
 */
function run_dropbox_backup(){
    $options = WPQ2DBX_Factory::get('config');
    if (!$options->get_option('is_running')) {
        $options->set_option('is_running', true);
        WPQ2DBX_BackupController::construct()->execute();
    }
}

/**
 * Adds a set of custom intervals to the cron schedule list
 * @param  $schedules
 * @return array
 */
function b_2_dbx_cron_schedules($schedules){
    $new_schedules = array(
        'every_min' => array(
            'interval' => 60,
            'display' => 'WPQ2DBX - Monitor'
        ),
        'daily' => array(
            'interval' => 86400,
            'display' => 'WPQ2DBX - Daily'
        ),
        'weekly' => array(
            'interval' => 604800,
            'display' => 'WPQ2DBX - Weekly'
        ),
        'fortnightly' => array(
            'interval' => 1209600,
            'display' => 'WPQ2DBX - Fortnightly'
        ),
        'monthly' => array(
            'interval' => 2419200,
            'display' => 'WPQ2DBX - Once Every 4 weeks'
        ),
        'two_monthly' => array(
            'interval' => 4838400,
            'display' => 'WPQ2DBX - Once Every 8 weeks'
        ),
        'three_monthly' => array(
            'interval' => 7257600,
            'display' => 'WPQ2DBX - Once Every 12 weeks'
        ),
    );

    return array_merge($schedules, $new_schedules);
}

//install dropbox module
function wpq2dbx_install(){
    $wpdb = WPQ2DBX_Factory::db();
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $table_name = $wpdb->prefix . 'wpq2dbx_options';
    dbDelta("CREATE TABLE $table_name (
        name varchar(50) NOT NULL,
        value varchar(255) NOT NULL,
        UNIQUE KEY name (name)
    );");

    $table_name = $wpdb->prefix . 'wpq2dbx_processed_files';
    dbDelta("CREATE TABLE $table_name (
        file varchar(255) NOT NULL,
        offset int NOT NULL DEFAULT 0,
        uploadid varchar(50),
        UNIQUE KEY file (file)
    );");

    $table_name = $wpdb->prefix . 'wpq2dbx_processed_dbtables';
    dbDelta("CREATE TABLE $table_name (
        name varchar(255) NOT NULL,
        count int NOT NULL DEFAULT 0,
        UNIQUE KEY name (name)
    );");

    $table_name = $wpdb->prefix . 'wpq2dbx_excluded_files';
    dbDelta("CREATE TABLE $table_name (
        file varchar(255) NOT NULL,
        isdir tinyint(1) NOT NULL,
        UNIQUE KEY file (file)
    );");

    //Ensure that there are no insert errors
    $errors = array();

    global $EZSQL_ERROR;
    if ($EZSQL_ERROR) {
        foreach ($EZSQL_ERROR as $error) {
            if (preg_match("/^CREATE TABLE {$wpdb->prefix}wpq2dbx_/", $error['query']))
                $errors[] = $error['error_str'];
        }

        delete_option('wpq2dbx-init-errors');
        add_option('wpq2dbx-init-errors', implode($errors, '<br />'), false, 'no');
    }

    //Only set the DB version if there are no errors
    if (empty($errors)) {
        WPQ2DBX_Factory::get('config')->set_option('database_version', WPQORE_2_DROPBOX_DATABASE_VERSION);
    }
}

function get_sanitized_home_path(){
    //Needed for get_home_path() function and may not be loaded
    require_once(ABSPATH . 'wp-admin/includes/file.php');

    //If site address and WordPress address differ but are not in a different directory
    //then get_home_path will return '/' and cause issues.
    $home_path = get_home_path();
    if ($home_path == '/') {
        $home_path = ABSPATH;
    }

    return rtrim(str_replace('/', DIRECTORY_SEPARATOR, $home_path), DIRECTORY_SEPARATOR);
}

//More cron shedules
add_filter('cron_schedules', 'b_2_dbx_cron_schedules');

//Initialize dropbox
function wpq2dbx_init(){
    try {
        if (WPQ2DBX_Factory::get('config')->get_option('database_version') < WPQORE_2_DROPBOX_DATABASE_VERSION) {
            wpq2dbx_install();
        }

    } catch (Exception $e) {
        error_log($e->getMessage());
    }
}