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
        add_media_page(__('DreamSpeed Settings', dreamspeed), __('DreamSpeed', dreamspeed), 'manage_options', 'dreamspeed-menu', array('DHDSSET', 'settings_page'));
    }

    // Define Settings Pages    
    public static function  settings_page() {
        include_once( DHDS_PLUGIN_DIR . '/admin/settings.php');// Main Settings
    }

    // Register Settings (for forms etc)
    public static function add_register_settings() {  

        // Keypair settings
        add_settings_section( 'settings1_id', __('Keypair Settings', dreamspeed), array( DHDSSET, 'settings1_callback'), 'dh-ds-settings1_sections' );
        
        register_setting( 'dh-ds-settings_fields','dhds_options', array( DHDSSET, 'validate_options') );
        add_settings_field( 'key_id', __('Access Key', dreamspeed), array( DHDSSET, 'key_callback'), 'dh-ds-settings1_sections', 'settings1_id' );
        
        register_setting( 'dh-ds-settings_fields','dhds_options', array( DHDSSET, 'validate_options') );
        add_settings_field( 'secretkey_id', __('Secret Key', dreamspeed), array( DHDSSET, 'secretkey_callback'), 'dh-ds-settings1_sections', 'settings1_id' );

        // Bucket and Domain settings
        add_settings_section( 'settings2_id', __('Bucket and Domain Settings', dreamspeed), array( DHDSSET, 'settings2_callback'), 'dh-ds-settings2_sections' );

        
        register_setting( 'dh-ds-settings_fields','dhds_options', array( DHDSSET, 'validate_options') );
        add_settings_field( 'dh-ds-domain_id',  __('Domain (URL)', dreamspeed), array( DHDSSET, 'domain_callback'), 'dh-ds-settings2_sections', 'settings2_id' );

        register_setting( 'dh-ds-settings_fields','dhds_options', array( DHDSSET, 'validate_options') );
        add_settings_field( 'dh-ds-bucket_id',  __('Bucket', dreamspeed), array( DHDSSET, 'bucket_callback'), 'dh-ds-settings2_sections', 'settings2_id' );
    }
    
    // And all the callbacks:
    static function settings1_callback() { 
        echo '<p>'. __("Once you've configured your keypair here, you'll be able to use the features of this plugin.", dreamspeed).'</p>';
    }
	static function key_callback() {
    	echo '<input type="text" name="dhds_options[key]" value="'. DHDS::$options['key'] .'" class="regular-text"/>';	
	}
	static function secretkey_callback() {
    	echo '<input type="text" name="dhds_options[secretkey]" value="'. DHDS::$options['secretkey'] .'" class="regular-text"/>';
	}

    static function settings2_callback() { 
        echo '<p>'. __("Define your CDN URL and your image bucket.", dreamspeed).'</p>';
    }
    static function domain_callback() { 
        echo '<input type="text" name="dhds_options[url]" value="'. DHDS::$options['url'] .'" class="regular-text"/>';
        echo '<p class="description">'.__('The URL (including http://) of your CDN or it\'s alias.', dreamspeed).'</p>';
    }
    static function bucket_callback() { 
        if ( DHDS::$options['key'] && DHDS::$options['secretkey'] ) {
            $dreamobjects = \Aws\S3\S3Client::factory( array(
                            'key'   => DHDS::$options['key'],
                            'secret'    => DHDS::$options['secretkey'],
                            'base_url'  => 'https://objects.dreamhost.com'
                )
            );
        
            // checking access_key, secret_key and bucket_name
            try {
                $result = $dreamobjects->listBuckets();
        
                ?>
                
                <select name="dhds_options[bucket]">
                    <option value="">(select a bucket)</option>
                    <?php 
                        foreach ($result['Buckets'] as $bucket) {
                            ?>
                            <option <?php if ( $bucket['Name'] == DHDS::$options['bucket'] ) echo 'selected="selected"' ?>><?php echo $bucket['Name'] ?></option>
                            <?php
                        }
                    ?>
                </select>        
                <?php
        
            } catch (\Aws\S3\Exception\AccessDeniedException $e) {
                echo "<p><strong>".__('Access denied. Please check your keys.', dreamspeed)."</strong></p>";
            }
        }
    }

    static function validate_options( $input ) {
	    $options = wp_parse_args(get_option('dhds_options'), DHDS::$defaults );
	    $defaults = DHDS::$defaults;
		$valid = array();

	    foreach ($options as $key=>$value) {
    	    if (!isset($input[$key])) $input[$key]=$defaults[$key];
        }
	    
	    foreach ($options as $key=>$value) {
    	    $valid[$key] = $input[$key];
        }

		unset( $input );
		return $valid;
	}
}