<?php

/*
    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License v 3 for more details.

    https://www.gnu.org/licenses/gpl-3.0.html

*/

use Aws\Common\Aws as AwsCDN;

class DreamSpeed_DHO_Services extends DreamSpeed_Plugin_Base {

	private $plugin_title, $plugin_menu_title, $client;

	const SETTINGS_KEY = 'dreamspeed_settings';

	function __construct( $plugin_file_path ) {
		parent::__construct( $plugin_file_path );

		do_action( 'dreamspeed_init', $this );

		if ( is_admin() ) {
			do_action( 'dreamspeed_admin_init', $this );
		}

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		$this->plugin_permission = 'manage_options';
		$this->plugin_vars = array( 'access_key_id', 'secret_access_key' );
		$this->plugin_title = __( 'DreamSpeed CDN', 'dreamspeed-cdn' );
		$this->plugin_menu_title = __( 'DreamSpeed', 'dreamspeed-cdn' );
	}

	function admin_menu() {
		$hook_suffixes[] = add_menu_page( $this->plugin_title, $this->plugin_menu_title, $this->plugin_permission, $this->plugin_slug, array( $this, 'render_page' ), 'dashicons-cloud' );
    	
	    	global $submenu;
	    	if ( isset( $submenu[$this->plugin_slug][0][0] ) ) {
	    		$submenu[$this->plugin_slug][0][0] = __( 'Settings', 'dreamspeed-cdn' );
		}

		$title = __( 'Settings', 'dreamspeed-cdn' );
		$hook_suffixes[] = $this->add_page( $title, $title, $this->plugin_permission, $this->plugin_slug, array( $this, 'render_page' ) );

		do_action( 'aws_admin_menu', $this );

		foreach ( $hook_suffixes as $hook_suffix ) {
			add_action( 'load-' . $hook_suffix , array( $this, 'plugin_load' ) );
		}
	}

	function add_page( $page_title, $menu_title, $capability, $menu_slug, $function = '' ) {
		return add_submenu_page( $this->plugin_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
	}

	function plugin_load() {
		$src = plugins_url( 'tools/js/script.js', $this->plugin_file_path );
		wp_enqueue_script( 'dreamspeed-script', $src, array( 'jquery' ), $this->get_installed_version(), true );

		$this->handle_post_request();

		do_action( 'dreamspeed_plugin_load', $this );
	}

	function handle_post_request() {
		if ( empty( $_POST['action'] ) || 'save' != $_POST['action'] ) {
			return;
		}

		if ( !wp_verify_nonce( $_POST['_wpnonce'], 'dreamspeed-save-settings' ) ) {
			die( );
		}

		// Make sure $this->settings has been loaded
		$this->get_settings();

		$post_vars = $this->plugin_vars;
		foreach ( $post_vars as $var ) {
			if ( !isset( $_POST[$var] ) ) {
				continue;
			}

			if ( 'secret_access_key' == $var && '-- not shown --' == $_POST[$var] ) {
				continue;
			}

			$this->set_setting( $var, sanitize_text_field($_POST[$var]) );
		}

		$this->save_settings();
	}

	function render_page() {
		if ( empty( $_GET['page'] ) ) {
			// Not sure why we'd ever end up here, but just in case
			wp_die( 'Error, page cannot render.' );
		}

		$view = 'settings';
		$this->render_view( $view );
	}

	function get_access_key_id() {
		return $this->get_setting( 'access_key_id' );
	}

	function get_secret_access_key() {
		return $this->get_setting( 'secret_access_key' );
	}

	function get_my_region() {
		$options = get_option('dreamspeed_cdn');
		return $options['region'];
	}

	function get_client() {
		if ( !$this->get_access_key_id() || !$this->get_secret_access_key() ) {
			return new WP_Error( 'access_keys_missing', sprintf( __( '<div class="dashicons dashicons-no"></div> Please <a href="%s">set your access keys</a> first.', 'dreamspeed-cdn' ), 'admin.php?page=' . $this->plugin_slug ) );
		}
		
		if ( is_null( $this->client ) ) {
			$args = array(
			    'key'      => $this->get_access_key_id(),
			    'secret'   => $this->get_secret_access_key(),
			    'base_url' => 'http://'.$this->get_my_region().'/',
			);
			$args = apply_filters( 'aws_get_client_args', $args );
			$this->client = AwsCDN::factory( $args );
		}

		return $this->client;
	}

}