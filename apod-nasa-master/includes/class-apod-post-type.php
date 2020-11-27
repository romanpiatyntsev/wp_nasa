<?php

	if ( ! defined( 'ABSPATH' ) ) {
			exit;
	}

	class APOD_Post_Type
	{

		/**
		 * Register core post types.
		 */
		public static function register_post_types() {
			register_post_type( 'post-nasa-gallery',
				array(
					'labels'    =>  array(
						'name'  => __('NASA Images', 'apod_locale'),
					),
					'public'            =>  true,
					'supports'          =>  array( 'title', 'editor', 'thumbnail' ),
					'has_archive'       =>	true,
					'menu_icon'   			=>	'dashicons-images-alt2',
					'show_in_nav_menus' =>  true,
					'rewrite'						=>	array( 'slug' => 'nasa-gallery'),
				)
			);
		}
	}