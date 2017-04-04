<?php

if(!class_exists('Tribe__Events__Main')) return;

add_filter('tribe_event_meta_venue_map', '__return_false');

/**
 * Modify the events listing date headers
 *
 * @param  string $html        original html
 * @param  string $event_month original month
 * @param  string $event_year  original year
 * @return string              extended html
 */
function wpv_tribe_events_list_the_date_headers($html, $event_month, $event_year) {
	if(empty($html)) return '';

	if(!isset($GLOBALS['wpv_pretty_tribe_date_headers'])) return $html;

	if(!$GLOBALS['wpv_pretty_tribe_date_headers_first']) {
		$html = '</section>'.$html;
	}

	return $html.'<section class="wpv-tribe-events-block clearfix">';
}
add_filter('tribe_events_list_the_date_headers',  'wpv_tribe_events_list_the_date_headers', 100, 3);

function wpv_upcoming_events_title( $title ) {
	$upcoming = wpv_get_option( 'tribe-events-upcoming-title' );
	$past     = wpv_get_option( 'tribe-events-past-title' );
	$month    = wpv_get_option( 'tribe-events-month-title' );

	if ( ! empty( $upcoming ) && ( tribe_is_upcoming() || ( function_exists( 'tribe_is_map' ) && tribe_is_map() ) || ( function_exists( 'tribe_is_photo' ) && tribe_is_photo() ) ) ) {
		return $upcoming;
	} elseif ( ! empty( $past ) && tribe_is_past() ) {
		return $past;
	} elseif ( ! empty( $month ) && tribe_is_month() ) {
		return sprintf( $month, date_i18n( tribe_get_option( 'monthAndYearFormat', 'F Y' ), strtotime( tribe_get_month_view_date() ) ) );
	}

	return $title;
}
add_filter('tribe_get_events_title', 'wpv_upcoming_events_title');

function wpv_tribe_events_template_data_array( $json, $event, $additional ) {
	if ( function_exists( 'has_post_thumbnail' ) && has_post_thumbnail( $event->ID ) ) {
		$image_tool_arr          = wp_get_attachment_image_src( get_post_thumbnail_id( $event->ID ), array( 260, 175 ) );
		$json['imageTooltipSrc'] = $image_tool_arr[0];
	}

	return $json;
}
add_filter( 'tribe_events_template_data_array', 'wpv_tribe_events_template_data_array', 10, 3 );

function wpv_tribe_events_integration() {
	if ( ! is_singular( Tribe__Events__Main::POSTTYPE ) ) return;

	// large map
	function wpv_tribe_single_gmap() {
		if ( tribe_get_option( 'embedGoogleMaps', true ) ) {
			echo tribe_get_embedded_map();
		}
	}
	add_action( 'wpv_tribe_events_after_sidebars-1', 'wpv_tribe_single_gmap' );

	// upcoming events
	if(wpv_get_option('events-after-sidebars-2-content') !== '') {
		function wpv_tribe_single_upcoming() {
			echo do_shortcode(wpv_get_option('events-after-sidebars-2-content'));
		}
		add_action( 'wpv_tribe_events_after_sidebars-2', 'wpv_tribe_single_upcoming' );
	}

	// single event media
	function wpv_tribe_media() {
		get_template_part('tribe-events/single-event', 'media');
	}
	add_action( 'wpv_inside_main', 'wpv_tribe_media' );

	function wpv_tribe_events_get_event_link($link) {
		return str_replace('a href', 'a class="wpv-tribe-sibling" href', $link);
	}
	add_filter('tribe_events_get_event_link', 'wpv_tribe_events_get_event_link');
}
add_action('template_redirect', 'wpv_tribe_events_integration');
