<?php

	if ( ! defined('WP_UNINSTALL_PLUGIN') ) {
			exit;
	}

	delete_option( 'apod_initial_boot' );
	delete_option( 'apod_settings' );
	delete_option( 'apod_last_status' );

	delete_transient( 'apod_posts' );

	$all_apod_posts = get_posts( array(
		'posts_per_page' => -1,
		'post_type' 		 => 'post-nasa-gallery',
		'post_status' 	 => 'any, trash, auto-draft',
		)
	);

	foreach ( $all_apod_posts as $post ) :
			wp_delete_post( $post->ID, true );
	endforeach;

	wp_cache_flush();