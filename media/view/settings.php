<div class="dreamspeed-content dreamspeed-settings">

<?php
$buckets = $this->get_buckets();

if ( is_wp_error( $buckets ) ) :
	?>
	<div class="error">
		<p>
			<?php _e( 'Error retrieving a list of your buckets from DreamObjects:', 'dreamspeed' ); ?>
			<?php echo $buckets->get_error_message(); ?>
		</p>
	</div>
	<?php
endif;

if ( isset( $_GET['updated'] ) ) {
	?>
	<div class="updated">
		<p>
			<?php _e( 'Settings saved.', 'dreamspeed' ); ?>
		</p>
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
		<h3><?php _e( 'Bucket and Path Settings', 'dreamspeed' ); ?></h3>
		
		<p><?php _e( 'Select which bucket to use for uploading your media. You should not change this once set, as it will break any existing CDN uploads.', 'dreamspeed' ); ?></p>

		<p><select name="bucket" class="bucket">
		<option value="">-- <?php _e( 'Select an S3 Bucket', 'dreamspeed' ); ?> --</option>
		<?php if ( is_array( $buckets ) ) foreach ( $buckets as $bucket ): ?>
		    <option value="<?php echo esc_attr( $bucket['Name'] ); ?>" <?php echo $bucket['Name'] == $this->get_setting( 'bucket' ) ? 'selected="selected"' : ''; ?>><?php echo esc_html( $bucket['Name'] ); ?></option>
		<?php endforeach;?>
		<option value="new"><?php _e( 'Create a new bucket...', 'dreamspeed' ); ?></option>
		</select></p>

		<p><input type="checkbox" name="expires" value="1" id="expires" <?php echo $this->get_setting( 'expires' ) ? 'checked="checked" ' : ''; ?> />
		<label for="expires"> <?php printf( __( 'Set a <a href="%s" target="_blank">far future HTTP expiration header</a> for uploaded files <em>(recommended)</em>', 'dreamspeed' ), 'http://developer.yahoo.com/performance/rules.html#expires' ); ?></label></p>
	</td>
</tr>

<tr valign="top">
	<td>
		<p><?php _e( 'Determine the name of your folder structure for your media. At this time, you cannot remove the year or month in order to prevent file-name collisions. If you already have folders (aka objects) in your bucket, please make sure to select a new location.', 'dreamspeed' ); ?></p>
		<p><label><?php _e( 'Object Path:', 'dreamspeed' ); ?></label>
		<input type="text" name="object-prefix" value="<?php echo esc_attr( $this->get_setting( 'object-prefix' ) ); ?>" size="30" />
		<label><?php echo trailingslashit( $this->get_dynamic_prefix() ); ?></label></p>
		<p class="description"><?php _e( 'The default is <code>wp-content/uploads/</code>', 'dreamspeed' ); ?></p>
	</td>
</tr>

<tr valign="top">
	<td>
		<h3><?php _e( 'CDN Path Settings', 'dreamspeed' ); ?></h3>
	
		<label><?php _e( 'Domain Name', 'dreamspeed' ); ?></label><br />
		<input type="text" name="cloudfront" value="<?php echo esc_attr( $this->get_setting( 'cloudfront' ) ); ?>" size="50" />
		<p class="description"><?php _e( 'Leave blank if you aren&#8217;t using a DNS alias.', 'dreamspeed' ); ?></p>

	</td>
</tr>

<tr valign="top">
	<td>
		<h3><?php _e( 'Plugin Options', 'dreamspeed' ); ?></h3>

		<input type="checkbox" name="copy-to-s3" value="1" id="copy-to-s3" <?php echo $this->get_setting( 'copy-to-s3' ) ? 'checked="checked" ' : ''; ?> />
		<label for="copy-to-s3"> <?php _e( 'Copy files to DreamSpeed as they are uploaded to the Media Library', 'dreamspeed' ); ?></label>
		<br />

		<input type="checkbox" name="serve-from-s3" value="1" id="serve-from-s3" <?php echo $this->get_setting( 'serve-from-s3' ) ? 'checked="checked" ' : ''; ?> />
		<label for="serve-from-s3"> <?php _e( 'Point file URLs to DreamSpeed/DNS Alias for files that have been copied to S3 <em>(recommended)</em>', 'dreamspeed' ); ?></label>
		<br />

		<input type="checkbox" name="force-ssl" value="1" id="force-ssl" <?php echo $this->get_setting( 'force-ssl' ) ? 'checked="checked" ' : ''; ?> />
		<label for="force-ssl"> <?php _e( 'Always serve files over https (SSL)', 'dreamspeed' ); ?></label>
		<br />

		<input type="checkbox" name="hidpi-images" value="1" id="hidpi-images" <?php echo $this->get_setting( 'hidpi-images' ) ? 'checked="checked" ' : ''; ?> />
		<label for="hidpi-images"> <?php _e( 'Copy any HiDPI (@2x) images to CDN (works with WP Retina 2x plugin)', 'dreamspeed' ); ?></label>

	</td>
</tr>
<tr valign="top">
	<td>
		<button type="submit" class="button button-primary"><?php _e( 'Save Changes', 'dreamspeed' ); ?></button>
	</td>
</tr>
</table>

</form>

</div>