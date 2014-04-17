<?php

/*
Plugin Name: DreamSpeed CDN
Plugin URI: https://github.com/Ipstenu/dreamspeed
Description: Connect your WordPress install to your DreamHost DreamSpeed CDN for supercharged media deployment.
Version: 0.1
Author: Mika Epstein
Author URI: http://halfelf.org/
Network: false
Text Domain: dreamspeed
Domain Path: /i18n

Copyright 2012 Mika Epstein (email: ipstenu@ipstenu.org)

    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License v 3 for more details.

    https://www.gnu.org/licenses/gpl-3.0.html

*/

/**
 * @package dh-ds-backups
 */
 
require_once dirname(__FILE__) . '/lib/defines.php';        // Default settings
require_once dirname(__FILE__) . '/lib/dhds.php';           // The actual code
require_once dirname(__FILE__) . '/lib/messages.php';       // Default messages
require_once dirname(__FILE__) . '/lib/settings.php';       // Admin Settings
require_once dirname(__FILE__) . '/aws/aws-autoloader.php'; // AWS SDK v2