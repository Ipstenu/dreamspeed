<?php

/*
    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License v 3 for more details.

    https://www.gnu.org/licenses/gpl-3.0.html

*/

use Aws\S3\S3Client as AwsS3CDN;

class DreamSpeed_Services extends DreamSpeed_Plugin_Base {
	private $dos, $doclient;

	const SETTINGS_KEY = 'dreamspeed_cdn';

	function __construct( $plugin_file_path, $dos ) {

		parent::__construct( $plugin_file_path );

		$this->aws = $dos;
		add_action( 'aws_admin_menu', array( $this, 'admin_menu' ) );

		$this->plugin_vars = array( 'bucket', 'virtual-host', 'expires', 'permissions', 'cloudfront', 'object-prefix', 'copy-to-s3', 'serve-from-s3', 'remove-local-file', 'force-ssl', 'fullspeed', 'hidpi-images', 'object-versioning', 'region' );

		$this->plugin_title = __( 'DreamSpeed CDN Configuration', 'dreamspeed-cdn' );
		$this->plugin_menu_title = __( 'CDN', 'dreamspeed-cdn' );
		$this->plugin_slug = 'dreamspeed-media';
		
		// Set default region
		$myregion = $this->get_setting( 'region' );
		if ( empty($myregion ) ) { 
			$this->set_setting( 'region' , 'objects-us-west-1.dream.io' );
			$this->save_settings();
		}

		add_action( 'wp_ajax_dreamspeed-create-bucket', array( $this, 'ajax_create_bucket' ) );

		if ( $this->is_plugin_setup() && ( $this->get_setting( 'copy-to-s3' ) == 1 ) && ( get_option('dreamspeed_importer') != 1 ) && !defined( 'WP_IMPORTING' ) ) {
			add_filter( 'wp_get_attachment_url', array( $this, 'wp_get_attachment_url' ), 9, 2 );
			//add_filter( 'wp_calculate_image_srcset_meta' , array( $this, 'wp_calculate_image_srcset_meta' ) );
			add_filter( 'wp_generate_attachment_metadata', array( $this, 'wp_generate_attachment_metadata' ), 20, 2 );
			add_filter( 'delete_attachment', array( $this, 'delete_attachment' ), 20 );
		}
		add_filter( 'manage_media_columns', array( $this, 'media_column' ) );
		add_action( 'manage_media_custom_column', array( $this, 'media_column_content' ), 10, 2 );
	}

	// Columns to show where media is
	function media_column( $cols ) {
	        $cols["dreamspeed-cdn"] = "CDN";
	        return $cols;
	}

	function media_column_content( $column_name, $id ) {
	    switch ($column_name) {
		    case 'dreamspeed-cdn' :
		    	$meta = get_post_meta($id, 'amazonS3_info' );
		        if( !empty( $meta ) ) {
		        	echo '<div title="YES!" class="dashicons dashicons-yes" style="color: #7ad03a;"></div>';
		        } else {
			        echo '<div title="No :(" class="dashicons dashicons-no" style="color: #dd3d36;"></div>';
		        }
		    break;
	    }
	}

	function get_setting( $key ) {
		$settings = $this->get_settings();

		// If legacy setting set, migrate settings
		if ( isset( $settings['wp-uploads'] ) && $settings['wp-uploads'] && in_array( $key, array( 'copy-to-s3', 'serve-from-s3' ) ) ) {
			return '1';
		}

		// Default object prefix
		if ( 'object-prefix' == $key && !isset( $settings['object-prefix'] ) ) {
			$uploads = wp_upload_dir();
			$parts = parse_url( $uploads['baseurl'] );
			$path = $parts['path'];
			return substr( $path, 1 ) . '/';
		}

		return parent::get_setting( $key );
	}

