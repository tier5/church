<?php
/**
 * List View Single Event
 * This file contains one event in the list view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/single-event.php
 *
 * @package TribeEventsCalendar
 * @since  3.0
 * @author Modern Tribe Inc.
 *
 */

if ( !defined('ABSPATH') ) { die('-1'); }

global $post;

$featured_image = tribe_event_featured_image( null, 'post-small-2' );
?>

<div class="small-event-header clearfix <?php if(empty($featured_image)) echo 'no-image' ?>">
	<div class="tribe-events-event-meta-wrapper">
		<?php do_action( 'tribe_events_before_the_meta' ); ?>
			<div class="tribe-events-event-meta">
				<?php
					$stime = tribe_get_start_date( $post, false, get_option('time_format') );
					$etime = tribe_get_end_date( $post, false, get_option('time_format') );
				?>
				<a href="<?php echo tribe_get_event_link() ?>" title="<?php esc_attr_e('Read More') ?>">
					<div class="date">
						<div class="day"><?php echo tribe_get_start_date( $post, false, 'd' ) ?></div>
						<div class="month"><?php echo tribe_get_start_date( $post, false, 'F' ) ?></div>
					</div>
				</a>
				<div class="when-where">
					<?php if ( ! tribe_event_is_all_day( $post->ID ) ): ?>
						<div><?php echo $stime ?> <?php if ( $stime !== $etime ) echo '&mdash; ' . $etime ?></div>
					<?php else: ?>
						<div><?php _e( 'All Day', 'church-event' ) ?></div>
					<?php endif ?>
					<div>@ <?php
						if( class_exists( 'Tribe__Events__Pro__Templates__Single_Venue' ) ) {
							echo tribe_get_venue_link( $post->ID, true );
						} else {
							echo tribe_get_venue( $post->ID );
						}
					?></div>
				</div>
				<a href="<?php echo tribe_get_event_link() ?>" title="<?php esc_attr_e('Read More') ?>" class="button button-border accent1 hover-accent1"><span class="btext"><?php wpvge( 'tribe-events-join-text' ) ?></span></a>
			</div><!-- .tribe-events-event-meta -->
		<?php do_action( 'tribe_events_after_the_meta' ); ?>
	</div>
	<?php echo $featured_image; ?>
</div>

<div class="tribe-events-event-details tribe-clearfix <?php if(empty($featured_image)) echo 'no-image' ?>">

	<!-- Event Title -->
	<?php do_action( 'tribe_events_before_the_event_title' ); ?>
	<h4 class="tribe-events-list-event-title entry-title summary">
		<a class="url" href="<?php echo tribe_get_event_link() ?>" title="<?php the_title() ?>" rel="bookmark">
			<?php the_title(); ?>
		</a>
	</h4>
	<?php do_action( 'tribe_events_after_the_event_title' ); ?>

	<!-- Event Content -->
	<?php do_action( 'tribe_events_before_the_content' ); ?>
	<div class="tribe-events-list-photo-description tribe-events-content entry-summary description">
		<?php the_excerpt(); ?>
	</div>
	<?php do_action( 'tribe_events_after_the_content' ) ?>

</div><!-- /.tribe-events-event-details -->
