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

class DHDS {

    public static $options;
    public static $defaults;

	    public function __construct() {
	        add_action( 'init', array( &$this, 'init' ) );
	        
	    	// Setting plugin defaults here:
			DHDS::$defaults = array(
		        'key'       => null,
		        'secretkey' => null,
		        'url'       => null,
		        'bucket'    => null,
		        'inuse'     => null,
		    );

            if( !get_option('dhds_options') || is_null( get_option('dhds_options') ) || !is_array( get_option('dhds_options') ) ) {
                update_option('dhds_options', DHDS::$defaults);
            } 
	    }

    // INIT - hooking into this lets us run things when a page is hit.
    public static function init() {

    // Translations
    if ( !defined('dreamspeed')) {define('dreamspeed','dreamspeed');}
    
    // The Help Screen
    function plugin_help() {
        include_once( DHDS_PLUGIN_DIR . '/admin/help.php' );
    }
    add_action('contextual_help', 'plugin_help', 10, 3);
    add_action('admin_menu', array('DHDSSET', 'add_settings_page'));
        
        // UPDATE OPTIONS
        if ( isset($_GET['settings-updated']) && isset($_GET['page']) && ( $_GET['page'] == 'dreamspeed-menu') ) {
            add_action('admin_notices', array('DHDSMESS','updateMessage'));            
        }
/*
        // Actually copy files
        
        if (all the options are set) {
            1. Copy up images in batches
            2. Activate the function DHDS::image_filter
            add_filter( 'init', array( 'DHDS', 'image_filter' ) );
        }
        
*/        
    }
    
    public static function image_filter() {
        /* 
        if (image exists on the cloud) {
            filter content
        } else {
            return
        }
        */
    }
}

DHDS::$options = get_option('dhds_options');

//instantiate the class
if (class_exists('DHDS')) {
	new DHDS();
}