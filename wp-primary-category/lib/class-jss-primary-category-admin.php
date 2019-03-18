<?php
/**
 * Class to handle admin interations in plugin
 *
 * @package JSS_Primary_Category
 */

// If called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'JSS_Primary_Category_Admin' ) ) {

	/**
	 * JSS_Primary_Category_Admin Class
	 */
	class JSS_Primary_Category_Admin {
		/**
		 * Create instance of JSS_Primary_Category_Admin
		 *
		 * @var JSS_Primary_Category_Admin
		 */
		protected static $instance = null;

		/**
		 * Return instance of class JSS_Primary_Category_Admin
		 *
		 * @return instance of class JSS_Primary_Category_Admin
		 */
		public static function get_instance() {
			// set instance.
			if ( null === self::$instance ) {
				self::$instance = new self();
			}

			// return.
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->register_hooks();
			add_action( 'current_screen', array( $this, 'this_screen' ) );
		}

		/**
		 * Determine if page is post edit or new with action
		 *
		 * @return bool
		 */
		public function is_post() {
			/**
			 * Checks current screen
			 *
			 * @return bool true or false
			 */
			return $this->this_screen();
		}

		/**
		 * Get current screen in the admin.
		 *
		 * @return bool
		 */
		public function this_screen() {
			$screen = get_current_screen();
			// if it is default post.
			if ( 'post' === $screen->id ) {
				// return true.
				return true;
			}

			// if cpt is not post and base is post and not a page.
			if ( 'post' !== $screen->post_type && 'post' === $screen->base && 'page' !== $screen->post_type ) {
				// if it has default categories.
				if ( has_category( '' ) ) {
					// return true.
					return true;
				}
			}

			return false;
		}

		/**
		 * Get current ID
		 *
		 * @return integer
		 */
		public function get_current_id() {
			// if has admin screen.
			if ( $this->this_screen() ) {
				// return.
				return get_the_ID();
			}
		}

		/**
		 * @param $taxonomy_name
		 *
		 * @return mixed
		 */
		protected function get_primary_term( $taxonomy_name ) {
			// get primary term.
			$primary_term = new JSS_Primary_Term( $taxonomy_name, $this->get_current_id() );

			// return.
			return $primary_term->get_primary_term();
		}

		/**
		 * Register actions for Admin
		 *
		 * @access private
		 * @return void
		 */
		public function register_hooks() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		/**
		 * Enqueue all assets required for plugin in admin
		 */
		public function enqueue_scripts() {
			// return is not edit post.
			if ( ! $this->is_post() ) {
				return;
			}

			// register styles and scripts.
			wp_register_style( 'jss-category-metabox-css', JSS_URL . 'admin/css/output/jss-category-metabox.min.css', array(), '0.1' );
			wp_register_script( 'jss-category-metabox-js', JSS_URL . 'admin/js/output/jss-category-metabox.min.js', array( 'jquery' ), '0.1' );

			// Enqueue admin scripts.
			wp_enqueue_style( 'jss-category-metabox-css' );
			wp_enqueue_style( 'jss-category-metabox-js' );
		}

		/**
		 * Get category
		 *
		 * @param $post_id
		 *
		 * @return array
		 */
		public function get_term_object( $post_id ) {
			// return category.
			return get_the_category( $post_id );
		}
	}

	JSS_Primary_Category_Admin::get_instance();
}