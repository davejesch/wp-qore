<?php
/**
 * This file contains the contents of the Dropbox admin options page.
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
$v = phpversion();
if ($pos = strpos($v, '-'))
    $v = substr($v, 0, $pos);
?>
<div class="wrap" id="av_main">
    <div class="icon32"><br></div>


    <div id="poststuff">
    <div class="postbox">
    <table class="form-table">
    <tbody>

    <h2><?php _e('Dropbox Backup Module', 'wp-qore'); ?></h2>
    <p><?php _e(sprintf('It is <em>STRONGLY</em> recommended that you upgrade to PHP 5.3 or higher. <a href="%s">As of December 2010</a>, version 5.2 is no longer supported by the PHP community.'), 'wp-qore'); ?></p>

    </tbody>
    </table>
    </div>
    </div>

</div>