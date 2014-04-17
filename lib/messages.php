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

class DHDSMESS {

    // Messages
    public static function updateMessage() {
        echo "<div id='message' class='updated fade'><p><strong>".__('Options Updated!', dreamspeed)."</strong></p></div>";
    }

    public static function oldPHPError() {
        echo "<div id='message' class='error fade'><p><strong>".__('Error: The DreamSpeed CDN plugin requires PHP 5.3 or higher to run. Please upgrade your PHP or this plugin will not function correctly.)', dreamspeed)."</strong></p></div>";
    }

}