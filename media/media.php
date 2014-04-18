<?php

/*
    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License v 3 for more details.

    https://www.gnu.org/licenses/gpl-3.0.html

*/

function dreamspeed_init( $dos ) {
    global $dreamspeed;
    require_once 'classes/dreamspeed.php';
    $dreamspeed = new DreamSpeed_Services( __FILE__, $dos );
}

add_action( 'dreamspeed_init', 'dreamspeed_init' );