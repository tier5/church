<?php

/**
 * Displays social sharing buttons
 *
 * @package wpv
 */

if ( function_exists( 'sharing_display' ) || class_exists( 'Jetpack_Likes' ) ) {
	if ( function_exists( 'sharing_display' ) ) {
		sharing_display( '', true );
	}

	if ( class_exists( 'Jetpack_Likes' ) ) {
		$custom_likes = new Jetpack_Likes;
		echo $custom_likes->post_likes( '' );
	}

	return;
}

if ( function_exists( 'Easy_Social_Share_Buttons' ) ) {
	if ( ! wpv_get_option( "share-$context-ess" ) ) {
		return;
	}

	$output = do_shortcode( '[ess_post]' );

	if ( ! empty( $output ) ):
	?>
		<div class="clearfix <?php echo apply_filters('wpv_share_class', 'share-btns')?>">
			<div class="sep-3"></div>
			<?php echo $output ?>
		</div>
	<?php
	endif;

	return;
}

global $post;

$networks = array(
	'facebook' => array(
		'link' => 'https://www.facebook.com/sharer/sharer.php?u=',
		'title' => __('Share on Facebook', 'church-event'),
		'text' => __('Like', 'church-event'),
	),
	'twitter' => array(
		'link' => 'https://twitter.com/intent/tweet?text=',
		'title' => __('Share on Twitter', 'church-event'),
		'text' => __('Tweet', 'church-event'),
	),
	'googleplus' => array(
		'link' => 'https://plus.google.com/share?url=',
		'title' => __('Share on Google Plus', 'church-event'),
		'text' => __('+1', 'church-event'),
	),
	'pinterest' => array(
		'link' => '#',
		'title' => __('Share on Pinterest', 'church-event'),
		'text' => __('Pin it', 'church-event'),
	),
);

if(WpvTemplates::has_share($context)):
?>
<div class="clearfix <?php echo apply_filters('wpv_share_class', 'share-btns')?>">
	<div class="sep-3"></div>
	<ul class="socialcount" data-url="<?php esc_attr_e(get_permalink()) ?>" data-share-text="<?php esc_attr_e(get_the_title()) ?>" data-media="">
		<?php foreach($networks as $slug => $cfg): ?>
			<?php if(wpv_get_option("share-$context-$slug")): ?>
				<li class="<?php echo $slug ?>">
					<a href="<?php echo $cfg['link'] ?><?php echo urlencode(get_permalink()) ?>" title="<?php esc_attr_e($cfg['title']) ?>">
						<?php echo do_shortcode( "[icon name='$slug']" ) ?>
						<span class="count"><?php echo $cfg['text'] ?></span>
					</a>
				</li>&nbsp;
			<?php endif ?>
		<?php endforeach ?>
	</ul>
</div>
<?php
endif;