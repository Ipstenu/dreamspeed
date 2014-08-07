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

<div class="dreamspeed-content dreamspeed-settings">

<?php

$buckets = $this->get_buckets();

if ( is_wp_error( $buckets ) ) {
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
	?>
	<div class="updated">
			<p><div class="dashicons dashicons-yes"></div> <?php _e( 'Settings saved.', 'dreamspeed-cdn' ); ?></p>
	</div>
	<?php
} 

if ( isset( $_GET['migrated'] ) ) {
	?>
	<div class="updated">
			<p><div class="dashicons dashicons-info"></div> <?php _e( 'Existing files migrating.', 'dreamspeed-cdn' ); ?></p>
	</div>
	<?php
}

if (isset( $_GET['error'] ) ) {
	?>
	<div class="error">
			<p><div class="dashicons dashicons-no"></div> <?php _e( 'Warning. You cannot migrate existing files without that checkbox.', 'dreamspeed-cdn' ); ?></p>
	</div>
	<?php
}

?>

<form method="post">
<input type="hidden" name="action" value="save" />
<?php wp_nonce_field( 'dreamspeed-save-settings' ) ?>

<table class="form-table">
<tr valign="top">
	<td>
		<h3><?php _e( 'Bucket and Path Settings', 'dreamspeed-cdn' ); ?></h3>
		
		<p><?php _e( 'Select which bucket to use for uploading your media. You should not change this once set, as it will break any existing CDN uploads.', 'dreamspeed-cdn' ); ?></p>

		<p><select name="bucket" class="bucket">
		<option value="">-- <?php _e( 'Select a Bucket', 'dreamspeed-cdn' ); ?> --</option>
		<?php if ( is_array( $buckets ) ) foreach ( $buckets as $bucket ): ?>
		    <option value="<?php echo esc_attr( $bucket['Name'] ); ?>" <?php echo $bucket['Name'] == $this->get_setting( 'bucket' ) ? 'selected="selected"' : ''; ?>><?php echo esc_html( $bucket['Name'] ); ?></option>
		<?php endforeach;?>
		<option value="new"><?php _e( 'Create a new bucket...', 'dreamspeed-cdn' ); ?></option>
		</select></p>

		<p><input type="checkbox" name="expires" value="1" id="expires" <?php echo $this->get_setting( 'expires' ) ? 'checked="checked" ' : ''; ?> />
		<label for="expires"> <?php printf( __( 'Set a <a href="%s" target="_blank">far future HTTP expiration header</a> for uploaded files <em>(recommended)</em>', 'dreamspeed-cdn' ), 'http://developer.yahoo.com/performance/rules.html#expires' ); ?></label></p>
	</td>
</tr>

<tr valign="top">
	<td>
		<p><?php _e( 'Determine the name of your folder structure for your media. At this time, you cannot remove the year or month in order to prevent file-name collisions. If you already have folders (aka objects) in your bucket, please make sure to select a new location.', 'dreamspeed-cdn' ); ?></p>
		<p><label><?php _e( 'Object Path:', 'dreamspeed-cdn' ); ?></label>
		<input type="text" name="object-prefix" value="<?php echo esc_attr( $this->get_setting( 'object-prefix' ) ); ?>" size="30" />
		<label><?php echo trailingslashit( $this->get_dynamic_prefix() ); ?></label></p>
		<p class="description"><?php _e( 'The default is <code>wp-content/uploads/</code> (or <code>wp-content/uploads/site/#/</code> for Multisite).', 'dreamspeed-cdn' ); ?></p>
	</td>
</tr>

<tr valign="top">
	<td>
		<h3><?php _e( 'CDN Path Settings', 'dreamspeed-cdn' ); ?></h3>
		
		<p><?php _e( 'If you use an alias for your CDN (like http://cdn.example.com) then you can tell DreamSpeed to use that instead of the default http://objects.dreamhost.com/bucketname. Both URLs will always work, but pretty CDN is pretty.', 'dreamspeed-cdn' ); ?></p>
	
		<label><?php _e( 'Domain Name:', 'dreamspeed-cdn' ); ?></label> <br />
			<?php 
				if (is_ssl()) {
						?> https:// <?php
				} else {
						?> http:// <?php
				} 
			?>
		<input type="text" name="cloudfront" value="<?php echo esc_attr( $this->get_setting( 'cloudfront' ) ); ?>" size="50" />
		<p class="description"><?php _e( 'Leave blank if you aren&#8217;t using a DNS alias.', 'dreamspeed-cdn' ); ?></p>

	</td>
</tr>

<tr valign="top">
	<td>
		<h3><?php _e( 'Plugin Options', 'dreamspeed-cdn' ); ?></h3>

		<input type="checkbox" name="copy-to-s3" value="1" id="copy-to-s3" <?php echo $this->get_setting( 'copy-to-s3' ) ? 'checked="checked" ' : ''; ?> />
		<label for="copy-to-s3"> <?php _e( 'Copy files to DreamSpeed as they are uploaded to the Media Library <em>(recommended)</em>', 'dreamspeed-cdn' ); ?></label>
		<br />

		<input type="checkbox" name="serve-from-s3" value="1" id="serve-from-s3" <?php echo $this->get_setting( 'serve-from-s3' ) ? 'checked="checked" ' : ''; ?> />
		<label for="serve-from-s3"> <?php _e( 'Point file URLs to DreamSpeed/DNS Alias for files that have been copied to S3 <em>(recommended)</em>', 'dreamspeed-cdn' ); ?></label>
		<br />
		<input type="checkbox" name="force-ssl" value="1" id="force-ssl" <?php echo $this->get_setting( 'force-ssl' ) ? 'checked="checked" ' : ''; ?> />
		<label for="force-ssl"> <?php _e( 'Always serve files over https (SSL)', 'dreamspeed-cdn' ); ?></label>
		<br />
<!--
		<input type="checkbox" name="hidpi-images" value="1" id="hidpi-images" <?php echo $this->get_setting( 'hidpi-images' ) ? 'checked="checked" ' : ''; ?> />
		<label for="hidpi-images"> <?php _e( 'Copy any HiDPI (@2x) images to CDN (works with WP Retina 2x plugin)', 'dreamspeed-cdn' ); ?></label>
-->
	</td>
</tr>
<tr valign="top">
	<td>
		<button type="submit" class="button button-primary"><?php _e( 'Save Changes', 'dreamspeed-cdn' ); ?></button>
	</td>
</tr>
</table>

</form>

<?php 
	$all_attachments = wp_count_attachments();

	if ( $this->get_setting( 'copy-to-s3' ) == 1 ) {
	?><h3><?php _e( 'Migrate Exisiting Files', 'dreamspeed-cdn' ); ?></h3><?php

	if ( count($this->get_attachment_without_dreamspeed_info()) != 0 ) {
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
			</td>
		</tr>
		</table>
		</form>		  
	<?php } else { ?>
		<p><div class="dashicons dashicons-smiley"></div> <?php _e( 'All your media files are uploaded to the cloud! Celebrate!', 'dreamspeed-cdn' ); ?> </p>
	<?php }
}