	function delete_attachment( $post_id ) {
		if ( !$this->is_plugin_setup() ) {
			return;
		}

		$backup_sizes = get_post_meta( $post_id, '_wp_attachment_backup_sizes', true );

		$intermediate_sizes = array();
		foreach ( get_intermediate_image_sizes() as $size ) {
			if ( $intermediate = image_get_intermediate_size( $post_id, $size ) )
				$intermediate_sizes[] = $intermediate;
		}

		if ( !( $dsobject = $this->get_attachment_dreamspeed_info( $post_id ) ) ) {
			return;
		}

		$amazon_path = dirname( $dsobject['key'] );
		$objects = array();

		// remove intermediate and backup images if there are any
		foreach ( $intermediate_sizes as $intermediate ) {
			$objects[] = array(
				'Key' => path_join( $amazon_path, $intermediate['file'] )
			);
		}

		if ( is_array( $backup_sizes ) ) {
			foreach ( $backup_sizes as $size ) {
				$objects[] = array(
					'Key' => path_join( $amazon_path, $del_file )
				);
			}
		}

		// Try removing any @2x images but ignore any errors
		if ( $objects ) {
			$hidpi_images = array();
			foreach ( $objects as $object ) {
				$hidpi_images[] = array(
					'Key' => $this->get_hidpi_file_path( $object['Key'] )
				);
			}

			try {
				$this->get_doclient()->deleteObjects( array(
					'Bucket' => $dsobject['bucket'],
					'Objects' => $hidpi_images
				) );
			}
			catch ( Exception $e ) {}
		}

		$objects[] = array(
			'Key' => $dsobject['key']
		);

		try {
			$this->get_doclient()->deleteObjects( array(
				'Bucket' => $dsobject['bucket'],
				'Objects' => $objects
			) );
		}
		catch ( Exception $e ) {
			error_log( 'Error removing files from DreamSpeed: ' . $e->getMessage() );
			return;
		}

		delete_post_meta( $post_id, 'amazonS3_info' );
	}

	function wp_generate_attachment_metadata( $data, $post_id ) {
		if ( !$this->get_setting( 'copy-to-s3' ) || !$this->is_plugin_setup() ) {
			return $data;
		}

		$time = $this->get_attachment_folder_time( $post_id );
		$time = date( 'Y/m', $time );

		$prefix = ltrim( trailingslashit( $this->get_setting( 'object-prefix' ) ), '/' );
		$prefix .= ltrim( trailingslashit( $this->get_dynamic_prefix( $time ) ), '/' );

		if ( $this->get_setting( 'object-versioning' ) ) {
			$prefix .= $this->get_object_version_string( $post_id );
		}

		$type = get_post_mime_type( $post_id );

		$file_path = get_attached_file( $post_id, true );

		$acl = apply_filters( 'wps3_upload_acl', 'public-read', $type, $data, $post_id, $this ); // Old naming convention, will be deprecated soon
		$acl = apply_filters( 'dreamspeed_upload_acl', $acl, $data, $post_id );

		if ( !file_exists( $file_path ) ) {
			return $data;
		}

		$file_name = basename( $file_path );
		$files_to_remove = array( $file_path );

		$doclient = $this->get_doclient();

		$bucket = $this->get_setting( 'bucket' );

		$args = array(
			'Bucket'	 => $bucket,
			'Key'		=> $prefix . $file_name,
			'SourceFile' => $file_path,
			'ACL'		=> $acl
		);

		// If far future expiration checked (10 years)
		if ( $this->get_setting( 'expires' ) ) {
			$args['Expires'] = date( 'D, d M Y H:i:s O', time()+315360000 );
		}

		try {
			$doclient->putObject( $args );
		}
		catch ( Exception $e ) {
			error_log( 'Error uploading ' . $file_path . ' to S3: ' . $e->getMessage() );
			return $data;
		}

		delete_post_meta( $post_id, 'amazonS3_info' );

		add_post_meta( $post_id, 'amazonS3_info', array(
			'bucket' => $bucket,
			'key'	=> $prefix . $file_name
		) );

		$additional_images = array();

		if ( isset( $data['thumb'] ) && $data['thumb'] ) {
			$path = str_replace( $file_name, $data['thumb'], $file_path );
			$additional_images[] = array(
				'Key'		=> $prefix . $data['thumb'],
				'SourceFile' => $path
			);
			$files_to_remove[] = $path;
		}
		elseif ( !empty( $data['sizes'] ) ) {
			foreach ( $data['sizes'] as $size ) {
				$path = str_replace( $file_name, $size['file'], $file_path );
				$additional_images[] = array(
					'Key'		=> $prefix . $size['file'],
					'SourceFile' => $path
				);
				$files_to_remove[] = $path;
			}
		}

		// Because we're just looking at the filesystem for files with @2x
		// this should work with most HiDPI plugins
		if ( $this->get_setting( 'hidpi-images' ) ) {
			$hidpi_images = array();

			foreach ( $additional_images as $image ) {
				$hidpi_path = $this->get_hidpi_file_path( $image['SourceFile'] );
				if ( file_exists( $hidpi_path ) ) {
					$hidpi_images[] = array(
						'Key'		=> $this->get_hidpi_file_path( $image['Key'] ),
						'SourceFile' => $hidpi_path
					);
					$files_to_remove[] = $hidpi_path;
				}
			}

			$additional_images = array_merge( $additional_images, $hidpi_images );
		}

		foreach ( $additional_images as $image ) {
			try {
				$args = array_merge( $args, $image );
				$doclient->putObject( $args );
			}
			catch ( Exception $e ) {
				error_log( 'Error uploading ' . $args['SourceFile'] . ' to DreamSpeed: ' . $e->getMessage() );
			}
		}

		if ( $this->get_setting( 'remove-local-file' ) ) {
			$this->remove_local_files( $files_to_remove );
		}

		return $data;
	}

