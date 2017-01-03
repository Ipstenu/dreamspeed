<?php
/**
 * Upgrade Functions
 *
 * Forked from Easy Digital Downloads
 * @copyright   Copyright (c) 2015, Pippin Williamson
 * @copyright   Copyright (c) 2017, Mika Epstein
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Render Upgrades Screen
 *
 * @since 0.7.0
 * @return void
*/
function dreamspeed_add_invisible_menu(){
	$dreamspeed_upgrade_page = add_submenu_page( null, __( 'DreamSpeed Upgrades', 'dreamspeed-cdn' ), __( 'DreamSpeed Upgrades', 'dreamspeed-cdn' ), 'activate_plugins', 'dreamspeed-upgrades', 'dreamspeed_upgrades_screen' );
}
add_action( 'admin_menu', 'dreamspeed_add_invisible_menu', 10 );

function dreamspeed_upgrades_screen() {
	$action = isset( $_GET['dreamspeed-upgrade'] ) ? sanitize_text_field( $_GET['dreamspeed-upgrade'] ) : '';
	$step   = isset( $_GET['step'] )        ? absint( $_GET['step'] )                     : 1;
	$total  = isset( $_GET['total'] )       ? absint( $_GET['total'] )                    : false;
	$custom = isset( $_GET['custom'] )      ? absint( $_GET['custom'] )                   : 0;
	$number = isset( $_GET['number'] )      ? absint( $_GET['number'] )                   : 100;
	$steps  = round( ( $total / $number ), 0 );

	$doing_upgrade_args = array(
		'page'        => 'dreamspeed-upgrades',
		'dreamspeed-upgrade' => $action,
		'step'        => $step,
		'total'       => $total,
		'custom'      => $custom,
		'steps'       => $steps
	);
	update_option( 'edd_doing_upgrade', $doing_upgrade_args );
	if ( $step > $steps ) {
		// Prevent a weird case where the estimate was off. Usually only a couple.
		$steps = $step;
	}
	?>

	<div class="wrap">
		<h2><?php _e( 'DreamSpeed CDN - Upgrades', 'dreamspeed-cdn' ); ?></h2>

		<?php if( ! empty( $action ) ) : ?>

			<div id="dreamspeed-upgrade-status">
				<p><?php _e( 'The upgrade process has started, please be patient. This could take several minutes. You will be automatically redirected when the upgrade is finished.', 'dreamspeed-cdn' ); ?></p>

				<?php if( ! empty( $total ) ) : ?>
					<p><strong><?php printf( __( 'Step %d of approximately %d running', 'dreamspeed-cdn' ), $step, $steps ); ?></strong></p>
				<?php endif; ?>
			</div>
			<script type="text/javascript">
				setTimeout(function() { document.location.href = "index.php?dreamspeed_action=<?php echo $action; ?>&step=<?php echo $step; ?>&total=<?php echo $total; ?>&custom=<?php echo $custom; ?>"; }, 250);
			</script>

		<?php else : ?>

			<div id="dreamspeed-upgrade-status">
				<p>
					<?php _e( 'The upgrade process has started, please be patient. This could take several minutes. You will be automatically redirected when the upgrade is finished.', 'dreamspeed-cdn' ); ?>
				</p>
			</div>
			<script type="text/javascript">
				jQuery( document ).ready( function() {
					// Trigger upgrades on page load
					var data = { action: 'dreamspeed_trigger_upgrades' };
					jQuery.post( ajaxurl, data, function (response) {
						if( response == 'complete' ) {
							jQuery('#dreamspeed-upgrade-loader').hide();
							document.location.href = 'admin.php?page=dreamspeed-media'; // Redirect to the welcome page
						}
					});
				});
			</script>

		<?php endif; ?>

	</div>
	<?php
}

