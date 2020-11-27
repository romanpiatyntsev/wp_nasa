<?php
	/**
	 * Shortcode [nasa-gallery-slider] template
	 */
?>

<div class="nasa-gallery-slider">
	<?php foreach ( $apod_posts as $post ) : ?>
	<div class="slider-items">
		<div class="apdo-image">
      <img data-lazy="<?php echo get_the_post_thumbnail_url( $post->ID, 'nasa_gallery_thumb' ); ?>" alt="<?php echo $post->post_title; ?>">
    </div>
    <div class="apdo-title">
      <?php echo $post->post_title; ?>
    </div>
	</div>
	<?php endforeach; ?>
</div>