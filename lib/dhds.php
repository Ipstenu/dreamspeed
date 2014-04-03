<?php
/*
    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WordPress.  If not, see <http://www.gnu.org/licenses/>.

*/

if (!defined('ABSPATH')) {
    die();
}

include_once( DHDS_PLUGIN_DIR. '/aws/aws-autoloader.php');

class DHDS {
    // INIT - hooking into this lets us run things when a page is hit.

    public static function init() {

        $dreamspeed_options = get_option( 'dhds_options' );

        // RESET
        if ( current_user_can('manage_options') && isset($_POST['dhds-reset']) && $_POST['dhds-reset'] == 'Y'  ) {
            delete_option( 'dhds_options' );
           }
        
        // UPDATE OPTIONS
        if ( isset($_GET['settings-updated']) && isset($_GET['page']) && ( $_GET['page'] == 'dreamspeed-menu' || $_GET['page'] == 'dreamspeed-menu-backup' || $_GET['page'] == 'dreamspeed-menu-uploader' ) ) add_action('admin_notices', array('DHDSMESS','updateMessage'));
    }
   
}