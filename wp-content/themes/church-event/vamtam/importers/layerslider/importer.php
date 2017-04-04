<?php
/**
 * VamTam LayerSlider Importer
 */

if ( ! defined( 'WP_LOAD_IMPORTERS' ) )
	return;

// Load Importer API
require_once ABSPATH . 'wp-admin/includes/import.php';

if ( ! class_exists( 'WP_Importer' ) ) {
	$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
	if ( file_exists( $class_wp_importer ) )
		require $class_wp_importer;
}

/**
 * WordPress Importer class for managing the import process of a WXR file
 *
 * @package Importer
 */
if ( class_exists( 'WP_Importer' ) ) {
class Vamtam_LayerSlider_Import extends WP_Importer {
	private $dir;

	public function __construct() {
		$this->dir = WPV_SAMPLES_DIR . 'layerslider';
	}

	/**
	 * Registered callback function for the WordPress Importer
	 *
	 * Manages the three separate stages of the WXR import process
	 */
	public function dispatch() {
		$this->header();

		check_admin_referer( 'vamtam-import-layerslider' );

		set_time_limit( 0 );
		$this->import( );

		$this->footer();
	}

	/**
	 * The main controller for the actual import stage.
	 */
	public function import() {
		add_filter( 'http_request_timeout', array( $this, 'bump_request_timeout' ) );

		$this->import_start();

		wp_suspend_cache_invalidation( true );

		$this->import_sliders();

		wp_suspend_cache_invalidation( false );

		$this->import_end();
	}

	private function import_sliders() {
		ob_start();

		include LS_ROOT_PATH.'/classes/class.ls.importutil.php';

		$sliders = glob( $this->dir . '/*/*.zip' );
		foreach ( $sliders as $file ) {
			$import = new LS_ImportUtil( $file );
		}

		if ( WP_DEBUG ) {
			echo ob_get_clean(); // xss ok
		} else {
			ob_end_clean();
		}
	}

	private function import_start() {
		if ( ! is_dir( $this->dir ) ) {
			echo '<p><strong>' . esc_html__( 'Sorry, there has been an error.', 'wordpress-importer' ) . '</strong><br />';
			echo esc_html__( 'The file does not exist, please try again.', 'wordpress-importer' ) . '</p>';
			$this->footer();
			die();
		}

		do_action( 'import_start' );
	}

	/**
	 * Performs post-import cleanup of files and the cache
	 */
	private function import_end() {
		$redirect = admin_url( '' );

		echo '<p>' . esc_html__( 'All done.', 'wordpress-importer' ) . ' <a href="' . esc_url( $redirect ) . '">' . esc_html__( 'Have fun!', 'wordpress-importer' ) . '</a></p>';

		$redirect = admin_url('admin.php?page=wpv_import');

		echo <<<REDIRECT
			<script>
				/*<![CDATA[*/
				setTimeout(function() {
					window.location = '$redirect';
				}, 3000);
				/*]]>*/
			</script>
REDIRECT;

		do_action( 'import_end' );
	}

	// Display import page title
	private function header() {
		echo '<div class="wrap">';
		echo '<h2>' . esc_html__( 'Import LayerSlider Samples', 'wordpress-importer' ) . '</h2>'; }

	// Close div.wrap
	private function footer() {
		echo '</div>';
	}

	/**
	 * Added to http_request_timeout filter to force timeout at 120 seconds during import
	 * @return int 120
	 */
	public function bump_request_timeout( $imp ) {
		return 120;
	}
}

} // class_exists( 'WP_Importer' )

function vamtam_layerslider_importer_init() {
	$GLOBALS['vamtam_layerslider_import'] = new Vamtam_LayerSlider_Import();
	register_importer( 'vamtam_layerslider', 'Vamtam Slider Revolution Import', sprintf( esc_html__( 'Import LayerSlider samples from VamTam themes, not to be used as a stand-alone importer.', 'church-event' ), VAMTAM_THEME_NAME ), array( $GLOBALS['vamtam_layerslider_import'], 'dispatch' ) );
}
add_action( 'admin_init', 'vamtam_layerslider_importer_init' );
