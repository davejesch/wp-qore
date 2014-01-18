<?php
/**
 * Functionality to remove Dropbox backup from your WordPress installation
 *
 * @author Michael De Wildt
 * @license This program is free software; you can redistribute it and/or modify
 *          it under the terms of the GNU General Public License as published by
 *          the Free Software Foundation; either version 2 of the License, or
 *          (at your option) any later version.
 *
 *          This program is distributed in the hope that it will be useful,
 *          but WITHOUT ANY WARRANTY; without even the implied warranty of
 *          MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *          GNU General Public License for more details.
 *
 *          You should have received a copy of the GNU General Public License
 *          along with this program; if not, write to the Free Software
 *          Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110, USA.
 */
if (!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN')) {
    exit();
}

delete_option('b2dbx-tokens');
delete_option('b2dbx-options');
delete_option('b2dbx-history');
delete_option('b2dbx-current-action');
delete_option('b2dbx-actions');
delete_option('b2dbx-excluded-files');
delete_option('b2dbx-file-list');
delete_option('b2dbx-in-progress');
delete_option('b2dbx-processed-files');
delete_option('b2dbx-log');
delete_option('wpq2dbx-init-errors');

wp_clear_scheduled_hook('execute_periodic_drobox_backup');
wp_clear_scheduled_hook('execute_instant_drobox_backup');
wp_clear_scheduled_hook('monitor_dropbox_backup_hook');

remove_action('run_dropbox_backup_hook', 'run_dropbox_backup');
remove_action('monitor_dropbox_backup_hook', 'monitor_dropbox_backup');
remove_action('execute_instant_drobox_backup', 'execute_drobox_backup');
remove_action('execute_periodic_drobox_backup', 'execute_drobox_backup');
remove_action('admin_menu', 'b_2_dbx_admin_menu');
remove_action('wp_ajax_file_tree', 'b_2_dbx_file_tree');
remove_action('wp_ajax_progress', 'b_2_dbx_progress');

global $wpdb;

$table_name = $wpdb->prefix . 'wpq2dbx_options';
$wpdb->query("DROP TABLE IF EXISTS $table_name");

$table_name = $wpdb->prefix . 'wpq2dbx_processed_files';
$wpdb->query("DROP TABLE IF EXISTS $table_name");

$table_name = $wpdb->prefix . 'wpq2dbx_excluded_files';
$wpdb->query("DROP TABLE IF EXISTS $table_name");