	function remove_local_files( $file_paths ) {
		foreach ( $file_paths as $path ) {
			if ( !@unlink( $path ) ) {
				error_log( 'Error removing local file ' . $path );
			}
		}
	}

	function get_hidpi_file_path( $orig_path ) {
		$hidpi_suffix = apply_filters( 'dreamspeed_hidpi_suffix', '@2x' );
		$pathinfo = pathinfo( $orig_path );
		return $pathinfo['dirname'] . '/' . $pathinfo['filename'] . $hidpi_suffix . '.' . $pathinfo['extension'];
	}

	function get_object_version_string( $post_id ) {
		if ( get_option( 'uploads_use_yearmonth_folders' ) ) {
			$date_format = 'dHis';
		}
		else {
			$date_format = 'YmdHis';
		}

		$time = $this->get_attachment_folder_time( $post_id );

		$object_version = date( $date_format, $time ) . '/';
		$object_version = apply_filters( 'dreamspeed_get_object_version_string', $object_version );

		return $object_version;
	}

	// Media files attached to a post use the post's date
	// to determine the folder path they are placed in
	function get_attachment_folder_time( $post_id ) {
		$time = current_time( 'timestamp' );

		if ( !( $attach = get_post( $post_id ) ) ) {
			return $time;
		}

		if ( !$attach->post_parent ) {
			return $time;
		}

		if ( !( $post = get_post( $attach->post_parent ) ) ) {
			return $time;
		}

		if ( substr( $post->post_date_gmt, 0, 4 ) > 0 ) {
			return strtotime( $post->post_date_gmt . ' +0000' );
		}

		return $time;
	}

	function wp_get_attachment_url( $url, $post_id ) {
		$new_url = $this->get_attachment_url( $post_id );
		if ( false === $new_url ) {
			return $url;
		}

		$new_url = apply_filters( 'dreamspeed_wp_get_attachment_url', $new_url, $post_id );

		return $new_url;
	}

	/*
	 * Since 0.4.0
	 *
	 * WP 4.4 added in srcsets so we must filter. 
	 */
	 //apply_filters( 'wp_calculate_image_srcset_meta', $image_meta, $size_array, $image_src, $attachment_id )
	function wp_calculate_image_srcset_meta( ) {
		
		//IF the image exists in CDN (that is if the attachment ID has amazons3 tagged) then change the URL
	
	}

	function get_attachment_dreamspeed_info( $post_id ) {
		return get_post_meta( $post_id, 'amazonS3_info', true );
	}

	function is_plugin_setup() {
		return (bool) $this->get_setting( 'bucket' ) && !is_wp_error( $this->aws->get_client() );
	}

