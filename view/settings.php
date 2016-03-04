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

	<h2><?php echo ( isset( $page_title ) ) ? $page_title : __( 'DreamSpeed CDN', 'dreamspeed-cdn' ); ?></h2>

	<div class="dreamspeed-content dreamspeed-settings">
	
		<p><?php printf( __( '<a href="%s">DreamObjects&#153;</a> is an inexpensive, scalable object storage service that was developed from the ground up to provide a reliable, flexible cloud storage solution for entrepreneurs and developers. It provides a perfect, scalable storage solution for your WordPress site.', dreamobjects ), 'https://www.dreamhost.com/cloud/storage/' ); ?></p>
		
		<p><?php printf( __( '<a href="%s">DreamSpeed CDN</a> is a content delivery network fully integrated with DreamObjects.', dreamobjects ), 'https://www.dreamhost.com/cloud/cdn/' ); ?></p>
	
		<h3>Access Keys</h3>
		
		<?php 
		if ( !$this->get_secret_access_key() ) { ?>
	    		<p><?php printf( __( 'If you don\'t have a DreamSpeed CDN and DreamObjects account yet, you need to <a href="%s">sign up for an account</a>.', 'dreamspeed-cdn' ), 'https://www.dreamhost.com/cloud/cdn/' ); ?></p>
		
			<p><?php printf( __( 'If you do have DreamObjects, you can find your keys in your <a href="%s">panel</a>.', 'dreamspeed-cdn' ), 'https://panel.dreamhost.com/index.cgi?tree=cloud.objects&' ); ?></p>
			
	    <?php } // endif 
	    else {
		    
	    }  
	    ?>
		
		<form method="post">
	
		<?php 
		if ( isset( $_POST['access_key_id'] ) ) {
			?>
			<div class="updated"><p><?php _e( 'Settings saved.', 'dreamspeed-cdn' ); ?></p></div>
			<?php
		}
		?>
	
		<input type="hidden" name="action" value="save" />
		<?php wp_nonce_field( 'dreamspeed-save-settings' ) ?>
	
		<table class="form-table">
		<tr valign="top">
			<th width="33%" scope="row"><?php _e( 'Key', 'dreamspeed-cdn' ); ?></th>
			<td><input type="text" name="access_key_id" value="<?php echo esc_attr( $this->get_access_key_id() ); ?>" size="50" autocomplete="off" /></td>
		</tr>
		<tr valign="top">
			<th width="33%" scope="row"><?php _e( 'Secret Key', 'dreamspeed-cdn' ); ?></th>
			<td><input type="text" name="secret_access_key" value="<?php echo $this->get_secret_access_key() ? '-- not shown --' : ''; ?>" size="50" autocomplete="off" />
			
			<p class="description"><div class="dashicons dashicons-shield"></div>  <?php _e( 'Once saved, your secret will not display again for your own security.', 'dreamspeed-cdn' ); ?></p>
			</td>
		</tr>
		<tr valign="top">
			<td colspan="2">
				<button type="submit" class="button button-primary"><?php _e( 'Save Changes', 'dreamspeed-cdn' ); ?></button>
				<?php if ( $this->get_secret_access_key() ) : ?>
				&nbsp;<button class="button remove-keys"><?php _e( 'Remove Keys', 'dreamspeed-cdn' ); ?></button>
				<?php endif; ?>
			</td>
		</tr>
		</table>
	
		</form>
	
	</div>
</div></div>