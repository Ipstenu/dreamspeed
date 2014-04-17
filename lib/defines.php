<?php
/*
    This file is part of DreamSpeed CDN , a plugin for WordPress.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License v 3 for more details.

    https://www.gnu.org/licenses/gpl-3.0.html

*/

if (!defined('ABSPATH')) {
    die();
}

// Set up defaults
define( 'DHDS', 'DHDS');
define( 'DHDSSET', 'DHDSSET');
define( 'DHDSMESS', 'DHDSMESS');
defined( 'DHDS_PLUGIN_DIR') || define('DHDS_PLUGIN_DIR', realpath(dirname(__FILE__) . '/..'));

// Auto-discovery disabled
if( !defined('AWS_DISABLE_CONFIG_AUTO_DISCOVERY') ) {
    define( 'AWS_DISABLE_CONFIG_AUTO_DISCOVERY', true );
}

// Error for PHP 5.2
if (version_compare(phpversion(), '5.3', '<')) {
    add_action('admin_notices', array('DHDSMESS','oldPHPError'));
    return;
}

// Standard content folder defines.
if ( ! defined( 'WP_CONTENT_DIR' ) )  define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );