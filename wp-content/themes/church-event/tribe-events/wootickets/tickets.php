<?php
/**
 * Renders the WooCommerce tickets table/form
 *
 * @version 4.3.3
 *
 * @var bool $global_stock_enabled
 * @var bool $must_login
 */
global $woocommerce;

$is_there_any_product         = false;
$is_there_any_product_to_sell = false;
$unavailability_messaging     = is_callable( array( $this, 'do_not_show_tickets_unavailable_message' ) );

ob_start();


/**
 * Filter classes on the Cart Form
 *
 * @since  4.3.2
 *
 * @param array $cart_classes
 */
$cart_classes = (array) apply_filters( 'tribe_events_tickets_woo_cart_class', array( 'cart' ) );
?>
<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ) ?>" class="<?php echo implode( ' ', $cart_classes ); ?>" method="post" enctype='multipart/form-data'>
	<h2 class="tribe-events-tickets-title"><?php esc_html_e( 'Tickets', 'event-tickets-plus' ) ?></h2>

	<table width="100%" class="tribe-events-tickets">
		<?php
		foreach ( $tickets as $ticket ) {
			/**
			 * Changing any HTML to the `$ticket` Arguments you will need apply filters
			 * on the `wootickets_get_ticket` hook.
			 */

			/**
			 * @var Tribe__Tickets__Ticket_Object $ticket
			 * @var WC_Product $product
			 */
			global $product;

			if ( class_exists( 'WC_Product_Simple' ) ) {
				$product = new WC_Product_Simple( $ticket->ID );
			} else {
				$product = new WC_Product( $ticket->ID );
			}

			if ( $ticket->date_in_range( current_time( 'timestamp' ) ) ) {

				$is_there_any_product = true;
				$data_product_id = 'data-product-id="' . esc_attr( $ticket->ID ) . '"';

				echo sprintf( '<input type="hidden" name="product_id[]" value="%d">', esc_attr( $ticket->ID ) );

				echo '<tr><td class="tickets_name" colspan="2">';
					echo $ticket->name; // xss ok
					echo '<p class="tickets_description">' . $ticket->description . '</p>'; // xss ok
				echo '</td></tr>';

				echo '<tr>';

				/**
				 * Filter classes on the Price column
				 *
				 * @since  4.3.2
				 *
				 * @param array $column_classes
				 */
				$column_classes = (array) apply_filters( 'tribe_events_tickets_woo_quantity_column_class', array( 'woocommerce' ) );
				echo '<td class="' . implode( ' ', $column_classes ) . '" ' . $data_product_id . '>';

				if ( $product->is_in_stock() ) {
					// Max quantity will be left open if backorders allowed, restricted to 1 if the product is
					// constrained to be sold individually or else set to the available stock quantity
					$max_quantity = $product->backorders_allowed() ? '' : $product->get_stock_quantity();
					$max_quantity = $product->is_sold_individually() ? 1 : $max_quantity;
					$original_stock = $ticket->original_stock();

					// For global stock enabled tickets with a cap, use the cap as the max quantity
					if ( $global_stock_enabled && Tribe__Tickets__Global_Stock::CAPPED_STOCK_MODE === $ticket->global_stock_mode()) {
						$max_quantity = $ticket->global_stock_cap();
						$original_stock = $ticket->global_stock_cap();
					}

					woocommerce_quantity_input( array(
						'input_name'  => 'quantity_' . $ticket->ID,
						'input_value' => 0,
						'min_value'   => 0,
						'max_value'   => $must_login ? 0 : $max_quantity, // Currently WC does not support a 'disable' attribute
					) );

					$is_there_any_product_to_sell = true;

					$remaining = $ticket->remaining();


					if ( $remaining ) {
						?>
						<span class="tribe-tickets-remaining">
							<?php
							echo sprintf( esc_html__( '%1$s available', 'event-tickets-plus' ),
								'<span class="available-stock" ' . $data_product_id . '>' . esc_html( $remaining ) . '</span>'
							);
							?>
						</span>
						<?php
					}

					do_action( 'wootickets_tickets_after_quantity_input', $ticket, $product );
				} else {
					echo '<span class="tickets_nostock">' . esc_html__( 'Out of stock!', 'event-tickets-plus' ) . '</span>';
				}
				echo '</td>';

				echo '<td class="woocommerce tickets_price"><span class="price"><span class="amount">' . $this->get_price_html( $product ) . '</span></span></td>';

				echo '</tr>';

				if ( class_exists( 'Tribe__Tickets_Plus__Attendees_List' ) && ! Tribe__Tickets_Plus__Attendees_List::is_hidden_on( get_the_ID() ) ) {
					echo '<tr class="tribe-tickets-attendees-list-optout">' . '<td colspan="2">' .
						 '<input type="checkbox" name="optout_' . $ticket->ID . '" id="tribe-tickets-attendees-list-optout-woo">' .
						 '<label for="tribe-tickets-attendees-list-optout-woo">' . esc_html__( 'Don\'t list me on the public attendee list', 'event-tickets' ) . '</label>' . '</td>' .
						 '</tr>';
				}

				if ( class_exists( 'Tribe__Tickets_Plus__Main' ) ) {
					include Tribe__Tickets_Plus__Main::instance()->get_template_hierarchy( 'meta.php' );
				}
			}
		}

		if ( $is_there_any_product_to_sell ) {
			?>
			<tr>
				<td colspan="2" class="woocommerce add-to-cart">
					<?php if ( class_exists( 'Tribe__Tickets_Plus__Main' ) && $must_login ): ?>
						<?php include Tribe__Tickets_Plus__Main::instance()->get_template_hierarchy( 'login-to-purchase' ); ?>
					<?php else: ?>
						<button type="submit" name="wootickets_process" value="1"
							class="button alt"><?php esc_html_e( 'Add to cart', 'event-tickets-plus' );?></button>
					<?php endif; ?>
				</td>
			</tr>
			<?php
		} ?>
	</table>
</form>

<?php
$content = ob_get_clean();
if ( $is_there_any_product ) {
	echo $content;

	// @todo remove safeguard in 4.3 or later
	if ( $unavailability_messaging ) {
		// If we have rendered tickets there is generally no need to display a 'tickets unavailable' message
		// for this post
		$this->do_not_show_tickets_unavailable_message();
	}
} else {
	// @todo remove safeguard in 4.3 or later
	if ( $unavailability_messaging ) {
		$unavailability_message = $this->get_tickets_unavailable_message( $tickets );

		// if there isn't an unavailability message, bail
		if ( ! $unavailability_message ) {
			return;
		}
	}
}
