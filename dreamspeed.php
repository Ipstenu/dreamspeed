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

/**
 * @package dh-ds-backups
 */
 
require_once dirname(__FILE__) . '/lib/defines.php';        // Default settings
require_once dirname(__FILE__) . '/lib/dhds.php';           // The actual code
require_once dirname(__FILE__) . '/lib/messages.php';       // Default messages
require_once dirname(__FILE__) . '/lib/settings.php';       // Admin Settings
require_once dirname(__FILE__) . '/aws/aws-autoloader.php'; // AWS SDK v2