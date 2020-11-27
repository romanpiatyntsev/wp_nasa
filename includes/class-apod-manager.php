<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class APOD_Manager
	{
		/**
		 * APDO posts creator
		 * @var APOD_Post_Creator
		 */
		private $post_creator;

		/**
		 * NASA API access object
		 * @var APOD_Api
		 */
		private $api_provider;

		/**
		 * Set API option page object
		 * @var APOD_Api_Option_Page
 		 */
		private $api_option_page;

		/**
		 * Option name for save plugin's settings
		 * @var string
		 */
		private $option_name;

		/**
		 * @var boolean
		 */
		private $initial_boot_proccess;

		public function __construct() {
			$this->option_name 		 = 'apod_settings';
			$this->initial_boot_proccess = false;
			$this->api_provider 	 = new APOD_Api( $this->get_api_key() );
			$this->api_option_page = new APOD_Api_Option_Page( $this->option_name );
			$this->post_creator 	 = new APOD_Post_Creator( $this->api_provider );
		}

		public function run() {
			$this->add_image_size();
			$this->add_hook();
			$this->add_shortcodes();
		}

		private function add_shortcodes() {
			APOD_Shortcode::init();
		}

		private function add_image_size() {
			add_image_size( 'nasa_gallery_thumb', 300, 500, true );
		}

		private function add_hook() {
			add_action( 'init', array( $this, 'init' ) );

			add_action( 'admin_menu', array( $this, 'admin_menu' ) );

			add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );

			/**
			 * Cron's hook
			 */
			add_action( 'nasa_gallery_post', array( $this, 'add_nasa_gallery_post' ) );

			add_action( 'plugins_loaded', array( $this, 'load_text_domain' )  );

			/**
			 * Insert new post from NASA
			 */
			add_action( 'apod_post_inserted', array( $this, 'apod_post_inserted' ), 10, 3 );

			/**
			 * Error insert post from NASA
			 */
			add_action( 'apod_post_insert_error', array( $this, 'apod_post_insert_error' ), 10, 1 );

			/**
			 * Show nasa_gallery_post feature images in admin panel
 			 */
			add_filter('manage_post-nasa-gallery_posts_columns', array( $this, 'apod_columns_head' ) );
			add_action('manage_post-nasa-gallery_posts_custom_column', array( $this, 'apod_columns_content' ), 10, 2 );
		}

		public function init() {
			APOD_Post_Type::register_post_types();
			APOD_Shortcode::register_script();
		}

		public function admin_menu() {
			/**
			 * Creating api-settings subpage in main APOD plugin's menu
			 */
			$api_page_args = array(
				'parent_slug' => 'edit.php?post_type=post-nasa-gallery',
				'page_title' 	=> __( 'APOD: Astronomy Picture of the Day settings', 'apod_locale' ),
				'menu_title' 	=> __( 'API settings', 'apod_locale' ),
				'menu_slug' 	=> 'apod_api_option_page',
			);
			$this->api_option_page->add_option_page( $api_page_args );
		}

		/**
		 * Download a new pictures from NASA APOD
		 */
		public function add_nasa_gallery_post() {
			$this->post_creator->add_post();
		}

		public function load_text_domain() {
			load_plugin_textdomain( 'apod_locale', false,
				dirname( plugin_basename( __FILE__ ), 2 )  . '/languages' );
		}

		public function load_scripts() {
			wp_enqueue_style( 'apod', '//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css', array(), APOD_VERSION );
		}

		public function apod_post_inserted( $post_id, $img_date, $img_url ) {
			$this->delete_cache();
			$this->set_last_status('OK', $img_url, $img_date );

			if ( get_option( 'apod_initial_boot' ) != true && $this->initial_boot_proccess != true ) {
				$this->initial_boot( $img_date );
			}
		}

		public function apod_post_insert_error( $error_message ) {
			$this->set_last_status( $error_message );
		}

		private function delete_cache() {
			delete_transient( 'apod_posts' );
		}

		public function apod_columns_head( $defaults ) {
			$defaults['featured_image'] = 'Featured Image';
			return $defaults;
		}

		public function apod_columns_content( $column_name, $post_ID ) {
			if ( $column_name == 'featured_image' ) {
				$post_featured_image = get_the_post_thumbnail_url( $post_ID, 'thumbnail' );
				if ( $post_featured_image ) {
					echo '<img src="' . $post_featured_image . '" height=50 width=50 alt="" >';
				}
			}
		}

		private function initial_boot( $img_date ) {
			$this->initial_boot_proccess = true;
			$date = new DateTime( $img_date );

			for ( $i=0; $i<4; $i++ ) { // add 4 older images
				$date->modify("-1 day");
				$new_date = $date->format("Y-m-d");
				$this->post_creator->add_post( $new_date );
			}
			$this->initial_boot_proccess = false;
			update_option( 'apod_initial_boot', true );
		}

		private function get_api_key() {
			$options = get_option( $this->option_name );
			$api_key = null;
			if ( is_array( $options )  && array_key_exists( 'api-key', $options ) ) {
				$api_key = $options['api-key'];
			}
			return $api_key;
		}

		private function set_last_status( $status, $img_url = null, $date = null ) {
			$value = array(
				'status' 	=> $status,
				'date'	 	=> $date,
				'img_url' => $img_url,
			);
			update_option('apod_last_status', $value );
		}
	}