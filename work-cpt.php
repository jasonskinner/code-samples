<?php
/*
Plugin Name: jasonskinner.me work CPT
Plugin URI: http://jasonskinner.me/
Description: Adds work custom post type to admin.
Version: 1.0
Author: Jason Skinner
*/

// let's create the function for the custom type
function work_cpt() {
	// creating (registering) the custom type
	register_post_type( 'work', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
		// let's now add all the options for this post type
		array('labels' => array(
			'name' => __('Work', 'jsswp'), /* This is the Title of the Group */
			'singular_name' => __('Work', 'jsswp'), /* This is the individual type */
			'all_items' => __('All Work', 'jsswp'), /* the all items menu item */
			'add_new' => __('Add New', 'jsswp'), /* The add new menu item */
			'add_new_item' => __('Add New Work', 'jsswp'), /* Add New Display Title */
			'edit' => __( 'Edit', 'jsswp' ), /* Edit Dialog */
			'edit_item' => __('Edit Work', 'jsswp'), /* Edit Display Title */
			'new_item' => __('New Work', 'jsswp'), /* New Display Title */
			'view_item' => __('View Work', 'jsswp'), /* View Display Title */
			'search_items' => __('Search Work', 'jsswp'), /* Search Custom Type Title */
			'not_found' =>  __('Nothing found in the Database.', 'jsswp'), /* This displays if there are no entries yet */
			'not_found_in_trash' => __('Nothing found in Trash', 'jsswp'), /* This displays if there is nothing in the trash */
			'parent_item_colon' => ''
		), /* end of arrays */
		      'description' => __( 'jasonskinner.me work custom post type', 'jsswp' ), /* Custom Type Description */
		      'public' => true,
		      'publicly_queryable' => true,
		      'exclude_from_search' => false,
		      'show_ui' => true,
		      'query_var' => true,
		      'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */
		      'menu_icon' => 'dashicons-portfolio', /* the icon for the custom post type menu. uses built-in dashicons (CSS class name) */
		      'rewrite'	=> array( 'slug' => 'work', 'with_front' => true ), /* you can specify its url slug */
		      'has_archive' => false, /* you can rename the slug here */
		      'capability_type' => 'post',
		      'hierarchical' => false,
			/* the next one is important, it tells what's enabled in the post editor */
			  'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions', 'page-attributes')
		) /* end of options */
	); /* end of register post type */
}

// adding the function to the Wordpress init
add_action( 'init', 'work_cpt');

/*
for more information on taxonomies, go here:
http://codex.wordpress.org/Function_Reference/register_taxonomy
*/

// now let's add custom categories (these act like categories)
register_taxonomy( 'work_type',
	array('work'), /* if you change the name of register_post_type( 'custom_type', then you have to change this */
	array('hierarchical' => true,     /* if this is true, it acts like categories */
	      'labels' => array(
		      'name' => __( 'Work Type', 'jsswp' ), /* name of the custom taxonomy */
		      'singular_name' => __( 'Work Type', 'jsswp' ), /* single taxonomy name */
		      'search_items' =>  __( 'Search Work Type', 'jsswp' ), /* search title for taxomony */
		      'all_items' => __( 'All Work Types', 'jsswp' ), /* all title for taxonomies */
		      'parent_item' => __( 'Parent Work Type', 'jsswp' ), /* parent title for taxonomy */
		      'parent_item_colon' => __( 'Parent Work Type:', 'jsswp' ), /* parent taxonomy title */
		      'edit_item' => __( 'Edit Work Type', 'jsswp' ), /* edit custom taxonomy title */
		      'update_item' => __( 'Update Work Type', 'jsswp' ), /* update title for taxonomy */
		      'add_new_item' => __( 'Add New Work Type', 'jsswp' ), /* add new title for taxonomy */
		      'new_item_name' => __( 'New Custom Work Type', 'jsswp' ) /* name title for taxonomy */
	      ),
	      'show_admin_column' => true,
	      'show_ui' => true,
	      'query_var' => true,
	      'rewrite' => false
	)
);
