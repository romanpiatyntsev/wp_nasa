<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class APOD_Shortcode
	{
		static $add_script;

		static function init() {
			add_shortcode( 'nasa-gallery-slider', array( __CLASS__, 'handle_shortcode' ) );
			add_action( 'wp_footer', array( __CLASS__, 'print_script' ) );
		}

		static function handle_shortcode() {
			self::$add_script = true;

			$apod_posts = get_transient( 'apod_posts');
			if ( false === $apod_posts ) {
				$apod_posts = get_posts( array(
					'numberposts' => 5,
					'orderby'     => 'date',
					'order'       => 'DESC',
					'post_status' => 'publish',
					'post_type'   => 'post-nasa-gallery',
				) );
				set_transient( 'apod_posts', $apod_posts,  HOUR_IN_SECONDS*24 ); // one day
			}

			ob_start();
			require APOD_DIR . 'templates/shortcodes/apod-shortcode-slider-template.php';
			return ob_get_clean();
		}

		/* fire on 'admin-init' hook. See APOD_Manager class */
		static function register_script() {
			wp_register_script('slick-slider', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array( 'jquery' ), APOD_VERSION, true);
			wp_register_script('apod-short-code', APOD_URL . 'assets/js/apod-script.js', array( 'jquery', 'slick-slider' ), APOD_VERSION, true);
		}

		static function print_script() {
			if ( ! self::$add_script )
				return;
			wp_print_scripts( 'slick-slider' );
			wp_print_scripts( 'apod-short-code' );
		}
	}