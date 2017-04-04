<?php

/**
 * Header slider template for LayerSlider WP
 *
 * @package  wpv
 */

$post_id = wpv_get_the_ID();

if ( is_null( $post_id ) ) {
	return;
}

$id = (int) str_replace( 'layerslider-', '', wpv_post_meta( $post_id, 'slider-category', true ) );

if ( ! empty( $id ) && function_exists( 'layerslider_check_unit' ) ) {
	$slider = lsSliderById( $id );

	if ( null !== $slider ) {
		$slides = is_string( $slider['data'] ) ? json_decode( $slider['data'], true ) : $slider['data'];

		echo "<div class='layerslider-fixed-wrapper' style='height:" . layerslider_check_unit( $slides['properties']['height'] ) . "'>"; // xss ok
		echo do_shortcode( '[layerslider id="'.$id.'"]' ); // xss ok
		echo '</div>';
		echo '<div style="height:1px;margin-top:-1px"></div>';

	}
}
