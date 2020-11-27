<div class="wrap apod-api-settings">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

	<?php settings_errors(); ?>

	<h2 class="title"><?php _e( 'Where to get API Key', 'apod_locale' ) ?></h2>

  <ol>
    <li><a href="https://api.nasa.gov" target="_blank"><?php _e( 'Generate API Key', 'apod_locale' ); ?></a></li>
    <li><?php _e( 'Paste the Key into the field below', 'apod_locale' ); ?></li>
  </ol>

	<form action="options.php" method="POST">
		<?php
			settings_fields( $this->menu_slug );
			do_settings_sections( $this->menu_slug );
			submit_button();
		?>
	</form>

	<h2 class="title"><?php _e( 'Last activities', 'apod_locale' ) ?></h2>
  <p><?php printf( '%s - %s', __( 'Status', 'apod_locale' ), $log['status'] ); ?></p>

  <?php if ( $log['status'] == 'OK' ) : ?>
    <p><?php _e( 'Downloaded image', 'apod_locale' ) ?> <a href="<?php echo $log['img_url']; ?>" target="_blank">view</a></p>
    <p><?php printf( '%s - %s', __( 'Date', 'apod_locale' ), $log['date'] ) ?></p>
  <?php endif; ?>

</div> <!-- //wrap-->
