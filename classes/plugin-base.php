<?php

/*
    This file is part of DreamSpeed CDN, a plugin for WordPress.

    DreamSpeed CDN is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License v 3 for more details.

    https://www.gnu.org/licenses/gpl-3.0.html

*/

class DreamSpeed_Plugin_Base {

	protected $plugin_file_path, $plugin_dir_path, $plugin_slug, $plugin_basename;
	private $settings;

	function __construct( $plugin_file_path ) {
		$this->plugin_file_path = $plugin_file_path;
		$this->plugin_dir_path = rtrim( plugin_dir_path( $plugin_file_path ), '/' );
		$this->plugin_slug = basename( $this->plugin_dir_path );
		$this->plugin_basename = plugin_basename( $plugin_file_path );
	}

	function get_settings( $force = false) {
		
		if ( is_null( $this->settings ) || $force ) {
			$this->settings = get_option( static::SETTINGS_KEY );
		}
		return $this->settings;
	}

	function get_setting( $key ) {
		$this->get_settings( );

		if ( isset( $this->settings[$key] ) ) {
			return $this->settings[$key];
		}

		return '';
	}

	function render_view( $view, $args = array() ) {
		extract( $args );
		include $this->plugin_dir_path . '/view/' . $view . '.php';
	}
	
	function set_setting( $key, $value ) {
		$this->settings[$key] = $value;
	}

	function set_settings( $settings ) {
		$this->settings = $settings;
	}

	function save_settings() {
		update_option( static::SETTINGS_KEY, $this->settings );
	}

	function get_installed_version() {
		if ( !is_admin() ) return false; // get_themes & get_plugins throw an error on the frontend

		$plugins = get_plugins();

		if ( !isset( $plugins[$this->plugin_basename]['Version'] ) ) {
			return false;
		}

		return $plugins[$this->plugin_basename]['Version'];
	}
}