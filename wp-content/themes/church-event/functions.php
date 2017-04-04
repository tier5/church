<?php

/**
 * Theme functions. Initializes the Vamtam Framework.
 *
 * @package  wpv
 */

require_once('vamtam/classes/framework.php');

new WpvFramework(array(
	'name' => 'church-event',
	'slug' => 'church-event'
));

// TODO remove next line when the editor is fully functional, to be packaged as a standalone module with no dependencies to the theme
define ('VAMTAM_EDITOR_IN_THEME', true); include_once THEME_DIR.'vamtam-editor/editor.php';

if ( class_exists( 'LS_Sources' ) ) {
	LS_Sources::addDemoSlider( WPV_SAMPLES_DIR . 'layerslider' );
}

remove_action( 'admin_head', 'jordy_meow_flattr', 1 );

