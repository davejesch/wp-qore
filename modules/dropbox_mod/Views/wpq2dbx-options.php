<?php
/**
 * This file contains the contents of the Dropbox admin options page.
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
try {
    if ($errors = get_option('wpq2dbx-init-errors')) {
        delete_option('wpq2dbx-init-errors');
        throw new Exception(__('WordPress Backup to Dropbox failed to initialize due to these database errors.', 'wp-qore') . '<br /><br />' . $errors);
    }

    $validation_errors = null;

    $dropbox = WPQ2DBX_Factory::get('dropbox');
    $config = WPQ2DBX_Factory::get('config');

    $backup = new WPQ2DBX_BackupController();

    $backup->create_dump_dir();

    $disable_backup_now = $config->get_option('in_progress');

    //We have a form submit so update the schedule and options
    if (array_key_exists('wpq2dbx_save_changes', $_POST)) {
        check_admin_referer('b_2_dbx_options_save');

        if (preg_match('/[^A-Za-z0-9-_.\/]/', $_POST['dropbox_location'])) {
            add_settings_error('wpq2dbx_options', 'invalid_subfolder', __('The sub directory must only contain alphanumeric characters.', 'wp-qore'), 'error');

            $dropbox_location = $_POST['dropbox_location'];
            $store_in_subfolder = true;
        } else {
            $config
                ->set_schedule($_POST['day'], @$_POST['time'], $_POST['frequency'])
                ->set_option('store_in_subfolder', @$_POST['store_in_subfolder'] == "on")
                ->set_option('dropbox_location', @$_POST['dropbox_location']);

            add_settings_error('general', 'settings_updated', __('Settings saved.', 'wp-qore'), 'updated');
        }
    } elseif (array_key_exists('unlink', $_POST)) {
        check_admin_referer('b_2_dbx_options_save');
        $dropbox->unlink_account()->init();
    } elseif (array_key_exists('clear_history', $_POST)) {
        check_admin_referer('b_2_dbx_options_save');
        $config->clear_history();
    }

    //Lets grab the schedule and the options to display to the user
    list($unixtime, $frequency) = $config->get_schedule();
    if (!$frequency) {
        $frequency = 'weekly';
    }

    if (!get_settings_errors('wpq2dbx_options')) {
        $dropbox_location = $config->get_option('dropbox_location');
        $store_in_subfolder = $config->get_option('store_in_subfolder');
    }

    $time = date('H:i', $unixtime);
    $day = date('D', $unixtime);
    ?>
<link rel="stylesheet" type="text/css" href="<?php echo $uri ?>/JQueryFileTree/jqueryFileTree.css"/>
<script src="<?php echo $uri ?>/JQueryFileTree/jqueryFileTree.js" type="text/javascript" language="javascript"></script>
<script src="<?php echo $uri ?>/wpq-2-dbx.js" type="text/javascript" language="javascript"></script>
<script type="text/javascript" language="javascript">
    jQuery(document).ready(function ($) {
        $('#frequency').change(function() {
            var len = $('#day option').size();
            if ($('#frequency').val() == 'daily') {
                $('#day').append($("<option></option>").attr("value", "").text('<?php _e('Daily', 'wp-qore'); ?>'));
                $('#day option:last').attr('selected', 'selected');
                $('#day').attr('disabled', 'disabled');
            } else if (len == 8) {
                $('#day').removeAttr('disabled');
                $('#day option:last').remove();
            }
        });

        //Display the file tree with a call back to update the clicked on check box and white list
        $('#file_tree').fileTree({
            root: '<?php echo str_replace("\\", "/", get_sanitized_home_path()) . "/"; ?>',
            script: ajaxurl,
            expandSpeed: 500,
            collapseSpeed: 500,
            multiFolder: false
        });

        $('#togglers .button').click(function() {
            switch ($(this).attr('rel')) {
            case "all":
                // clicking an unchecked, expanded directory triggers a collapse which is confusing
                // skip expanded directories when checking everything (they'll auto-check themselves)
                $('#file_tree .checkbox').not('.checked, .partial, .directory.expanded>.checkbox').click();
                break;
            case "none":
                $('#file_tree .checkbox.checked').click();
                break;
            case "invert":
                $('#file_tree .checkbox').not('.partial, .directory.expanded>.checkbox').click();
                break;
            }
        })

        $('#store_in_subfolder').click(function (e) {
            if ($('#store_in_subfolder').is(':checked')) {
                $('.dropbox_location').show('fast', function() {
                    $('#dropbox_location').focus();
                });
            } else {
                $('#dropbox_location').val('');
                $('.dropbox_location').hide();
            }
        });
    });

    /**
     * Display the Dropbox authorize url, hide the authorize button and then show the continue button.
     * @param url
     */
    function dropbox_authorize(url) {
        window.open(url);
        document.getElementById('continue').style.visibility = 'visible';
        document.getElementById('authorize').style.visibility = 'hidden';
    }
