<?php
/*
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