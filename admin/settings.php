<?php
/*
    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License v 3 for more details.

    https://www.gnu.org/licenses/gpl-3.0.html

*/

if (!defined('ABSPATH')) {
    die();
}

?>
			<div class="wrap">
				<div id="icon-dreamspeed" class="icon32"></div>
				<h2><?php echo __("DreamSpeed", dreamspeed); ?></h2>

				<p><?php echo __('DreamSpeed&#153; THE INTRO IS HERE.', dreamspeed); ?></p>

 				<form method="post" action="options.php">
				<?php
                    settings_fields( 'dh-ds-settings_fields' );
                    do_settings_sections( 'dh-ds-settings1_sections' );
                    
                    // Validate!
                    if ( DHDS::$options['key'] && DHDS::$options['secretkey'] ) {

                        $dreamobjects = \Aws\S3\S3Client::factory( array(
                                        'key'   => DHDS::$options['key'],
                                        'secret'    => DHDS::$options['secretkey'],
                                        'base_url'  => 'https://objects.dreamhost.com'
                            )
                        );
                        
                        // checking access_key, secret_key and bucket_name
                        try {
                            $result = $dreamobjects->listBuckets();
                            do_settings_sections( 'dh-ds-settings2_sections' );
                        } catch (\Aws\S3\Exception\AccessDeniedException $e) {
                            echo "<p><strong>".__('Access denied. Please check your keys.', dreamspeed)."</strong></p>";
                        }
                    }
                    
                    if ( DHDS::$options['key'] && DHDS::$options['secretkey'] && DHDS::$options['url'] && DHDS::$options['bucket'] ) {
                        do_settings_sections( 'dh-ds-settings3_sections' );
                    }
                    
                    submit_button();
				?>
                </form>
			</div>