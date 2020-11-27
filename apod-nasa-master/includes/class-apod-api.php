<?php

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class APOD_Api
	{
		private $api_apod_base;
		private $api_key;

		public function __construct( $api_key ) {
			$this->api_apod_base = 'https://api.nasa.gov/planetary/apod';
			if ( $api_key ) {
				$this->api_key = $api_key;
			} else {
				$this->api_key = 'DEMO_KEY';
			}
		}

		/**
		 * Return the image from NASA APOD API
		 * @param string date 'Y-m-d' of post image
		 * @return Object
		 * @throws ErrorException
		 */
		public function get_image_of_the_day( $date = null ) {
			$query_params = $this->get_query_params( $date );
			$response = wp_remote_get( $this->api_apod_base . '?' . $query_params );
			$body = json_decode( wp_remote_retrieve_body( $response ) );

			if (  200 != wp_remote_retrieve_response_code( $response ) ) {
				throw new ErrorException( __( 'Error receiving data from APOD API', 'apod_locale' ) );
			}
			return $body;
		}

		private function get_query_params( $date ) {
			$args = array_filter( array(
				'api_key' => $this->api_key,
				'date' 		=> $date,
			) );
			$query_params = http_build_query( $args );
			return $query_params;
		}
	}