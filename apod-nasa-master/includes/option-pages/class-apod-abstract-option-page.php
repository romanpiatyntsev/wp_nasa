<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	abstract class APOD_Abstract_Option_Page
	{
		/**
		 * Option name for save plugin's settings
		 */
		protected $option_name;

		protected $menu_slug;

		public function __construct( $option_name ) {
			$this->option_name = $option_name;
		}

		public function add_option_page( $args ) {

			$this->menu_slug = $args['menu_slug'];

			add_submenu_page(
				$args['parent_slug'],
				$args['page_title'],
				$args['menu_title'],
				'manage_options',
				$args['menu_slug'],
				array( $this, 'render_page' )
			);

			/**
			 * Register Wordpress API-Settings for option pages
			 */
			register_setting( $this->menu_slug, $this->option_name );

			$this->add_settings_sections();
		}

		abstract protected function add_settings_sections();

		abstract public function render_page();

		public function get_page_slug() {
			return $this->menu_slug;
		}

		public function get_option_name() {
			return $this->option_name;
		}
	}