/**
 * Display Upgrade Notices
 *
 * @since 0.7.0
 * @return void
*/
function dreamspeed_show_upgrade_notices() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'dreamspeed-upgrades' )
		return; // Don't show notices on the upgrades page

	$cdnoptions = get_option( 'dreamspeed_cdn' );
	$dreamspeed_version = get_option( 'dreamspeed_version' );
	$fullspeed = ( isset($cdnoptions['fullspeed']) )? $cdnoptions['fullspeed'] : '0';

	if ( ! $dreamspeed_version ) {
		// 0.7.0 is the first version to use this option so we must add it
		$dreamspeed_version = '0.6.0';
	}

	$dreamspeed_version = preg_replace( '/[^0-9.].*/', '', $dreamspeed_version );

	// Special warning to remind people about the change
	if ( version_compare( $dreamspeed_version, '0.7.0', '>=' ) && (new DateTime() < new DateTime("2016-09-05 00:00:00") ) && isset( $_GET['page'] ) && ( $_GET['page'] == 'dreamspeed-media' || $_GET['page'] == 'dreamspeed-cdn' ) ) {
		printf(
			'<div class="notice update-nag dreamspeed-v070-notice"><p>' . esc_html__( 'The hostname for DreamObjects has changed. Version 0.7.0 perform a search and replace on upgrades, but if you ever manually imported your old images, please review the %splugin FAQ%s and make sure you update before September 5th, 2016. This alert will remain in place until then.', 'dreamspeed-cdn' ) . '</p></div>',
			'<a href="https://wordpress.org/plugins/dreamspeed-cdn/faq/">',
			'</a>'
		);
	}

	// If you upgraded to 0.7.0, please run this. No it won't go away	
	if ( version_compare( $dreamspeed_version, '0.7.0', '<' ) ) {
		printf(
			'<div class="notice update-nag"><p>' . esc_html__( 'The hostname for DreamObjects has changed, click %shere%s to start the upgrade.', 'dreamspeed-cdn' ) . '</p></div>',
			'<a href="' . esc_url( admin_url( 'options.php?page=dreamspeed-upgrades' ) ) . '">',
			'</a>'
		);
	} elseif ( version_compare( $dreamspeed_version, '0.7.1', '<' ) ) {
		printf(
			'<div class="notice update-nag"><p>' . esc_html__( 'A database change is required in order to optimize your site fully. Please click %shere%s to start the upgrade.', 'dreamspeed-cdn' ) . '</p></div>',
			'<a href="' . esc_url( admin_url( 'options.php?page=dreamspeed-upgrades' ) ) . '">',
			'</a>'
		);
	} elseif ( version_compare( $dreamspeed_version, '0.7.3', '<' ) && $fullspeed == 1 ) {
		printf(
			'<div class="notice update-nag"><p>' . esc_html__( 'A database change is required in order to optimize your site fully. Please click %shere%s to start the upgrade.', 'dreamspeed-cdn' ) . '</p></div>',
			'<a href="' . esc_url( admin_url( 'options.php?page=dreamspeed-upgrades' ) ) . '">',
			'</a>'
		);
	}
}
add_action( 'admin_notices', 'dreamspeed_show_upgrade_notices' );

/**
 * Triggers all upgrade functions
 *
 * This function is usually triggered via AJAX
 *
 * @since 0.7.0
 * @return void
*/
function dreamspeed_trigger_upgrades() {

	if( ! current_user_can( 'activate_plugins' ) ) {
		wp_die( __( 'You do not have permission to perform upgrades', 'dreamspeed-cdn' ), __( 'Error', 'dreamspeed-cdn' ), array( 'response' => 403 ) );
	}

	$dreamspeed_version = get_option( 'dreamspeed_version' );

	if ( ! $dreamspeed_version ) {
		// 0.7.0 is the first version to use this option so we must add it
		$dreamspeed_version = '0.6.0';
		add_option( 'dreamspeed_version', $dreamspeed_version );
	}

	if ( version_compare( $dreamspeed_version, '0.7.0', '<' ) ) {
		dreamspeed_v070_upgrades();
	}

	if ( version_compare( $dreamspeed_version, '0.7.1', '<' ) ) {
		dreamspeed_v071_upgrades();
	}

	if ( version_compare( $dreamspeed_version, '0.7.3', '<' ) ) {
		dreamspeed_v073_upgrades();
	}

	update_option( 'dreamspeed_version', DREAMSPEED_VERSION );

	if ( DOING_AJAX )
		die( 'complete' ); // Let AJAX know that the upgrade is complete
}
add_action( 'wp_ajax_dreamspeed_trigger_upgrades', 'dreamspeed_trigger_upgrades' );

/**
 * Upgrade routine for v0.7.0
 *
 * @since 0.7.0
 * @return void
 */	 
