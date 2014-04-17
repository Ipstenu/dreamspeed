<?php
/*
Plugin Name: DreamSpeed CDN
Plugin URI: http://wordpress.org/extend/plugins/amazon-web-services/
Description: Copies files to DreamSpeed as they are uploaded to the Media Library. Optionally configure a custom domain name as an alias.
Author: Mika Epstein
Version: 0.1
Author URI: http://dreamhost.com
*/

// Copyright (c) 2013 Brad Touesnard. All rights reserved.
// Copyright (c) 2014 Mika A Epstein. All rights reserved.
//
// This plugin is a fork of the following:
// * http://wordpress.org/extend/plugins/amazon-web-services/
// * https://wordpress.org/plugins/amazon-web-services/
//
// Released under the GPL license v3
// http://www.opensource.org/licenses/gpl-license.php
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************

function amazon_web_services_incompatibile( $msg ) {
	require_once ABSPATH . '/wp-admin/includes/plugin.php';
	deactivate_plugins( __FILE__ );
    wp_die( $msg );
}

if ( is_admin() && ( !defined( 'DOING_AJAX' ) || !DOING_AJAX ) ) {
	if ( version_compare( PHP_VERSION, '5.3.3', '<' ) ) {
		amazon_web_services_incompatibile( __( 'The official Amazon Web Services SDK, which DreamSpeed relies on, requires PHP 5.3 or higher. The plugin has now disabled itself.', 'dreamspeed' ) );
	}
	elseif ( !function_exists( 'curl_version' ) 
		|| !( $curl = curl_version() ) || empty( $curl['version'] ) || empty( $curl['features'] )
		|| version_compare( $curl['version'], '7.16.2', '<' ) )
	{
		amazon_web_services_incompatibile( __( 'The official Amazon Web Services SDK, which DreamSpeed relies on requires cURL 7.16.2+. The plugin has now disabled itself.', 'dreamspeed' ) );
	}
	elseif ( !( $curl['features'] & CURL_VERSION_SSL ) ) {
		amazon_web_services_incompatibile( __( 'The official Amazon Web Services SDK, which DreamSpeed relies on requires that cURL is compiled with OpenSSL. The plugin has now disabled itself.', 'dreamspeed' ) );
	}
	elseif ( !( $curl['features'] & CURL_VERSION_LIBZ ) ) {
		amazon_web_services_incompatibile( __( 'The official Amazon Web Services SDK, which DreamSpeed relies on requires that cURL is compiled with zlib. The plugin has now disabled itself.', 'dreamspeed' ) );
	}
}

require_once 'classes/aws-plugin-base.php';
require_once 'classes/amazon-web-services.php';
require_once 'media/wordpress-s3.php';
require_once 'vendor/aws/aws-autoloader.php';

function amazon_web_services_init() {
    global $amazon_web_services;
    $amazon_web_services = new Amazon_Web_Services( __FILE__ );
}

add_action( 'init', 'amazon_web_services_init' );

function amazon_web_services_activation() {
	if ( !isset( $dreamspeed['key'] ) || !isset( $dreamspeed['secret'] ) ) {
		return;
	}

	if ( !get_site_option( Amazon_Web_Services::SETTINGS_KEY ) ) {
		add_site_option( Amazon_Web_Services::SETTINGS_KEY, array(
			'access_key_id' => $dreamspeed['key'],
			'secret_access_key' => $dreamspeed['secret']
		) );
	}

	unset( $dreamspeed['key'] );
	unset( $dreamspeed['secret'] );

	update_option( 'dreamspeed_cdn', $dreamspeed );
}
register_activation_hook( __FILE__, 'amazon_web_services_activation' );