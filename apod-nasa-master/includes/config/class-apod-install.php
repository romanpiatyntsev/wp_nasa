<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

class APOD_Install
{
	public static function activate() {
		APOD_Post_Type::register_post_types();
		flush_rewrite_rules();
		self::set_download_interval();
	}

	public static function deactivate() {
		self::delete_download_interval();
	}

	public static function set_download_interval() {
		if ( ! wp_next_scheduled( 'nasa_gallery_post', array() ) ) {
			wp_schedule_event( time(), 'daily', 'nasa_gallery_post', array() );
		}
	}

	public static function delete_download_interval() {
		wp_clear_scheduled_hook( 'nasa_gallery_post', array() );
	}
}