<?php
/**
 * Deactivation Functions
 *
 */
 
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Deactivate Revert.
 * Revert post content to localhost.
 * 
 * @access public
 * @return void
 */
function dreamspeed_cdn_deactivate_revert() {

	// Get a list of all attachments WITH S3 metadata - these will be the only ones updated
	$attachment_array = array(
			'posts_per_page' => -1,
			'post_type'      => 'attachment',
			'meta_query'     => array(
					array(
							'key'     => 'amazonS3_info',
							'compare' => 'EXISTS'
					)
			),
	);
    $attachments = get_posts( $attachment_array );
    
    // Early bail if attachments isn't set or it's empty
    if ( !$attachments || empty($attachments) ) return;

	// Create Object URL Paths
	$dreamspeed_settings = get_option( 'dreamspeed_cdn' );
	
	// Is SSL
	$scheme = ( is_ssl() || $dreamspeed_settings[ 'force-ssl' ] )? 'https' : 'http';
	
	// Does the bucket have a period?
	$bucket_period = ( strpos( $dreamspeed_settings[ 'bucket' ] , ".") == false)? false: true;
	
	// Is SSL
	$scheme = ( is_ssl() || !empty( $dreamspeed_settings[ 'force-ssl' ] ) )? 'https' : 'http';
	
	// Does the bucket have a period?
	$bucket_period = ( strpos( $dreamspeed_settings[ 'bucket' ] , ".") == false)? false: true;


	// Big If Section
	if ( $scheme == 'https' && ( !empty( $dreamspeed_settings[ 'fullspeed' ] ) && $dreamspeed_settings[ 'fullspeed' ] !== 1 ) ) {
		// https://objects-us-west-1.dream.io/BUCKET
		$domain_base = $dreamspeed_settings[ 'region' ]. '/' . $dreamspeed_settings[ 'bucket' ];
	} elseif ( !empty( $dreamspeed_settings[ 'cloudfront' ] ) ) {
		// https://CUSTOM.example.io
		$domain_base = $dreamspeed_settings[ 'cloudfront' ];
	} elseif ( ( !empty( $dreamspeed_settings[ 'fullspeed' ] ) && $dreamspeed_settings[ 'fullspeed' ] == 1 ) ) {
		// http(s)://BUCKET.objects.cdn.dream.io	 OR http(s)://objects-REGION.dream.io/BUCKET	 (becuase Fastly)
		$domain_base = ( $bucket_period == false )? $dreamspeed_settings[ 'bucket' ] . '.objects.cdn.dream.io' :  'objects.cdn.dream.io/' . $dreamspeed_settings[ 'bucket' ];
	} else {
		// http(s)://BUCKET.objects-REGION.dream.io OR http(s)://objects-REGION.dream.io/BUCKET
		$domain_base = ( $bucket_period == false )? $dreamspeed_settings[ 'bucket' ] . '.' . $dreamspeed_settings[ 'region' ] : $dreamspeed_settings[ 'region' ] . '/' . $dreamspeed_settings[ 'bucket' ];
	}

	$oldobjectpath = $scheme . '://' . $domain_base .'/'. $dreamspeed_settings[ 'object-prefix' ];	
	$upload_dir    = wp_upload_dir();
	$newobjectpath = $upload_dir['baseurl'].'/';

	// Search and replace the content and guids	
	dreamspeed_cdn_deactivate_urls( $oldobjectpath, $newobjectpath );

    // Check if the old URL is being used and, if so, replace it in posts.
	foreach ( $attachments as $attachment ) {
		delete_post_meta( $attachment->ID, 'amazonS3_info' );
	}
}

/**
 * Deactivate URLs
 * 
 * Search all the content and replace the URLs
 * Forked from https://wordpress.org/plugins/velvet-blues-update-urls/
 *
 * @access public
 * @param mixed $oldurl
 * @param mixed $newurl
 */
function dreamspeed_cdn_deactivate_urls( $oldurl, $newurl ) {	
	global $wpdb;
	$queries = array(
		'content' => "UPDATE $wpdb->posts SET post_content = replace( post_content, %s, %s )",
		'guids'   => "UPDATE $wpdb->posts SET guid = replace( guid, %s, %s )",
	);
	foreach( $queries as $option => $value ) {
		$result = $wpdb->query( $wpdb->prepare( $value, $oldurl, $newurl ) );
	}		
}

/**
 * Deactivate Reset.
 * Delete all the options
 * 
 * @access public
 * @return void
 */
function dreamspeed_cdn_deactivate_reset() {
    delete_option( 'dreamspeed_cdn' );
    delete_option( 'dreamspeed_version' );
    delete_option( 'dreamspeed_settings' );
    delete_option( 'dreamspeed_importer' );
    delete_option( 'dreamspeed_doing_upgrade' );
}