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

		<p><?php printf( __( 'DreamSpeed CDN allows WordPress upload all your media to <a href="%s">DreamObjects&#153;</a>, housing your images, MP3s, and more in an inexpensive, scalable object storage service that provides a reliable, flexible cloud solution.', 'dreamobjects'), 'https://www.dreamhost.com/cloud/cdn/' ); ?></p>

		<?php if ( !$this->get_secret_access_key() ) { ?>
    			<p><?php printf( __( 'DreamObjects&#153; comes with a 30-day trial to evaluate the service and the plugin. To sign up, go to your <a href="%s">DreamObjects Panel for DreamObjects</a> and create a user. You will then be able to click on the Keys button to retrieve your DreamObjects access and secret keys and enable CDN.', 'dreamobjects' ), 'https://panel.dreamhost.com/index.cgi?tree=cloud.objects&' ); ?></p>
				<p><?php printf( __( 'Once you have your keys, enter them in the form below and save your changes. Your secret key will not be displayed for security. For additional help, please review the official <a href="%s">What is DreamSpeed? help document</a>.', 'dreamobjects' ), 'https://help.dreamhost.com/hc/en-us/articles/215916677-What-is-DreamSpeed-' ); ?></p>
		<?php } // endif ?>
	
		<h3>Access Keys</h3>
		
		<form method="post">
	
		<?php 
		if ( isset( $_POST['access_key_id'] ) ) {
			?>
			<div id="message" class="is-dismissible updated"><p><?php _e( 'Settings saved.', 'dreamspeed-cdn' ); ?></p></div>
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