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
                    settings_fields( 'dh-ds-settings_fields' );
                    do_settings_sections( 'dh-ds-settings_sections' );                 
                    submit_button();
				?>
                </form>
			</div>