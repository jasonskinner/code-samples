<?php

// If called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Register CPT
 *
 * This could be more object oriented with params, but it is just for a test
 * Used to test conditionals and functionality against another created CPT without categories
 */
class JSS_Register_Test_CPT {
	/**
	 * @var string
	 *
	 * Set post type params
	 */
	private $type = 'test';
	private $slug = 'tests';
	private $name = 'Tests';
	private $singular_name = 'Test';

	/**
	 * Register post type
	 */
	public function register() {
		$labels = array(
			'name'               => $this->name,
			'singular_name'      => $this->singular_name,
			'add_new'            => 'Add New',
			'add_new_item'       => 'Add New ' . $this->singular_name,
			'edit_item'          => 'Edit ' . $this->singular_name,
			'new_item'           => 'New ' . $this->singular_name,
			'all_items'          => 'All ' . $this->name,
			'view_item'          => 'View ' . $this->name,
			'search_items'       => 'Search ' . $this->name,
			'not_found'          => 'No ' . strtolower( $this->name ) . ' found',
			'not_found_in_trash' => 'No ' . strtolower( $this->name ) . ' found in Trash',
			'parent_item_colon'  => '',
			'menu_name'          => $this->name,
		);
		$args   = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => $this->slug ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'menu_position'      => 8,
			'menu_icon'          => 'dashicons-star-filled',
			'capability_type'    => 'post',
			'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
		);
		register_post_type( $this->type, $args );
		register_taxonomy_for_object_type( 'category', $this->type );
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		// Register the post type.
		add_action( 'init', array( $this, 'register' ) );
	}
}

new JSS_Register_Test_CPT();