	/**
	 * Generate a link to download a file from Amazon S3 using query string
	 * authentication. This link is only valid for a limited amount of time.
	 *
	 * @param mixed $post_id Post ID of the attachment or null to use the loop
	 * @param int $expires Seconds for the link to live
	 */
	function get_secure_attachment_url( $post_id, $expires = 900 ) {
		return $this->get_attachment_url( $post_id, $expires );
	}

	function get_attachment_url( $post_id, $expires = null ) {
		if ( !$this->get_setting( 'serve-from-s3' ) || !( $dsobject = $this->get_attachment_dreamspeed_info( $post_id ) ) ) {
			return false;
		}

		// Is SSL
		$scheme = ( is_ssl() || $this->get_setting( 'force-ssl' ) )? 'https' : 'http';
		
		// Does the bucket have a period?
		$bucket_period = ( strpos( $this->get_setting( 'bucket' ) , ".") == false)? false: true;
		
		// Big If Section
		if ( $scheme == 'https' && $this->get_setting( 'fullspeed' ) !== 1 ) {
			// https://objects-us-west-1.dream.io/BUCKET
			$domain_base = $this->get_setting( 'region' ). '/' . $this->get_setting( 'bucket' );
		} elseif ( $this->get_setting( 'cloudfront' ) ) {
			// https://CUSTOM.example.io
			$domain_base = $this->get_setting( 'cloudfront' );
		} elseif ( $this->get_setting( 'fullspeed' ) == 1 ) {
			// http(s)://BUCKET.objects.cdn.dream.io	 OR http(s)://objects-REGION.dream.io/BUCKET	 (becuase Fastly)
			$domain_base = ( $bucket_period == false )? $this->get_setting( 'bucket' ) . '.objects.cdn.dream.io' :  'objects.cdn.dream.io/' . $this->get_setting( 'bucket' );
		} else {
			// http(s)://BUCKET.objects-REGION.dream.io OR http(s)://objects-REGION.dream.io/BUCKET
			$domain_base = ( $bucket_period == false )? $this->get_setting( 'bucket' ) . '.' . $this->get_setting( 'region' ) : $this->get_setting( 'region' ) . '/' . $this->get_setting( 'bucket' );
		}

		$url = $scheme . '://' . $domain_base . '/' . $dsobject['key'];

		if ( !is_null( $expires ) ) {
			try {
				$expires = time() + $expires;
				$secure_url = $this->get_doclient()->getObjectUrl( $dsobject['bucket'], $dsobject['key'], $expires );
				$url .= substr( $secure_url, strpos( $secure_url, '?' ) );
			} catch ( Exception $e ) {
				return new WP_Error( 'exception', $e->getMessage() );
			}
		}

		return apply_filters( 'dreamspeed_get_attachment_url', $url, $dsobject, $post_id, $expires );
	}

	function verify_ajax_request() {
		if ( !is_admin() || !wp_verify_nonce( $_POST['_nonce'], $_POST['action'] ) ) {
			wp_die( __( 'Cheatin&#8217; eh?', 'dreamspeed-cdn' ) );
		}

		if ( !current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.', 'dreamspeed-cdn' ) );
		}
	}

	function admin_menu( $dos ) {
		$hook_suffix = $dos->add_page( $this->plugin_title, $this->plugin_menu_title, 'manage_options', $this->plugin_slug, array( $this, 'render_page' ) );
		add_action( 'load-' . $hook_suffix , array( $this, 'plugin_load' ) );
	}

	function get_doclient() {
		if ( is_null( $this->doclient ) ) {
			$this->doclient = $this->aws->get_client()->get( 's3' );
		}
		return $this->doclient;
	}

	function get_buckets() {
		try {
			$result = $this->get_doclient()->listBuckets();
		}
		catch ( Exception $e ) {
			return new WP_Error( 'exception', $e->getMessage() );
		}
		
		return $result['Buckets'];
	}

	function get_regions() {
		$regions = array(
			'objects-us-west-1.dream.io'    => __('US West 1', 'dreamobjects'),
		);
		return $regions;
	}