function dreamspeed_v070_upgrades() {

	// Get a list of all attachments WITH S3 metadata - these will be the only ones uploaded
	$attachment_array = array(
			'posts_per_page'   => -1,
			'meta_query' => array(
					array(
							'key' => 'amazonS3_info',
							'compare' => 'EXISTS'
					)
			),
			'post_type'        => 'attachment',
	);

    $attachments = get_posts( $attachment_array );
    
    // Early bail if attachments isn't set or it's empty
    if ( !$attachments || empty($attachments) ) {
        return;
    }
    
    // Check if the old URL is being used and, if so, replace it in posts.
	foreach ( $attachments as $attachment ) {	
		$attachment_oldurl = wp_get_attachment_url( $attachment->ID );
		$oldobjectpath = 'objects.dreamhost.com';
		$newobjectpath = 'objects-us-west-1.dream.io';
		
		if( $attachment->post_parent > 0  && $parent_post = get_post($attachment->post_parent) && (strpos($attachment_oldurl, $oldobjectpath) !== false) ) {				
			$attachment_newurl = str_replace( $oldobjectpath , $newobjectpath , $attachment_oldurl );
        		$parent_post->post_content = str_replace( $attachment_oldurl , $attachment_newurl, $parent_post->post_content );
            wp_update_post($parent_post);
        }
	}
}

/**
 * Upgrade routine for v0.7.1
 *
 * @since 0.7.1
 * @return void
 */	 
function dreamspeed_v071_upgrades() {

	// Get a list of all attachments WITH S3 metadata - these will be the only ones uploaded
	$attachment_array = array(
			'posts_per_page'   => -1,
			'meta_query' => array(
					array(
							'key' => 'amazonS3_info',
							'compare' => 'EXISTS'
					)
			),
			'post_type'        => 'attachment',
	);

    $attachments = get_posts( $attachment_array );
    
    // Early bail if attachments isn't set or it's empty
    if ( !$attachments || empty($attachments) ) {
        return;
    }
    
    // Check if the old URL is being used and, if so, replace it in posts.
	foreach ( $attachments as $attachment ) {	
		$attachment_oldurl = wp_get_attachment_url( $attachment->ID );
		$oldobjectpath = 'objects.cdn.dream.io';
		$newobjectpath = 'objects-us-west-1.dream.io';
		
		if( $attachment->post_parent > 0  && $parent_post = get_post($attachment->post_parent) && (strpos($attachment_oldurl, $oldobjectpath) !== false) ) {				
			$attachment_newurl = str_replace( $oldobjectpath , $newobjectpath , $attachment_oldurl );
        		$parent_post->post_content = str_replace( $attachment_oldurl , $attachment_newurl, $parent_post->post_content );
            wp_update_post($parent_post);
        }
	}
}

/**
 * Upgrade routine for v0.7.3
 *
 * @since 0.7.3
 * @return void
 */	 
function dreamspeed_v073_upgrades() {

	// Early bail if fullspeed isn't set
	$myoptions = get_option( 'dreamspeed_settings' );
	$fullspeed = $myoptions['fullspeed'];
	if ( $fullspeed !== 1 ) {
		return;
	}

	// Get a list of all attachments WITH S3 metadata - these will be the only ones edited
	$attachment_array = array(
			'posts_per_page'   => -1,
			'meta_query' => array(
					array(
							'key' => 'amazonS3_info',
							'compare' => 'EXISTS'
					)
			),
			'post_type'        => 'attachment',
	);

    $attachments = get_posts( $attachment_array );
    
    // Early bail if attachments isn't set or it's empty
    if ( !$attachments || empty($attachments) ) {
        return;
    }
    
    // Check if the old URL is being used and, if so, replace it in posts.
	foreach ( $attachments as $attachment ) {	
		$attachment_oldurl = wp_get_attachment_url( $attachment->ID );
		$oldobjectpath = 'objects-us-west-1.dream.io';
		$newobjectpath = 'objects.cdn.dream.io';
		
		if( $attachment->post_parent > 0  && $parent_post = get_post($attachment->post_parent) && (strpos($attachment_oldurl, $oldobjectpath) !== false) ) {				
			$attachment_newurl = str_replace( $oldobjectpath , $newobjectpath , $attachment_oldurl );
        		$parent_post->post_content = str_replace( $attachment_oldurl , $attachment_newurl, $parent_post->post_content );
            wp_update_post($parent_post);
        }
	}
}