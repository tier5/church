<?php

/*
Plugin Name: Vamtam Sermons
Description: Semons post type (backend code only)
Version: 1.1.0
Author: Vamtam
Author URI: http://vamtam.com
*/


class Vamtam_Sermons {
	const VERSION = '1.0.4';

	public function __construct() {
		add_action( 'init', array( __CLASS__, 'init' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'load_metaboxes' ) );
		add_action( 'save_post', array( __CLASS__, 'load_metaboxes' ) );
		add_action( 'widgets_init', array( __CLASS__, 'load_widgets' ) );
		add_action( 'admin_init', array( __CLASS__, 'admin_init' ) );

		if ( !class_exists( 'Vamtam_Updates' ) )
			require 'vamtam-updates/class-vamtam-updates.php';

		$plugin_slug = basename( dirname( __FILE__ ) );
		$plugin_file = basename( __FILE__ );

		new Vamtam_Updates( array(
			'slug' => $plugin_slug,
			'main_file' => $plugin_slug . '/' . $plugin_file,
		) );
	}

	/**
	 * flush rewrite rules on update/install
	 */
	public static function admin_init() {
		if ( get_option( 'vamtam-sermons-version' ) !== self::VERSION ) {
			flush_rewrite_rules();
			update_option( 'vamtam-sermons-version', self::VERSION );
		}
	}

	/**
	 * Register post type and taxonomy
	 */
	public static function init() {
		$domain = 'vamtam-sermons';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

		register_post_type('wpv_sermon', array(
			'labels' => array(
				'name'               => _x('Sermons', 'post type general name', 'vamtam-sermons' ),
				'singular_name'      => _x('Sermon', 'post type singular name', 'vamtam-sermons' ),
				'add_new'            => _x('Add New', 'sermon', 'vamtam-sermons' ),
				'add_new_item'       => __('Add New Sermon', 'vamtam-sermons' ),
				'edit_item'          => __('Edit Sermon', 'vamtam-sermons' ),
				'new_item'           => __('New Sermon', 'vamtam-sermons' ),
				'view_item'          => __('View Sermon', 'vamtam-sermons' ),
				'search_items'       => __('Search Sermons', 'vamtam-sermons' ),
				'not_found'          =>  __('No Sermons found', 'vamtam-sermons' ),
				'not_found_in_trash' => __('No Sermons found in Trash', 'vamtam-sermons' ),
				'parent_item_colon'  => '',
			),
			'singular_label'      => __('Sermon', 'vamtam-sermons' ),
			'public'              => true,
			'exclude_from_search' => false,
			'show_ui'             => true,
			'capability_type'     => 'post',
			'hierarchical'        => false,
			'rewrite'             => array(
				'with_front' => false,
				'slug'       => 'sermon'
			),
			'query_var'     => false,
			'menu_position' => 55,
			'supports'      => array(
				'author',
				'comments',
				'editor',
				'page-attributes',
				'thumbnail',
				'title',
				'revisions',
			)
		));

		register_taxonomy( 'wpv_sermons_category', 'wpv_sermon', array(
			'hierarchical' => true,
			'labels'       => array(
				'name'                       => _x( 'Categories', 'taxonomy general name', 'vamtam-sermons' ),
				'singular_name'              => _x( 'Sermon Category', 'taxonomy singular name', 'vamtam-sermons' ),
				'search_items'               => __( 'Search Categories', 'vamtam-sermons' ),
				'popular_items'              => __( 'Popular Categories', 'vamtam-sermons' ),
				'all_items'                  => __( 'All Categories', 'vamtam-sermons' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => __( 'Edit Sermon Category', 'vamtam-sermons' ),
				'update_item'                => __( 'Update Sermon Category', 'vamtam-sermons' ),
				'add_new_item'               => __( 'Add New Sermon Category', 'vamtam-sermons' ),
				'new_item_name'              => __( 'New Sermon Category Name', 'vamtam-sermons' ),
				'separate_items_with_commas' => __( 'Separate Sermon category with commas', 'vamtam-sermons' ),
				'add_or_remove_items'        => __( 'Add or remove Sermon category', 'vamtam-sermons' ),
				'choose_from_most_used'      => __( 'Choose from the most used Sermon category', 'vamtam-sermons' )
			),
			'show_ui'   => true,
			'query_var' => true,
			'rewrite'   => false,
		) );

		register_taxonomy( 'wpv_sermons_tag', 'wpv_sermon', array(
			'hierarchical' => false,
			'labels'       => array(
				'name'                       => _x( 'Tags', 'taxonomy general name', 'vamtam-sermons' ),
				'singular_name'              => _x( 'Sermon Tags', 'taxonomy singular name', 'vamtam-sermons' ),
				'search_items'               => __( 'Search Sermon Tags', 'vamtam-sermons' ),
				'popular_items'              => __( 'Popular Tags', 'vamtam-sermons' ),
				'all_items'                  => __( 'All Tags', 'vamtam-sermons' ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				'edit_item'                  => __( 'Edit Sermon Tag', 'vamtam-sermons' ),
				'update_item'                => __( 'Update Sermon Tag', 'vamtam-sermons' ),
				'add_new_item'               => __( 'Add New Sermon Tag', 'vamtam-sermons' ),
				'new_item_name'              => __( 'New Tag Name', 'vamtam-sermons' ),
				'separate_items_with_commas' => __( 'Separate Sermon tag with commas', 'vamtam-sermons' ),
				'add_or_remove_items'        => __( 'Add or remove Sermon tag', 'vamtam-sermons' ),
				'choose_from_most_used'      => __( 'Choose from the most used Sermon tags', 'vamtam-sermons' )
			),
			'show_ui'   => true,
			'query_var' => true,
			'rewrite'   => false,
		) );
	}

	/**
	 * Add metabox, if the required Vamtam API is present
	 *
	 * @param int|null $post_id  id of the current post (if any)
	 */
	public static function load_metaboxes( $post_id = null ) {
		if ( class_exists( 'WpvMetaboxesGenerator' ) && defined( 'WPV_THEME_METABOXES' ) ) {
			$config = array(
				'id'       => 'vamtam-sermon-options',
				'title'    => __('VamTam Sermon Options', 'vamtam-sermons'),
				'pages'    => array('wpv_sermon'),
				'context'  => 'normal',
				'priority' => 'high',
				'post_id'  => $post_id,
			);


			$options = include WPV_THEME_METABOXES . 'sermons.php';
			new WpvMetaboxesGenerator($config, $options);
		}
	}

	/**
	 * Register widgets
	 */
	public static function load_widgets() {
		$widgets = array(
			'categories',
			'recent',
		);

		foreach ( $widgets as $name ) {
			require_once plugin_dir_path( __FILE__ ) . "widgets/$name.php";
		}
	}
}

new Vamtam_Sermons;
