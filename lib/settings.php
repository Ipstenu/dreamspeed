<?php
/*
    This file is part of DreamSpeed CDN , a plugin for WordPress.

    DreamSpeed CDN  is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    DreamSpeed CDN  is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WordPress.  If not, see <http://www.gnu.org/licenses/>.

*/

if (!defined('ABSPATH')) {
    die();
}

class DHDSSET {
    /**
     * Generates the settings page
     *
    */
        
    // Add Settings Pages
    public static function add_settings_page() {
        load_plugin_textdomain(dreamspeed, DHDS_PLUGIN_DIR . 'i18n', 'i18n');
        add_action('admin_init', array('DHDSSET', 'add_register_settings'));
        add_media_page(__('DreamSpeed Settings', 'dreamspeed'), __('DreamSpeed', 'dreamspeed'), 'manage_options', 'dreamspeed-menu', array('DHDSSET', 'settings_page'));
    }

    // Define Settings Pages    
    public static function  settings_page() {
        include_once( DHDS_PLUGIN_DIR . '/admin/settings.php');// Main Settings
    }

    // Register Settings (for forms etc)
    public static function add_register_settings() {  
    
        $dreamspeed_options = get_option( 'dhds_options' );

        // Keypair settings
        add_settings_section( 'settings_id', __('Key / Access Settings', 'dreamspeed'), array( DHDSSET, 'settings_callback'), 'dh-ds-settings_sections' );
        
        register_setting( 'dh-ds-settings_fields','dhds_options', array( DHDSSET, 'validate_options') );
        add_settings_field( 'key_id', __('Access Key', 'dreamspeed'), array( DHDSSET, 'key_callback'), 'dh-ds-settings_sections', 'settings_id' );
        
        register_setting( 'dh-ds-settings_fields','dhds_options', array( DHDSSET, 'validate_options') );
        add_settings_field( 'secretkey_id', __('Secret Key', 'dreamspeed'), array( DHDSSET, 'secretkey_callback'), 'dh-ds-settings_sections', 'settings_id' );
        
        register_setting( 'dh-ds-settings_fields','dhds_options', array( DHDSSET, 'validate_options') );
        add_settings_field( 'dh-ds-domain_id',  __('URL', 'dreamspeed'), array( DHDSSET, 'domain_callback'), 'dh-ds-settings_sections', 'settings_id' );
    }
    
    // And all the callbacks:
    static function settings_callback() { 
        echo '<p>'. __("Once you've configured your keypair here, you'll be able to use the features of this plugin.", dreamspeed).'</p>';
    }
	static function key_callback() {
    	echo '<input type="text" name="dhds_options[key]" value="'. DHDS::$options['key'] .'" class="regular-text"/>';	
	}
	static function secretkey_callback() {
    	echo '<input type="text" name="dhds_options[secretkey]" value="'. DHDS::$options['secretkey'] .'" class="regular-text"/>';
	}
    static function domain_callback() { 
        echo '<input type="text" name="dhds_options[url]" value="'. DHDS::$options['url'] .'" class="regular-text"/>';
    }
    
    static function validate_options( $input ) {
	    $options = wp_parse_args(get_option('dhds_options'), DHDS::$defaults );
		$valid = array();

	    foreach ($options as $key=>$value) {
    	    if (!isset($input[$key])) $input[$key]=DHDS::$defaults;
        }
	    
	    foreach ($options as $key=>$value) {
    	    $valid[$key] = $input[$key];
        }

		unset( $input );
		return $valid;
	}
}