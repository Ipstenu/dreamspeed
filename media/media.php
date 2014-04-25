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

// Columns to show where media is
function dreamspeed_media_column( $cols ) {
        $cols["dreamspeed"] = "CDN";
        return $cols;
}
function dreamspeed_media_column_content( $column_name, $id ) {

    switch ($column_name) {
    case 'dreamspeed' :
    	
    	$meta = get_post_meta($id, 'amazonS3_info' );
        if( !empty( $meta ) ) { echo '<div title="YES!" class="dashicons dashicons-yes"></div>'; }
    break;
    }
}
add_filter( 'manage_media_columns', 'dreamspeed_media_column' );
add_action( 'manage_media_custom_column', 'dreamspeed_media_column_content', 10, 2 );