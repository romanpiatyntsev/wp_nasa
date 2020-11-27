<?php

/*
 * Plugin Name:       Astronomy Picture of the Day
 * Description:       Would you like to get amazing pictures from "Astronomy Picture of the Day" by NASA? When activated you will get an everyday new image of the day from NASA, store as post and show foto's collection using shortcode on the site's pages.
 * Version:           1.0.0
 * Author:            Roman Piatyntsev
 * Author URI:        https://nasa.sitex.pp.ua
 * License: 					GPLv3 or later
 * License URI: 			https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       apod_locale
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class APOD_Nasa {

	private static $_instance;

	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();
		( new APOD_Manager() )->run();
	}

	private function define_constants() {
		$this->define( 'APOD_DIR', plugin_dir_path( __FILE__ ) );
		$this->define( 'APOD_URL', plugin_dir_url( __FILE__ ) ) ;
		$this->define( 'APOD_VERSION', $this->get_plugin_meta( 'Version' ) ) ;
	}

	private function includes() {
		require_once APOD_DIR . 'includes/config/class-apod-auto-loader.php';
	}

	private function init_hooks() {
		register_activation_hook( __FILE__, array( 'APOD_Install', 'activate' ) );
		register_deactivation_hook( __FILE__, array( 'APOD_Install', 'deactivate' ) );
	}

	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
				define( $name, $value );
		}
	}

	public function get_plugin_meta( $key ) {
		$plugin_meta = get_file_data( __FILE__, array( $key => $key ), false );
		return $plugin_meta == null ? '' : $plugin_meta[ $key ];
	}
}

APOD_Nasa::get_instance();