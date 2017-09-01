<?php
/*
    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License v 3 for more details.

    https://www.gnu.org/licenses/gpl-3.0.html

*/

//if uninstall not called from WordPress exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

// Deactivation Script
include_once( 'deactivate.php' );

// Multisite
global $wpdb;
$blogs = $wpdb->get_results("SELECT blog_id FROM {$wpdb->blogs}", ARRAY_A);

if ($blogs) {
	foreach($blogs as $blog) {
		switch_to_blog($blog['blog_id']);
		dreamspeed_cdn_deactivate_revert();
		dreamspeed_cdn_deactivate_reset();
	}
    restore_current_blog();
} else {
	dreamspeed_cdn_deactivate_revert();
	dreamspeed_cdn_deactivate_reset();
}