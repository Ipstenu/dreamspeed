<?php
/*
    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License v 3 for more details.

    https://www.gnu.org/licenses/gpl-3.0.html

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
<<<<<<< HEAD
            } 
=======
            }
>>>>>>> master
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
            
        if ( DHDS::$options['inuse'] == 1 ) {
            require_once dirname(__FILE__) . '/tantan.php';        // Forking wp-tantan-s3            
        }

    }
    
}

DHDS::$options = get_option('dhds_options');

//instantiate the class
if (class_exists('DHDS')) {
	new DHDS();
}