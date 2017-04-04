<?php
	$tag = (int)$count == 1 ? 'span' : 'div';
?>
<?php foreach($events as $event): ?>
	<<?php echo $tag ?>>
		<span class="wpv-countdown single-event style-<?php echo esc_attr( $style ) ?> layout-<?php echo esc_attr( $layout ) ?>" data-until="<?php echo esc_attr( Tribe__Events__Timezones::event_start_timestamp( $event->ID ) ) ?>" data-done="<?php echo esc_attr( $ongoing ) ?>">
			<?php
				$split = false;
				include locate_template( 'templates/countdown-parts.php' );
			?>
		</span>
		<a href="<?php echo tribe_get_event_link( $event ) ?>" title="<?php esc_attr( $read_more_text ) ?>" class="wpv-event-read-more"><?php echo $read_more_text // xss ok ?></a>
	</<?php echo $tag ?>>
<?php endforeach; ?>