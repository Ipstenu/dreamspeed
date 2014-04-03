<?php
/*
    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with WordPress.  If not, see <http://www.gnu.org/licenses/>.

*/

if (!defined('ABSPATH')) {
    die();
}

include_once( DHDS_PLUGIN_DIR. '/aws/aws-autoloader.php');
$dreamspeed_options = get_option( 'dhds_options' );
?>
			<div class="wrap">
				<div id="icon-dreamspeed" class="icon32"></div>
				<h2><?php echo __("DreamSpeed", dreamspeed); ?></h2>

				<p><?php echo __('DreamSpeed&#153; THE INTRO IS HERE.', dreamspeed); ?></p>

 				<form method="post" action="options.php">
				<?php
                    settings_fields( 'dh-ds-keypair-settings' );
                    do_settings_sections( 'dh-ds-keypair_page' );
                    settings_fields( 'dh-ds-domain-settings' );
                    do_settings_sections( 'dh-ds-domain_page' );                    
                    submit_button();
				?>
                </form>

                <h3><?php echo __('Reset Options', dreamspeed); ?></h3>
                <p><?php echo __('Click the button to wipe out all settings. This will reset your keypair, as well as all plugin options and wipe the debug log. It will <em>not</em> remove any backups.', dreamspeed); ?></p>
                	<form method="post" action="options.php">
                <?php settings_fields( 'dh-ds-reset-settings' ); ?>
                <input type="hidden" name="page_options" value="dh-ds-reset" />
                <input type="hidden" name="dhdo-reset" value="Y">
                <?php submit_button('Reset Options', 'primary'); ?>
                </form>
			</div>