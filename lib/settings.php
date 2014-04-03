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


include_once( DHDS_PLUGIN_DIR. '/aws/aws-autoloader.php');

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
        add_settings_section( 'keypair_id', __('Key / Access Settings', 'dreamspeed'), 'keypair_callback', 'dh-ds-keypair_page' );
        
        register_setting( 'dh-ds-keypair-settings','dh-ds-key');
        add_settings_field( 'key_id', __('Access Key', 'dreamspeed'), 'key_callback', 'dh-ds-keypair_page', 'keypair_id' );
        
        register_setting( 'dh-ds-keypair-settings','dh-ds-secretkey');
        add_settings_field( 'secretkey_id', __('Secret Key', 'dreamspeed'), 'secretkey_callback', 'dh-ds-keypair_page', 'keypair_id' );

        function keypair_callback() { 
            echo '<p>'. __("Once you've configured your keypair here, you'll be able to use the features of this plugin.", dreamspeed).'</p>';
            var_dump($dreamspeed_options);
            var_dump( get_option( 'dhds_options' ) );
        }
    	function key_callback() {
        	echo '<input type="text" name="dh-ds-key" value="'. $dreamspeed_options['key'] .'" class="regular-text"/>';
    	}
    	function secretkey_callback() {
        	echo '<input type="text" name="dh-ds-secretkey" value="'. $dreamspeed_options['secretkey'] .'" class="regular-text"/>';
    	}

     // Domain Settings
        add_settings_section( 'domain_id', __('Domain Settings', 'dreamspeed'), 'domain_callback', 'dh-ds-domain_page' );
        
        register_setting( 'dh-ds-domain-settings','dh-ds-domain');
        add_settings_field( 'dh-ds-domain_id',  __('URL', 'dreamspeed'), 'domain_url_callback', 'dh-ds-domain_page', 'domain_id' );
        
        function domain_callback() { 
            echo '<p>'. __("Configure your site for CDN by entering your CDN name (or alias if you made one).", dreamspeed).'</p>';
        }
        function domain_url_callback() { 
            echo '<input type="text" name="dh-ds-key" value="'. $dreamspeed_options['url'] .'" class="regular-text"/>';
        }
        
    // Reset Settings
        register_setting( 'dh-ds-reset-settings', 'dh-ds-reset');
    }
}