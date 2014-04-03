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

// Set up defaults
define( 'DHDS', true);
defined( 'DHDS_PLUGIN_DIR') || define('DHDS_PLUGIN_DIR', realpath(dirname(__FILE__) . '/..'));

// Auto-discovery disabled
if( !defined('AWS_DISABLE_CONFIG_AUTO_DISCOVERY') ) {
    define( 'AWS_DISABLE_CONFIG_AUTO_DISCOVERY', true );
}

// Error for PHP 5.2
if (version_compare(phpversion(), '5.3', '<')) {
    add_action('admin_notices', array('DHDOMESS','oldPHPError'));
    return;
}

// Standard content folder defines.
if ( ! defined( 'WP_CONTENT_DIR' ) )  define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );

// Translations
if ( !defined('dreamspeed')) {define('dreamspeed','dreamspeed');}

// Setting Options
if (!get_option( 'dhds_options' )) {
    $dreamspeed_options = get_option( 'dhds_options' );
        if ( !isset($dreamspeed_options['key']) ) $dreamspeed_options['key'] = 'Enter Key Here';
        if ( !isset($dreamspeed_options['secretkey']) ) $dreamspeed_options['secretkey'] = 'Enter Secret Key Here';
        if ( !isset($dreamspeed_options['url']) ) $dreamspeed_options['url'] = 'https://objects.dreamhost.com';
    update_option('dhds_options', $dreamspeed_options);
} else {
    $dreamspeed_options = get_option( 'dhds_options' );
}

// The Help Screen
function dreamhost_dreamspeed_plugin_help() {
	include_once( DHDS_PLUGIN_DIR . '/admin/help.php' );
}
//add_action('contextual_help', 'dreamhost_dreamspeed_plugin_help', 10, 3);

// Etc
add_action('admin_menu', array('DHDSSET', 'add_settings_page'));
add_action( 'init', array( 'DHDS', 'init' ) );