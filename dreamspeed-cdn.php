<?php
/*
Plugin Name: DreamSpeed CDN
Plugin URI: https://github.com/Ipstenu/dreamspeed/
Description: Copies files to DreamSpeed as they are uploaded to the Media Library. Optionally configure a custom domain name as an alias.
Author: Mika Epstein
Version: 0.3.1
Author URI: http://dreamhost.com
Text Domain: dreamspeed-cdn
Domain Path: i18n

Copyright (c) 2013 Brad Touesnard. All rights reserved.
Copyright (c) 2014 Mika A Epstein. All rights reserved.

This plugin is a fork of the following:
 * http://wordpress.org/extend/plugins/amazon-web-services/
 * https://wordpress.org/plugins/amazon-web-services/

    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License v 3 for more details.

    https://www.gnu.org/licenses/gpl-3.0.html

*/

function dreamspeed_core_incompatibile( $msg ) {
	require_once ABSPATH . '/wp-admin/includes/plugin.php';
	deactivate_plugins( __FILE__ );
    wp_die( $msg );
}

if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
	if ( version_compare( PHP_VERSION, '5.3.3', '<' ) ) {
		dreamspeed_core_incompatibile( __( 'The official Amazon Web Services SDK, which DreamSpeed relies on, requires PHP 5.3 or higher. The plugin has now disabled itself.', 'dreamspeed-cdn' ) );
	}
	elseif ( !function_exists( 'curl_version' ) 
		|| !( $curl = curl_version() ) || empty( $curl['version'] ) || empty( $curl['features'] )
		|| version_compare( $curl['version'], '7.16.2', '<' ) )
	{
		dreamspeed_core_incompatibile( __( 'The official Amazon Web Services SDK, which DreamSpeed relies on requires cURL 7.16.2+. The plugin has now disabled itself.', 'dreamspeed-cdn' ) );
	}
	elseif ( !( $curl['features'] & CURL_VERSION_SSL ) ) {
		dreamspeed_core_incompatibile( __( 'The official Amazon Web Services SDK, which DreamSpeed relies on requires that cURL is compiled with OpenSSL. The plugin has now disabled itself.', 'dreamspeed-cdn' ) );
	}
	elseif ( !( $curl['features'] & CURL_VERSION_LIBZ ) ) {
		dreamspeed_core_incompatibile( __( 'The official Amazon Web Services SDK, which DreamSpeed relies on requires that cURL is compiled with zlib. The plugin has now disabled itself.', 'dreamspeed-cdn' ) );
	}
}

require_once 'classes/plugin-base.php';
require_once 'classes/dreamobjects.php';
require_once 'media/media.php';

if (false === class_exists('Symfony\Component\ClassLoader\UniversalClassLoader', false)) {
	require_once 'aws/aws-autoloader.php';
}

if ( !get_option('dreamspeed_importer')) {update_option( 'dreamspeed_importer', 0 );}
		
function dreamspeed_core_init() {
    global $dreamspeed_core;
    $dreamspeed_core = new DreamSpeed_DHO_Services( __FILE__ );
    define('DreamSpeed_Services', true);
}

add_action( 'init', 'dreamspeed_core_init' );
