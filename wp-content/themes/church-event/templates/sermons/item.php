<?php
	$sermon_media = WpvTemplates::get_sermon_media();
?>
<div class="wpv-sermon-wrapper">
	<?php if(has_post_thumbnail()): ?>
		<div class="left-part">
			<?php the_post_thumbnail('wpv-sermons-thumbnail') ?>
		</div>
	<?php endif ?>
	<div class="center-part">
		<h2 class="title"><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
		<div>
			<span class="latest-message"><?php _e('Message from', 'church-event') ?></span> <span class="author"><?php
				global $authordata;
				if(is_object($authordata)) {
					echo apply_filters( 'the_author_posts_link', sprintf(
						'<a href="%1$s" title="%2$s" rel="author">%3$s</a>',
						esc_url( add_query_arg( 'post_type', 'wpv_sermon', get_author_posts_url( $authordata->ID, $authordata->user_nicename ) ) ),
						esc_attr( sprintf( __( 'Posts by %s', 'church-event' ), get_the_author() ) ),
						get_the_author()
					));
				}
			?></span>.
			<?php the_time(get_option('date_format')); ?>.
			<?php
				$separator = ', ';
				$sermon_categories = wp_get_object_terms( get_the_ID(), 'wpv_sermons_category', array( 'fields' => 'ids' ) );
				$sermon_tags       = wp_get_object_terms( get_the_ID(), 'wpv_sermons_tag', array( 'fields' => 'ids' ) );
			?>
			<?php
				if ( ! empty( $sermon_categories ) && ! is_wp_error( $sermon_categories ) ) {
					_e( 'Category: ', 'church-event' );

					$terms = wp_list_categories( array(
						'taxonomy'          => 'wpv_sermons_category',
						'hierarchical'      => false,
						'show_option_none ' => '',
						'title_li'          => '',
						'style'             => 'none',
						'echo'              => false,
						'include'           => $sermon_categories,
					) );

					echo rtrim( trim( str_replace( '<br />',  $separator, $terms ) ), $separator );
				}

				echo ' ';

				if ( ! empty( $sermon_tags ) && ! is_wp_error( $sermon_tags ) ) {
					_e( 'Tags: ', 'church-event' );

					$terms = wp_list_categories( array(
						'taxonomy'          => 'wpv_sermons_tag',
						'hierarchical'      => false,
						'show_option_none ' => '',
						'title_li'          => '',
						'style'             => 'none',
						'echo'              => false,
						'include'           => $sermon_tags,
					) );

					echo rtrim( trim( str_replace( '<br />',  $separator, $terms ) ), $separator );
				}
			?>
		</div>
	</div>
	<div class="right-part" data-items="<?php echo count($sermon_media['media_links']) ?>">
		<?php get_template_part( 'templates/sermons/media' ) ?>
	</div>
</div>