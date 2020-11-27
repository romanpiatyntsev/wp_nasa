<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class APOD_Post_Creator
	{
		/**
		 * @var APOD_Api
		 */
		private $api_provider;

		public function __construct( $api_provider ) {
			$this->api_provider = $api_provider;
		}

		public function add_post( $post_date = null, $user_id = 1, $post_status = 'publish' ) {
			if ( ! function_exists( 'media_sideload_image' ) ) {
				require_once( ABSPATH . 'wp-admin/includes/media.php' );
				require_once( ABSPATH . 'wp-admin/includes/file.php' );
				require_once( ABSPATH . 'wp-admin/includes/image.php' );
			}

			try {
				$image = $this->api_provider->get_image_of_the_day( $post_date );

				$new_post = array(
					'post_title' => $image->date,
					'post_content' => $image->explanation,
					'post_status' => $post_status,
					'post_author' => $user_id,
					'post_type' => 'post-nasa-gallery',
					'post_date' => $post_date,
				);

				$post_id = wp_insert_post( $new_post );

				if ( is_wp_error( $post_id ) ) {
					throw new ErrorException ( $post_id->get_error_message() );
				}

				$image_id = media_sideload_image( $image->url, $post_id, $desc = $image->title, $return = 'id' );

				if ( is_wp_error( $image_id ) ) {
					throw new ErrorException ( $post_id->get_error_message() );
				}

				set_post_thumbnail( $post_id, $image_id );
				do_action('apod_post_inserted', $post_id, $image->date, $image->url );

			} catch ( ErrorException $e) {
				do_action('apod_post_insert_error', $e );
			}
		}
	}