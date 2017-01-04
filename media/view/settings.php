<?php
/*
    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License v 3 for more details.

    https://www.gnu.org/licenses/gpl-3.0.html

*/
?>

<div class="wrap dreamspeed-main">

	<h2><?php echo ( isset( $page_title ) ) ? $page_title : __( 'DreamSpeed CDN Settings', 'dreamspeed-cdn' ); ?></h2>

	<div class="dreamspeed-content dreamspeed-settings">
	
	<?php
	
	// Preflight checks
	$regions	    = $this->get_regions();
	$myregion   = $this->get_setting( 'region' );
	
	$buckets    = $this->get_buckets();
	$thisbucket = $this->get_setting( 'bucket' );

	$copy_to_s3 = $this->get_setting( 'copy-to-s3' );
	
	if ( is_wp_error( $buckets ) && !empty($myregion) ) {
		?>
		<div class="error">
			<p>
				<?php _e( 'Error retrieving a list of your buckets from DreamObjects:', 'dreamspeed-cdn' ); ?>
				<?php echo $buckets->get_error_message(); ?>
			</p>
		</div>
		<?php
	}
	
	if ( isset( $_GET['updated'] ) ) {
		?><div id="message" class="is-dismissible updated"><p><?php _e( 'Settings saved.', 'dreamspeed-cdn' ); ?></p></div><?php
	} 
	
	if ( isset( $_GET['migrated'] ) ) {
		?><div id="message" class="is-dismissible updated"><p><?php _e( 'Existing files migrating.', 'dreamspeed-cdn' ); ?></p></div><?php
	}
	
	if (isset( $_GET['error'] ) ) {
		?><div id="message" class="error"><p><?php _e( 'Warning. You cannot migrate existing files without that checkbox.', 'dreamspeed-cdn' ); ?></p></div><?php
	}
	?>

	<h3><?php _e( 'Settings', 'dreamspeed-cdn' ); ?></h3>
	<p><?php _e( 'Configure your site for CDN by selecting your bucket and path settings.', 'dreamspeed-cdn' ); ?></p>
	<p><?php printf( __( 'If you need to change configurations in your DreamSpeed bucket, you can do so in your <a href="%s">panel</a>.', 'dreamspeed-cdn' ), 'https://panel.dreamhost.com/index.cgi?tree=cloud.objects&' ); ?></p>
	
	<form method="post">
	<input type="hidden" name="action" value="save" />
	<?php wp_nonce_field( 'dreamspeed-save-settings' ) ?>
	<table class="form-table">
		
	<tr>
		<th scope="row"><?php _e( 'Buckets Names', 'dreamspeed-cdn' ); ?></th>
		<td><select name="bucket" class="bucket">
			<option value="">-- <?php _e( 'Select a Bucket', 'dreamspeed-cdn' ); ?> --</option>
			<?php if ( is_array( $buckets ) ) foreach ( $buckets as $bucket ): 
				?><option value="<?php echo esc_attr( $bucket['Name'] ); ?>" <?php echo $bucket['Name'] == $this->get_setting( 'bucket' ) ? 'selected="selected"' : ''; ?>><?php echo esc_html( $bucket['Name'] ); ?></option><?php
			endforeach;?>
		</select>
		
		<p class="description"><?php _e( 'Select from pre-existing buckets.', 'dreamspeed-cdn' ); ?></p>
		<p class="description"><?php _e( 'You should not change this once set, as it can break any existing CDN uploads. If you must change it, you will need to manually edit your content to the new URL.', 'dreamspeed-cdn' ); ?></p></td>
	</tr>
	
	<?php if ( !empty( $thisbucket ) ) { ?>

<!-- The following section is hidden because RIGHT NOW it's not done. It's not ready. We ony have one region. Once it is done, however, then it can be unhidden and used. -->
<!--
	<tr>
		<th scope="row"><?php _e( 'Region', 'dreamspeed-cdn' ); ?></th>
		<td>	
			<select name="region" class="region">
			<option value="">-- <?php _e( 'Select a Region', 'dreamspeed-cdn' ); ?> --</option>
			<?php 		
			if ( is_array( $regions ) ) foreach ( $regions as $key => $value  ): 
				if ( ( $key == $myregion ) || ( empty($myregion) && $value == $defaultregion ) ) {
					$selected = 'selected="selected"';
				} else {
					$selected = '';
				}
				?>
			    <option value="<?php echo esc_attr( $key ); ?>" <?php echo $selected ?>><?php echo esc_html( $value ); ?></option>
			<?php endforeach;?>
		</select>
		
		<p class="description"><?php _e( 'Select what DreamObjects region you want to use', 'dreamspeed-cdn' ); ?></p>
		<p class="description"><?php _e( 'You should not change this once set, as it will break any existing CDN uploads. Resources are not replicated across regions unless you do so specifically.', 'dreamspeed-cdn' ); ?></p></td>
	</tr>
-->

	<tr><th scope="row"><?php _e( 'Paths', 'dreamspeed-cdn' ); ?></th>
		<td>
			<p><strong><?php _e( 'Folder Structure', 'dreamspeed-cdn' ); ?></strong></p>
			<p><?php _e( 'Determine the name of your folder structure for your media. At this time, you cannot remove the year or month in order to prevent file-name collisions. If you already have folders (aka objects) in your bucket, please make sure to select a new location.', 'dreamspeed-cdn' ); ?></p>
			<input type="text" name="object-prefix" value="<?php echo esc_attr( $this->get_setting( 'object-prefix' ) ); ?>" size="30" />
			<label><?php echo trailingslashit( $this->get_dynamic_prefix() ); ?></label></p>
			<p class="description"><?php _e( 'The default is <code>wp-content/uploads/</code> (or <code>wp-content/uploads/site/#/</code> for Multisite).', 'dreamspeed-cdn' ); ?></p>

			<p><br /><strong><?php _e( 'CDN Alias', 'dreamspeed-cdn' ); ?></strong></p>			
			<p><?php _e( 'If you use an alias for your CDN (like http://cdn.example.com) then you can tell DreamSpeed to use that instead of the default. Both URLs will always work, but pretty CDN is pretty.', 'dreamspeed-cdn' ); ?></p>
			<?php if (is_ssl()) {
					?> https:// <?php
				} else {
					?> http:// <?php
				} 
			?>
			<input type="text" name="cloudfront" value="<?php echo esc_attr( $this->get_setting( 'cloudfront' ) ); ?>" size="50" <?php if ( is_ssl() || $this->get_setting( 'fullspeed' ) || $this->get_setting( 'force-ssl' ) ) { echo 'disabled'; } ?>/>
			<p class="description"><?php _e( 'Make sure to set up a DNS alias and test it first.', 'dreamspeed-cdn' ); ?></p>
			<p class="description"><em><?php _e( 'This feature will automatically disable itself if you\'re using HTTPS as you can\'t use HTTPS with a CDN Alias at this time because of SSL Certificates. If you\'ve selected to use our accelerated CDN, it is also disabled to prioritize speed.', 'dreamspeed-cdn' ); ?>
			</p>
		</td>
	</tr>

	<tr><th scope="row"><?php _e( 'Options', 'dreamspeed-cdn' ); ?></th>
		<td>
			<input type="checkbox" name="copy-to-s3" value="1" id="copy-to-s3" <?php checked( $this->get_setting( 'copy-to-s3' ), 1 ); ?> />
			<label for="copy-to-s3"> <?php _e( 'Copy files to DreamSpeed as they are uploaded to the Media Library', 'dreamspeed-cdn' ); ?></label>
			<p class="description"><?php _e( 'Recommended. Required to serve media in posts from DreamSpeed.', 'dreamspeed-cdn' ); ?></p>
			
			<input type="checkbox" name="serve-from-s3" value="1" id="serve-from-s3" <?php checked( $this->get_setting( 'serve-from-s3' ), 1 ); ?> />
			<label for="serve-from-s3"> <?php _e( 'Serve all available files from DreamSpeed', 'dreamspeed-cdn' ); ?></label>
			<p class="description"><?php _e( 'Recommended. Required to serve media in posts from DreamSpeed.', 'dreamspeed-cdn' ); ?></p>

			<input type="checkbox" name="fullspeed" value="1" id="fullspeed" <?php checked( $this->get_setting( 'fullspeed' ), 1 ); ?> />
			<label for="fullspeed"> <?php _e( 'Serve accelerated files', 'dreamspeed-cdn' ); ?></label>
			<p class="description"><?php _e( 'Recommended. Results in fastest display of content. Requires you to activate DreamSpeed CDN in panel.', 'dreamspeed-cdn' ); ?></p>

			<input type="checkbox" name="force-ssl" value="1" id="force-ssl" <?php checked( $this->get_setting( 'force-ssl' ), 1 ); checked( is_ssl(), 1 ); ?> />
			<label for="force-ssl"> <?php _e( 'Always serve files over https (SSL)', 'dreamspeed-cdn' ); ?></label>
			<p class="description"><?php _e( 'Slower but most secure. Overrides any CDN aliases. This will be automatically selected if you use SSL for your site.', 'dreamspeed-cdn' ); ?></p>

			<input type="checkbox" name="expires" value="1" id="expires" <?php checked( $this->get_setting( 'expires' ), 1 ); ?> />
			<label for="expires"> <?php printf( __( 'Set a <a href="%s" target="_blank">far future HTTP expiration header</a> for uploaded files', 'dreamspeed-cdn' ), 'http://developer.yahoo.com/performance/rules.html#expires' ); ?></label>
			<p class="description"><?php _e( 'Recommended. Makes Google PageSpeed and ySlow tests happiest.', 'dreamspeed-cdn' ); ?></p>
			
		</td>
	</tr>
	
	<?php } ?>
	
	<tr valign="top">
		<td colspan="2">
			<button type="submit" class="button button-primary"><?php _e( 'Save Changes', 'dreamspeed-cdn' ); ?></button>
		</td>
	</tr>
	</table>
	
	</form>
	
	<?php 
		$all_attachments = wp_count_attachments();
	
		if ( $copy_to_s3 == 1 && !empty( $thisbucket ) ) {
		?><h3><?php _e( 'Migrate Existing Files', 'dreamspeed-cdn' ); ?></h3><?php
	
		if ( count( $this->get_attachment_without_dreamspeed_info() ) != 0 ) {
			?>
			<form method="post">
			<input type="hidden" name="action" value="migrate" />
			<?php wp_nonce_field( 'dreamspeed-save-settings' ) ?>
			
			<table class="form-table">
			<tr valign="top">
				<td>
					<p><?php _e( 'If want to upload existing images, check the following box and they will begin to upload to DreamSpeed. If you have a high number of images, the uploader will run as long as it can, and then <em>schedule</em> a retry in an hour. To see if your images are uploaded to the Cloud, check the <a href="upload.php">Media Library</a>. Any item with a green checkmark under the CDN column is uploaded (and the red X means it\'s not). The uploader will automatically rerun itself on your images, no need to re-run!', 'dreamspeed-cdn' ); ?></p>
			
					<p><input type="checkbox" name="migrate-to-dreamspeed" value="1" id="migrate-to-dreamspeed" />
					<label for="migrate-to-dreamspeed"> <?php printf( __( '%d file(s) can be migrated to DreamSpeed.', 'dreamspeed-cdn' ), count($this->get_attachment_without_dreamspeed_info()) ); ?></label>
					</p>		
				</td>
			</tr>
			<tr valign="top">
				<td>
					<button type="submit" class="button button-secondary"><?php _e( '<div class="dashicons dashicons-upload"></div> Upload Existing Media', 'dreamspeed-cdn' ); ?></button>
					<p>
				</td>
			</tr>
			</table>
			</form>		  
		<?php } else { ?>
			<p><div class="dashicons dashicons-smiley"></div> <?php _e( 'All your media files are uploaded to the cloud! Celebrate!', 'dreamspeed-cdn' ); ?> </p>
		<?php }
	}
?>
</div></div>