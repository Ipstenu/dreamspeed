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
    
    // Sync existing media if called
    add_action( 'dreamspeed_media_sync', array( $dreamspeed, 'bulk_upload_to_dreamspeed') );
    add_action( 'import_start', array( $dreamspeed, 'import_start') );
    add_action( 'import_end', array( $dreamspeed, 'import_end') );
}

// If everything is set...
add_action( 'dreamspeed_init', 'dreamspeed_init' );

/**
 * @since 2.0
 * @access public
 * @param mixed $post_id Post ID of the attachment or null to use the loop
 * @param int $expires Secondes for the link to live
 * @return array
 */
function dreamspeed_get_secure_attachment_url( $post_id, $expires = 900, $operation = 'GET' ) {}