	function plugin_load() {
		wp_enqueue_script( 'dreamspeed-script', plugins_url( 'classes/script.js', $this->plugin_file_path ), array( 'jquery' ), $this->get_installed_version(), true );

		wp_localize_script( 'dreamspeed-script', 'dreamspeed_i18n', array(
			'create_bucket_prompt'  => __( 'Bucket Name:', 'dreamspeed-cdn' ),
			'create_bucket_error'	=> __( 'Error creating bucket: ', 'dreamspeed-cdn' ),
			'create_bucket_nonce'	=> wp_create_nonce( 'dreamspeed-create-bucket' )
		) );

		$this->handle_post_request();
	}

	function handle_post_request() {
		if ( empty( $_POST['action'] ) || !in_array($_POST['action'], array('save', 'migrate')) ) {
			return;
		} elseif ( empty( $_POST['_wpnonce'] ) || !wp_verify_nonce( $_POST['_wpnonce'], 'dreamspeed-save-settings' ) ) {
			die( __( "Cheatin' eh?", 'dreamspeed-cdn' ) );
		} elseif ( 'migrate' == $_POST['action'] && ( 1 == $_POST['migrate-to-dreamspeed'] ) ) {
			$this->bulk_upload_to_dreamspeed();
			wp_redirect( 'admin.php?page=' . $this->plugin_slug . '&migrated=1' );
		} elseif ( 'save' == $_POST['action'] ) {

			$this->set_settings( array() );

			$post_vars = $this->plugin_vars;
			foreach ( $post_vars as $var ) {
				if ( !isset( $_POST[$var] ) ) {
					continue;
				}
				$cleanvar =  esc_html( $_POST[$var] );

				if ( $var == 'cloudfront' ) {
					$cloudfront_url = $cleanvar;
					if (!preg_match('#^http(s)?://#', $cleanvar) ) {
						$cloudfront_url = 'http://' . $cleanvar;
					}
					$cloudfront_url = parse_url($cloudfront_url);
					$cleanvar = $cloudfront_url['host'];
				}
				$this->set_setting( $var, $cleanvar );
			}

			$this->save_settings();

			wp_redirect( 'admin.php?page=' . $this->plugin_slug . '&updated=1' );
		} else {
			wp_redirect( 'admin.php?page=' . $this->plugin_slug . '&error=1' );
		}

		exit;
	}

	function render_page() {
		$dos_client = $this->aws->get_client();

		if ( is_wp_error( $dos_client ) ) {
			$this->render_view( 'error', array( 'error' => $dos_client ) );
		}
		else {
			$this->render_view( 'settings' );
		}
	}

	function get_dynamic_prefix( $time = null ) {
		$uploads = wp_upload_dir( $time );

		if ( !is_multisite() ) {
			$path = $uploads['path'];
		} else {
			$path = $uploads['subdir'];
		}

		return str_replace( $this->get_base_upload_path(), '', $path );
	}

	// Without the multisite subdirectory
	function get_base_upload_path() {
		if ( defined( 'UPLOADS' ) && ! ( is_multisite() && get_site_option( 'ms_files_rewriting' ) ) ) {
			return ABSPATH . UPLOADS;
		}

		$upload_path = trim( get_option( 'upload_path' ) );

		if ( empty( $upload_path ) || 'wp-content/uploads' == $upload_path ) {
			return WP_CONTENT_DIR . '/uploads';
		} elseif ( 0 !== strpos( $upload_path, ABSPATH ) ) {
			// $dir is absolute, $upload_path is (maybe) relative to ABSPATH
			return path_join( ABSPATH, $upload_path );
		} else {
			return $upload_path;
		}
	}

	// Upload things that already exist
	// CREDIT: https://github.com/bradt/wp-tantan-s3/pull/45