</script>

    <div class="wrap" id="av_main">
    <div class="icon32"><br></div>
    <h2><?php _e('Backup Settings', 'wp-qore'); ?></h2>

    <?php settings_errors(); ?>

    <?php if ($dropbox->is_authorized()) {
        $account_info = $dropbox->get_account_info();
        $used = round(($account_info->quota_info->quota - ($account_info->quota_info->normal + $account_info->quota_info->shared)) / 1073741824, 1);
        $quota = round($account_info->quota_info->quota / 1073741824, 1);
    ?>

    <div id="poststuff">
    <div class="postbox">
    <table class="form-table">
    <tbody>

    <h3><?php _e('Dropbox Account', 'wp-qore'); ?></h3>
    <form id="b_2_dbx_options" name="b_2_dbx_options"
          action="admin.php?page=b2dbx" method="post">
    <div style="padding-left:15px;padding-bottom:15px">
    <p class="bump">
        <?php echo
                $account_info->display_name . ', ' .
                __('you have', 'wp-qore') . ' ' .
                $used .
                '<acronym title="' . __('Gigabyte', 'wp-qore') . '">GB</acronym> ' .
                __('of', 'wp-qore') . ' ' . $quota . 'GB (' . round(($used / $quota) * 100, 0) .
                '%) ' . __('free', 'wp-qore') ?>
    </p>
    <input type="submit" id="unlink" name="unlink" class="bump button-secondary" value="<?php _e('Unlink Account', 'wp-qore'); ?>">
    </div>               
    </tbody>
    </table>
    </div>
    </div>

    <div id="poststuff">
    <div class="postbox">
    <table class="form-table">
    <tbody>
    <h3><?php _e('Next Scheduled', 'wp-qore'); ?></h3>
        <?php
        $schedule = $config->get_schedule();
        if ($schedule) {
            ?>
            <p style="margin-left: 10px;"><?php printf(__('Next backup scheduled for %s at %s', 'wp-qore'), date('Y-m-d', $schedule[ 0 ]), date('H:i:s', $schedule[ 0 ])) ?></p>
            <?php } else { ?>
            <p style="margin-left: 10px;"><?php _e('No backups are scheduled yet. Please select a day, time and frequency below. ', 'wp-qore') ?></p>
            <?php } ?>
    </tbody>
    </table>
    </div>
    </div>

    <div id="poststuff">
    <div class="postbox">
    <table class="form-table">
    <tbody>
        <h3><?php _e('History', 'wp-qore'); ?></h3>
        <?php
        $backup_history = array_reverse($config->get_history());
        if ($backup_history) {
            echo '<ol class="history_box">';
            foreach ($backup_history as $backup_time) {

                if (is_array($backup_time))
                    continue;

                $blog_time = strtotime(date('Y-m-d H', strtotime(current_time('mysql'))) . ':00:00');
                $blog_time += $backup_time - strtotime(date('Y-m-d H') . ':00:00');

                $backup_date = date('l F j, Y', $blog_time);
                $backup_time_str = date('H:i:s', $blog_time);

                echo '<li>' . sprintf(__('Backup completed on %s at %s.', 'wp-qore'), $backup_date, $backup_time_str) . '</li>';
            }
            echo '</ol>';
            echo '<div style="padding-left:15px;padding-bottom:15px"><input type="submit" id="clear_history" name="clear_history"" class="bump button-secondary" value="' . __('Clear history', 'wp-qore') . '"></div>';
        } else {
            echo '<p style="margin-left: 10px;">' . __('No history', 'wp-qore') . '</p>';
        }
        ?>
    </tbody>
    </table>
    </div>
    </div>

    <div id="poststuff">
    <div class="postbox">
    <table class="form-table">
    <tbody>
    <h3><?php _e('Settings', 'wp-qore'); ?></h3>
    <table class="form-table">
        <tbody>
        <tr valign="top">
            <th scope="row"><label
                    for="dropbox_location"><?php _e("Create a new folder within WPQORE app folder", 'wp-qore'); ?></label>
            </th>
            <td>
                <input name="store_in_subfolder" type="checkbox" id="store_in_subfolder"
                       <?php echo $store_in_subfolder ? 'checked="checked"' : ''; ?>>

                <span class="dropbox_location <?php if (!$store_in_subfolder) echo 'hide' ?>">
                    <input name="dropbox_location" type="text" id="dropbox_location"
                           value="<?php echo $dropbox_location; ?>" class="regular-text code" placeholder="foldername">
                </span>
            </td>
        </tr>

        <tr valign="top">
            <th scope="row"><label for="time"><?php _e('Day and Time', 'wp-qore'); ?></label></th>
            <td>
                <select id="day" name="day" <?php echo ($frequency == 'daily') ? 'disabled="disabled"' : '' ?>>
                    <option value="Mon" <?php echo $day == 'Mon' ? ' selected="selected"'
                            : "" ?>><?php _e('Monday', 'wp-qore'); ?></option>
                    <option value="Tue" <?php echo $day == 'Tue' ? ' selected="selected"'
                            : "" ?>><?php _e('Tuesday', 'wp-qore'); ?></option>
                    <option value="Wed" <?php echo $day == 'Wed' ? ' selected="selected"'
                            : "" ?>><?php _e('Wednesday', 'wp-qore'); ?></option>
                    <option value="Thu" <?php echo $day == 'Thu' ? ' selected="selected"'
                            : "" ?>><?php _e('Thursday', 'wp-qore'); ?></option>
                    <option value="Fri" <?php echo $day == 'Fri' ? ' selected="selected"'
                            : "" ?>><?php _e('Friday', 'wp-qore'); ?></option>
                    <option value="Sat" <?php echo $day == 'Sat' ? ' selected="selected"'
                            : "" ?>><?php _e('Saturday', 'wp-qore'); ?></option>
                    <option value="Sun" <?php echo $day == 'Sun' ? ' selected="selected"'
                            : "" ?>><?php _e('Sunday', 'wp-qore'); ?></option>
                    <?php if ($frequency == 'daily') { ?>
                    <option value="" selected="selected"><?php _e('Daily', 'wp-qore'); ?></option>
                    <?php } ?>
                </select> <?php _e('at', 'wp-qore'); ?>
                <select id="time" name="time">
                    <option value="00:00" <?php echo $time == '00:00' ? ' selected="selected"' : "" ?>>00:00
                    </option>
                    <option value="01:00" <?php echo $time == '01:00' ? ' selected="selected"' : "" ?>>01:00
                    </option>
                    <option value="02:00" <?php echo $time == '02:00' ? ' selected="selected"' : "" ?>>02:00
                    </option>
                    <option value="03:00" <?php echo $time == '03:00' ? ' selected="selected"' : "" ?>>03:00
                    </option>
                    <option value="04:00" <?php echo $time == '04:00' ? ' selected="selected"' : "" ?>>04:00
                    </option>
                    <option value="05:00" <?php echo $time == '05:00' ? ' selected="selected"' : "" ?>>05:00
                    </option>
                    <option value="06:00" <?php echo $time == '06:00' ? ' selected="selected"' : "" ?>>06:00
                    </option>
                    <option value="07:00" <?php echo $time == '07:00' ? ' selected="selected"' : "" ?>>07:00
                    </option>
                    <option value="08:00" <?php echo $time == '08:00' ? ' selected="selected"' : "" ?>>08:00
                    </option>
                    <option value="09:00" <?php echo $time == '09:00' ? ' selected="selected"' : "" ?>>09:00
                    </option>
                    <option value="10:00" <?php echo $time == '10:00' ? ' selected="selected"' : "" ?>>10:00
                    </option>
                    <option value="11:00" <?php echo $time == '11:00' ? ' selected="selected"' : "" ?>>11:00
                    </option>
                    <option value="12:00" <?php echo $time == '12:00' ? ' selected="selected"' : "" ?>>12:00
                    </option>
                    <option value="13:00" <?php echo $time == '13:00' ? ' selected="selected"' : "" ?>>13:00
                    </option>
                    <option value="14:00" <?php echo $time == '14:00' ? ' selected="selected"' : "" ?>>14:00
                    </option>
                    <option value="15:00" <?php echo $time == '15:00' ? ' selected="selected"' : "" ?>>15:00
                    </option>
                    <option value="16:00" <?php echo $time == '16:00' ? ' selected="selected"' : "" ?>>16:00
                    </option>
                    <option value="17:00" <?php echo $time == '17:00' ? ' selected="selected"' : "" ?>>17:00
                    </option>
                    <option value="18:00" <?php echo $time == '18:00' ? ' selected="selected"' : "" ?>>18:00
                    </option>
                    <option value="19:00" <?php echo $time == '19:00' ? ' selected="selected"' : "" ?>>19:00
                    </option>
                    <option value="20:00" <?php echo $time == '20:00' ? ' selected="selected"' : "" ?>>20:00
                    </option>
                    <option value="21:00" <?php echo $time == '21:00' ? ' selected="selected"' : "" ?>>21:00
                    </option>
                    <option value="22:00" <?php echo $time == '22:00' ? ' selected="selected"' : "" ?>>22:00
                    </option>
                    <option value="23:00" <?php echo $time == '23:00' ? ' selected="selected"' : "" ?>>23:00
                    </option>
                </select>
                <span class="description"><?php _e('The day and time the backup will run.', 'wp-qore'); ?></span>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row"><label for="frequency"><?php _e('Frequency', 'wp-qore'); ?></label></th>
            <td>
                <select id="frequency" name="frequency">
                    <option value="daily" <?php echo $frequency == 'daily' ? ' selected="selected"' : "" ?>>
                        <?php _e('Daily', 'wp-qore') ?>
                    </option>
                    <option value="weekly" <?php echo $frequency == 'weekly' ? ' selected="selected"' : "" ?>>
                        <?php _e('Weekly', 'wp-qore') ?>
                    </option>
                    <option value="fortnightly" <?php echo $frequency == 'fortnightly' ? ' selected="selected"'
                            : "" ?>>
                        <?php _e('Fortnightly', 'wp-qore') ?>
                    </option>
                    <option value="monthly" <?php echo $frequency == 'monthly' ? ' selected="selected"' : "" ?>>
                        <?php _e('Every 4 weeks', 'wp-qore') ?>
                    </option>
                    <option value="two_monthly" <?php echo $frequency == 'two_monthly' ? ' selected="selected"'
                            : "" ?>>
                        <?php _e('Every 8 weeks', 'wp-qore') ?>
                    </option>
                    <option value="three_monthly" <?php echo $frequency == 'three_monthly' ? ' selected="selected"'
                            : "" ?>>
                        <?php _e('Every 12 weeks', 'wp-qore') ?>
                    </option>
                </select>
                <span class="description"><?php _e('Frequency of backup.', 'wp-qore'); ?></span>
            </td>
        </tr>
        </tbody>
    </table>
    <!--[if !IE | gt IE 7]><!-->
    </tbody>
    </table>
    </div>
    </div>

    <div id="poststuff">
    <div class="postbox">
    <table class="form-table">
    <tbody>
    <h3><?php _e('Excluded Files and Directories', 'wp-qore'); ?></h3>
    <div style="padding-left:15px;padding-bottom:15px">
    <p>
        <span class="description">
            <?php _e('Select the files and directories that you wish to exclude from your backup. You can expand directories with contents by clicking its name.', 'wp-qore') ?><br />
            <strong><?php _e('Notice:', 'wp-qore'); ?></strong>&nbsp;<?php _e('Your SQL dump file will always be backed up regardless of what is selected below.', 'wp-qore'); ?>
        </span>
    </p>
    <div style="width:400px">
    <div id="file_tree" style="border:1px dashed #CCCCCC;padding-left:10px;padding-top:10px;padding-bottom:10px;">
        <div id="circleG" class="start">
            <div id="circleG_1" class="circleG"></div>
            <div id="circleG_2" class="circleG"></div>
            <div id="circleG_3" class="circleG"></div>
        </div>
        <div class="loading start"><?php _e('Loading...', 'wp-qore') ?></div>
    </div>
    </div>
    </div>
    <div style="padding-left:15px;padding-bottom:15px">
    <div id="togglers"><?php _e("Exclude:", 'wp-qore'); ?>
        <span class="button" rel="all" href="#"><?php _e("All", 'wp-qore'); ?></span>
        <span class="button" rel="none" href="#"><?php _e("None", 'wp-qore'); ?></span>
        <span class="button" rel="invert" href="#"><?php _e("Inverse", 'wp-qore'); ?></span>
    </div>
    </div>
    <!--<![endif]-->
    </tbody>
    </table>
    </div>
    </div>

    <p class="submit">
        <input type="submit" id="wpq2dbx_save_changes" name="wpq2dbx_save_changes" class="button-primary" value="<?php _e('Save Changes', 'wp-qore'); ?>">
    </p>
        <?php wp_nonce_field('b_2_dbx_options_save'); ?>
    </form>
        <?php

    } else {

        ?>
    <div id="poststuff">
    <div class="postbox">
    <table class="form-table">
    <tbody>
    <h3><?php _e('WordPress to Dropbox Backup', 'wp-qore'); ?></h3>
    <div style="padding-left:15px">
    <p><?php _e('In order to use this option, you will need to first authorized WP Qore with your Dropbox account.', 'wp-qore'); ?></p>
    <p><?php _e('Please click the Authorize button below and follow the instructions inside the pop up window. Once you have done that, then come back to this page and click Authorize again. Then refresh the page.', 'wp-qore'); ?></p>
        <?php if (array_key_exists('continue', $_POST) && !$dropbox->is_authorized()): ?>
            <?php $dropbox->unlink_account()->init(); ?>
            <p style="color: red"><?php _e('There was an error authorizing WP Qore with your Dropbox account. Please try again.', 'wp-qore'); ?></p>
        <?php endif; ?>
    </div>
    </tbody>
    </table>
    </div>
    </div>
    <form id="b_2_dbx_continue" name="b_2_dbx_continue" method="post">
        <input type="button" name="authorize" id="authorize" value="<?php _e('Authorize', 'wp-qore'); ?>"
               class="button-primary" onclick="dropbox_authorize('<?php echo $dropbox->get_authorize_url() ?>')"/><br/>
        <input style="visibility: hidden;" type="submit" name="continue" id="continue"
               class="button-primary" value="<?php _e('Continue', 'wp-qore'); ?>"/>
    </form>
    </p>
        <?php

    }
} catch (Exception $e) {
    echo '<h3>' . __("Error", 'wp-qore') . '</h3>';
    echo '<p>' . __('There was a fatal error loading WordPress Backup to Dropbox. Please fix the problems listed and reload the page.', 'wp-qore') . '</h3>';
    echo '<p>' . __('If the problem persists please re-install WordPress Backup to Dropbox.', 'wp-qore') . '</h3>';
    echo '<p><strong>' . __('Error message:', 'wp-qore') . '</strong> ' . $e->getMessage() . '</p>';

    if ($dropbox)
        $dropbox->unlink_account();
}
?>
</div>