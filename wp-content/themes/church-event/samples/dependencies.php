<?php

/**
 * Declare plugin dependencies
 *
 * @package wpv
 */

/**
 * Declare plugin dependencies
 */
function wpv_register_required_plugins() {
	$plugins = apply_filters( 'vamtam-required-plugins', array(
		array(
			'name' => 'Seamless Donations',
			'slug' => 'seamless-donations',
			'required' => false,
		),

		array(
			'name' => 'Contact Form 7',
			'slug' => 'contact-form-7',
			'required' => false,
		),

		array(
			'name' => 'WooCommerce',
			'slug' => 'woocommerce',
			'required' => false,
		),

		array(
			'name' => 'WP Retina 2x',
			'slug' => 'wp-retina-2x',
			'required' => false,
		),

		array(
			'name' => 'MailPoet Newsletters (formerly Wysija)',
			'slug' => 'wysija-newsletters',
			'required' => false,
		),

		array(
			'name' => 'The Events Calendar',
			'slug' => 'the-events-calendar',
			'required' => false,
		),

		array(
			'name' => 'Love it (Maintained by Vamtam)',
			'slug' => 'vamtam-love-it',
			'source' => WPV_PLUGINS . 'vamtam-love-it.zip',
			'required' => false,
			'version' => '1.0.0',
		),

		array(
			'name' => 'LayerSlider WP',
			'slug' => 'layerslider',
			'source' => WPV_PLUGINS . 'layerslider.zip',
			'required' => false,
			'version' => '5.6.9',
		),

		array(
			'name' => 'Vamtam Push Menu',
			'slug' => 'vamtam-push-menu',
			'source' => WPV_PLUGINS . 'vamtam-push-menu.zip',
			'required' => false,
			'version' => '1.3.0',
		),

		array(
			'name' => 'Vamtam Sermons',
			'slug' => 'vamtam-sermons',
			'source' => WPV_PLUGINS . 'vamtam-sermons.zip',
			'required' => false,
			'version' => '1.0.0',
		),
	) );

	$config = array(
		'default_path' => '',    // Default absolute path to pre-packaged plugins
		'is_automatic' => true,  // Automatically activate plugins after installation or not
	);

	tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'wpv_register_required_plugins' );