	function bulk_upload_to_dreamspeed() {

		$max_time = ini_get('max_execution_time');
        $time_start = microtime(true);

        // make sure this exists
        if ( !function_exists('wp_generate_attachment_metadata') ) {
			include( ABSPATH . 'wp-admin/includes/image.php' );
        }

		$attachments = $this->get_attachment_without_dreamspeed_info();

		if($attachments && !empty($attachments) ) {
			foreach ($attachments as $attachment) {
				if( microtime(true) - $time_start >= $max_time - 5 ) {
	                if ( !wp_next_scheduled( 'dreamspeed_media_sync' ) ) {
						wp_schedule_event( time(), 'hourly', 'dreamspeed_media_sync' );
					}
	                break;
	            }

				$file   = get_attached_file( $attachment->ID );
				$data   = wp_generate_attachment_metadata( $attachment->ID, $file );
				$this->wp_generate_attachment_metadata( array(), $attachment->ID);

				if( $attachment->post_parent > 0  ) {
	                if( $parent_post = get_post($attachment->post_parent)) {

						$upload_dir = wp_upload_dir();
						$base_url = $upload_dir['baseurl'];

						// Quick check to make sure Multisite old school isn't being Midvale School for the Gifted.
						if ( defined( 'DOMAIN_MAPPING' ) ) {
							$base_url_ms = str_replace( get_original_url( 'siteurl' ), site_url(), $base_url );
							$parent_post->post_content = str_replace($base_url_ms.'/', $this->get_base_url(), $parent_post->post_content );
						}

	                	$parent_post->post_content = str_replace($base_url.'/', $this->get_base_url(), $parent_post->post_content );
	                    wp_update_post($parent_post);
	                }
	            }

			}
		} else {
            wp_clear_scheduled_hook( 'dreamspeed_media_sync' );
		}
	}

	function get_base_url() {
		// Bail early if we're not using this
		if ( !$this->get_setting( 'serve-from-s3' ) ) {
			return false;
		}

		// Is SSL
		$scheme = ( is_ssl() || $this->get_setting( 'force-ssl' ) )? 'https' : 'http';

		// Does the bucket have a period?
		$bucket_period = ( strpos( $this->get_setting( 'bucket' ), ".") == false)? false: true;

		// Big If Section
		if ( $scheme == 'https' && $this->get_setting( 'fullspeed' ) !== 1 ) {
			// https://objects-us-west-1.dream.io/BUCKET
			$domain_base = $this->get_setting( 'region' ).'/'.$this->get_setting( 'bucket' );
		} elseif ( $this->get_setting( 'cloudfront' ) ) {
			// http://custom.example.io
			$domain_base = $this->get_setting( 'cloudfront' );
		} elseif ( $this->get_setting( 'fullspeed' ) == 1) {
			// http(s)://BUCKET.objects.cdn.dream.io	 OR http(s)://objects-REGION.dream.io/BUCKET	 (becuase Fastly)
			$domain_base = ( $bucket_period == false )? $this->get_setting( 'bucket' ) . '.objects.cdn.dream.io' : 'objects.cdn.dream.io/' . $this->get_setting( 'bucket' );
		} else {
			// http(s)://BUCKET.objects-REGION.dream.io OR http(s)://objects-REGION.dream.io/BUCKET
			$domain_base = ( $bucket_period == false )? $this->get_setting( 'bucket' ) . '.' . $this->get_setting( 'region' ) : $this->get_setting( 'region' ) . '/' . $this->get_setting( 'bucket' );
		}

		$url = $scheme . '://' . $domain_base . '/' . esc_attr( $this->get_setting( 'object-prefix' ) );

		return $url;
	}

	function get_attachment_without_dreamspeed_info() {
		$args = array(
				'posts_per_page'   => -1,
				'meta_query' => array(
						array(
								'key' => 'amazonS3_info',
								'compare' => 'NOT EXISTS'
						)
				),
				'post_type'        => 'attachment',
		);
		return get_posts( $args );
	}

	function import_start() {
		update_option( 'dreamspeed_importer', 1 );
	}

	function import_end() {
		update_option( 'dreamspeed_importer', 0 );
		if ( !wp_next_scheduled( 'dreamspeed_media_sync' ) ) {
			wp_schedule_event( time(), 'hourly', 'dreamspeed_media_sync' );
		}


		echo "<p>".__( 'All of your imported media has not <em>YET</em> been uploaded to the DreamSpeed Cloud, but it will be soon! The uploads are scheduled via the bulk uploader feature.', 'dreamspeed-cdn')."</p>";
	}

}