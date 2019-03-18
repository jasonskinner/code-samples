<?php
/**
 * Post Primary Term
 *
 * @package JSS_Primary_Category
 */

// If called directly, abort
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class JSS_Primary_Category_Term
 */
class JSS_Primary_Term {

	/**
	 * Taxonomy
	 *
	 * @var string
	 */
	protected $taxonomy_name;

	/**
	 * Post ID
	 *
	 * @var int
	 */
	protected $post_id;

	/**
	 * JSS_Primary_Category_Term constructor.
	 *
	 * @param $taxonomy_name
	 * @param $post_id post
	 */
	public function __construct( $taxonomy_name, $post_id ) {
		$this->taxonomy_name = $taxonomy_name;
		$this->post_id       = $post_id;
	}

	/**
	 * Return primary term id
	 *
	 * @return bool|mixed
	 */
	public function get_primary_term_id() {
		// get post meta.
		$primary_term_id = get_post_meta( $this->post_id, 'jss_primary_taxonomy_' . $this->taxonomy_name, true );

		// get cat id.
		$terms = $this->get_term_id();

		// if not is array or plucked term_id, bail.
		if ( ! in_array( $primary_term_id, wp_list_pluck( $terms, 'term_id' ), true ) ) {
			$primary_term_id = false;
		}

		// return.
		return ( $primary_term_id ) ? ( $primary_term_id ) : false;
	}

	/**
	 * Save the new primary term
	 *
	 * @param $new_primary_term_id
	 *
	 * @return void
	 */
	public function save_primary_term( $new_primary_term_id ) {
		update_post_meta( $this->post_id, 'jss_primary_taxonomy_' . $this->taxonomy_name, $new_primary_term_id );
	}

	/**
	 * Get terms for current post id
	 *
	 * @return array|false|WP_Error
	 */
	public function get_term_id() {
		// get terms.
		$terms = get_the_terms( $this->post_id, $this->taxonomy_name );

		// if WordPress error return.
		if ( is_wp_error( $terms ) ) {
			return;
		}

		// if term is not array, make it.
		if ( ! array( $terms ) ) {
			$terms = array();
		}

		// return.
		return $terms;
	}
}