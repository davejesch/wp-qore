<?php
/**
 * A class with functions the perform a backup of WordPress
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
class WPQ2DBX_BackupController
{
    private
        $dropbox,
        $config,
        $output,
        $processed_file_count
        ;

    public static function construct()
    {
        return new self();
    }

    public function __construct($output = null)
    {
        $this->config = WPQ2DBX_Factory::get('config');
        $this->dropbox = WPQ2DBX_Factory::get('dropbox');
        $this->output = $output ? $output : WPQ2DBX_Extension_Manager::construct()->get_output();
    }

    public function backup_path($path, $dropbox_path = null, $always_include = null)
    {
        if (!$this->config->get_option('in_progress')) {
            return;
        }

        if (!$dropbox_path) {
            $dropbox_path = get_sanitized_home_path();
        }

        $file_list = WPQ2DBX_Factory::get('fileList');

        $current_processed_files = $uploaded_files = array();

        $next_check = time() + 5;
        $total_files = $this->config->get_option('total_file_count');

        $processed_files = WPQ2DBX_Factory::get('processed-files');

        $this->processed_file_count = $processed_files->get_file_count();

        $last_percent = 0;

        if (file_exists($path)) {
            $source = realpath($path);
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST, RecursiveIteratorIterator::CATCH_GET_CHILD);
            foreach ($files as $file_info) {
                $file = $file_info->getPathname();

                if (time() > $next_check) {
                    $this->config->die_if_stopped();

                    $processed_files->add_files($current_processed_files);
                    $msg = null;

                    if ($this->processed_file_count > 0) {
                        $msg = _n(__('Processed 1 file.', 'wp-qore'), sprintf(__('Processed %s files.', 'wp-qore'), $this->processed_file_count), $this->processed_file_count, 'wp-qore');
                    }

                    if ($total_files > 0) {
                        $percent_done = round(($this->processed_file_count / $total_files) * 100, 0);
                        if ($percent_done < 100) {
                            if ($percent_done < 1) {
                                $percent_done = 1;
                            }

                            if ($percent_done > $last_percent) {
                                $msg .= ' ' . sprintf(__('Approximately %s%% complete.', 'wp-qore'), $percent_done);
                                $last_percent = $percent_done;
                            }
                        }
                    }

                    if ($msg) {
                        WPQ2DBX_Factory::get('logger')->log($msg, $uploaded_files);
                    }

                    $next_check = time() + 5;
                    $uploaded_files = $current_processed_files = array();
                }

                if ($file != $always_include && $file_list->is_excluded($file)) {
                    continue;
                }

                if ($file_list->in_ignore_list($file)) {
                    continue;
                }

                if (is_file($file)) {
                    $processed_file = $processed_files->get_file($file);
                    if ($processed_file && $processed_file->offset == 0) {
                        continue;
                    }

                    if (dirname($file) == $this->config->get_backup_dir() && $file != $always_include) {
                        continue;
                    }

                    if ($this->output->out($dropbox_path, $file, $processed_file)) {
                        $uploaded_files[] = array(
                            'file' => str_replace($dropbox_path . DIRECTORY_SEPARATOR, '', WPQ2DBX_DropboxFacade::remove_secret($file)),
                            'mtime' => filemtime($file),
                        );

                        if ($processed_file && $processed_file->offset > 0) {
                            $processed_files->file_complete($file);
                        }
                    }

                    $current_processed_files[] = $file;
                    $this->processed_file_count++;
                }
            }
        }
    }

    public function execute()
    {
        $manager = WPQ2DBX_Extension_Manager::construct();
        $logger = WPQ2DBX_Factory::get('logger');
        $dbBackup = WPQ2DBX_Factory::get('databaseBackup');

        $this->config->set_time_limit();
        $this->config->set_memory_limit();

        try {

            if (!$this->dropbox->is_authorized()) {
                $logger->log(__('Your Dropbox account is not authorized yet.', 'wp-qore'));

                return;
            }

            //Create the SQL backups
            $dbStatus = $dbBackup->get_status();
            if ($dbStatus == WPQ2DBX_DatabaseBackup::NOT_STARTED) {
                if ($dbStatus == WPQ2DBX_DatabaseBackup::IN_PROGRESS) {
                    $logger->log(__('Resuming SQL backup.', 'wp-qore'));
                } else {
                    $logger->log(__('Starting SQL backup.', 'wp-qore'));
                }

                $dbBackup->execute();

                $logger->log(__('SQL backup complete. Starting file backup.', 'wp-qore'));
            }

            if ($this->output->start()) {

                //Backup the content dir first
                $this->backup_path(WP_CONTENT_DIR, dirname(WP_CONTENT_DIR), $dbBackup->get_file());

                //Now backup the blog root
                $this->backup_path(get_sanitized_home_path());

                //End any output extensions
                $this->output->end();

                //Record the number of files processed to make the progress meter more accurate
                $this->config->set_option('total_file_count', $this->processed_file_count);
            }

            $manager->complete();

            //Update log file with stats
            $logger->log(__('Backup complete.', 'wp-qore'));
            $logger->log(sprintf(__('A total of %s files were processed.', 'wp-qore'), $this->processed_file_count));
            $logger->log(sprintf(
                __('A total of %dMB of memory was used to complete this backup.', 'wp-qore'),
                (memory_get_usage(true) / 1048576)
            ));

            //Process the log file using the default backup output
            $root = false;
            if (get_class($this->output) != 'WPQ2DBX_Extension_DefaultOutput') {
                $this->output = new WPQ2DBX_Extension_DefaultOutput();
                $root = true;
            }

            $this->output->set_root($root)->out(get_sanitized_home_path(), $logger->get_log_file());

            $this->config
                ->complete()
                ->log_finished_time()
                ;

            $this->clean_up();

        } catch (Exception $e) {
            if ($e->getMessage() == 'Unauthorized') {
                $logger->log(__('The plugin is no longer authorized with Dropbox.', 'wp-qore'));
            } else {
                $logger->log(__('A fatal error occured: ', 'wp-qore') . $e->getMessage());
            }

            $manager->failure();
            $this->stop();
        }
    }

    public function backup_now()
    {
        if (defined('WPQ2DBX_TEST_MODE')) {
            execute_drobox_backup();
        } else {
            wp_schedule_single_event(time(), 'execute_instant_drobox_backup');
        }
    }

    public function stop()
    {
        $this->config->complete();
        $this->clean_up();
    }

    private function clean_up()
    {
        WPQ2DBX_Factory::get('databaseBackup')->clean_up();
        WPQ2DBX_Extension_Manager::construct()->get_output()->clean_up();
    }

    private static function create_silence_file()
    {
        $silence = WPQ2DBX_Factory::get('config')->get_backup_dir() . DIRECTORY_SEPARATOR . 'index.php';
        if (!file_exists($silence)) {
            $fh = @fopen($silence, 'w');
            if (!$fh) {
                throw new Exception(
                    sprintf(
                        __("WordPress does not have write access to '%s'. Please grant it write privileges before using this plugin."),
                        WPQ2DBX_Factory::get('config')->get_backup_dir()
                    )
                );
            }
            fwrite($fh, "<?php\n// Silence is golden.\n");
            fclose($fh);
        }
    }

    public static function create_dump_dir()
    {
        $dump_dir = WPQ2DBX_Factory::get('config')->get_backup_dir();
        $error_message  = sprintf(__("WordPress Backup to Dropbox requires write access to '%s', please ensure it exists and has write permissions.", 'wp-qore'), $dump_dir);

        if (!file_exists($dump_dir)) {
            //It really pains me to use the error suppressor here but PHP error handling sucks :-(
            if (!@mkdir($dump_dir)) {
                throw new Exception($error_message);
            }
        } elseif (!is_writable($dump_dir)) {
            throw new Exception($error_message);
        }

        self::create_silence_file();
    }
}
