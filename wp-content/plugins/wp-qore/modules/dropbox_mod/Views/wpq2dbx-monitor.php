<?php
/**
 * This file contains the contents of the Dropbox admin monitor page.
 *
 * @copyright Copyright (C) 2011-2014 Awesoft Pty. Ltd. All rights reserved.
 * @author Michael De Wildt (http://www.mikeyd.com.au/)
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
$config = WPQ2DBX_Factory::get('config');
$backup = new WPQ2DBX_BackupController();

if (array_key_exists('stop_backup', $_POST)) {
    check_admin_referer('b_2_dbx_monitor_stop');
    $backup->stop();

    add_settings_error('wpq2dbx_monitor', 'backup_stopped', __('Backup stopped.', 'wp-qore'), 'updated');

} elseif (array_key_exists('start_backup', $_POST)) {
    check_admin_referer('b_2_dbx_monitor_stop');
    $backup->backup_now();
    $started = true;

    add_settings_error('wpq2dbx_monitor', 'backup_started', __('Backup started.', 'wp-qore'), 'updated');
}

?>
<script type="text/javascript" language="javascript">
    function reload() {
        jQuery('.files').hide();
        jQuery.post(ajaxurl, { action : 'progress' }, function(data) {
            if (data.length) {
                jQuery('#progress').html(data);
                jQuery('.view-files').on('click', function() {
                    $files = jQuery(this).next();

                    $files.toggle();
                    $files.find('li').each(function() {
                        $this = jQuery(this);
                        $this.css(
                            'background',
                            'url(<?php echo $uri ?>/JQueryFileTree/images/' + $this.text().slice(-3).replace(/^\.+/,'') + '.png) left top no-repeat'
                        );
                    });

                });
            }
        });
        <?php if ($config->get_option('in_progress') || isset($started)): ?>
            setTimeout("reload()", 15000);
        <?php endif; ?>
    }
    jQuery(document).ready(function ($) {
        reload();
    });
</script>
    <div class="wrap" id="av_main">
    <div class="icon32"><br></div>

    <?php settings_errors(); ?>

    <div id="poststuff">
    <div class="postbox">
    <table class="form-table">
    <tbody>

    <h3><?php _e('Backup Monitor', 'wp-qore'); ?></h3>
    <div id="progress" style="padding-left:15px">
        <div id="circleG">
            <div id="circleG_1" class="circleG"></div>
            <div id="circleG_2" class="circleG"></div>
            <div id="circleG_3" class="circleG"></div>
        </div>
        <div class="loading" style="padding-top:15px;padding-bottom:15px"><?php _e('Loading...', 'wp-qore') ?></div>
    </div>

    </tbody>
    </table>
    </div>
    </div>

    <form id="b_2_dbx_options" name="b_2_dbx_options" action="admin.php?page=b2dbx-monitor" method="post">
        <?php if ($config->get_option('in_progress') || isset($started)): ?>
            <input type="submit" id="stop_backup" name="stop_backup" class="button-primary" value="<?php _e('Stop Backup', 'wp-qore'); ?>">
        <?php else: ?>
            <input type="submit" id="start_backup" name="start_backup" class="button-primary" value="<?php _e('Start Backup', 'wp-qore'); ?>">
        <?php endif; ?>

        <?php wp_nonce_field('b_2_dbx_monitor_stop'); ?>
    </form>
</div>