<div class="dreamspeed-content dreamspeed-settings">

	<h3>Access Keys</h3>
	
	<?php 
	if ( !$this->get_secret_access_key() ) { ?>
    	<p><?php printf( __( 'If you don&#8217;t have an DreamSpeed CDN and DreamObjects account yet, you need to <a href="%s">sign up</a>.', 'dreamspeed' ), 'http://www.dreamhost.com/cloud/dreamspeedcdn/' ); ?></p>
	
		<p><?php printf( __( 'If you do have DreamSpeed, you can find your keys in your <a href="%s">panel</a>:', 'dreamspeed' ), 'https://panel.dreamhost.com/index.cgi?tree=cloud.objects&' ); ?></p>
		
		<p><img src="<?php echo plugins_url( 'tools/images/keys.png', $this->plugin_file_path ); ?>" width="90%" /></p>
		
    <?php } // endif 
    else {
	    
    }  
    ?>
	
	<form method="post">

	<?php 
	if ( isset( $_POST['access_key_id'] ) ) {
		?>
		<div class="updated">
			<p>
				<?php _e( 'Settings saved.', 'dreamspeed' ); ?>
			</p>
		</div>
		<?php
	}
	?>



	<input type="hidden" name="action" value="save" />
	<?php wp_nonce_field( 'dreamspeed-save-settings' ) ?>

	<table class="form-table">
	<tr valign="top">
		<th width="33%" scope="row"><?php _e( 'Key:', 'dreamspeed' ); ?></th>
		<td><input type="text" name="access_key_id" value="<?php echo esc_attr( $this->get_access_key_id() ); ?>" size="50" autocomplete="off" /></td>
	</tr>
	<tr valign="top">
		<th width="33%" scope="row"><?php _e( 'Secret Key:', 'dreamspeed' ); ?></th>
		<td><input type="text" name="secret_access_key" value="<?php echo $this->get_secret_access_key() ? '-- not shown --' : ''; ?>" size="50" autocomplete="off" />
		
		<p class="description"><?php _e( 'Your secret key will not display for your own security.', 'dreamspeed' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
		<td colspan="2">
			<button type="submit" class="button button-primary"><?php _e( 'Save Changes', 'dreamspeed' ); ?></button>
			<?php if ( $this->get_secret_access_key() ) : ?>
			&nbsp;<button class="button remove-keys"><?php _e( 'Remove Keys', 'dreamspeed' ); ?></button>
			<?php endif; ?>
		</td>
	</tr>
	</table>

	</form>

</div>