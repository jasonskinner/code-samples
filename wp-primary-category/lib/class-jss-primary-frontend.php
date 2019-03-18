<?php
/**
 * Frontend Class
 *
 * @package JSS_Primary_Category
 */

// If called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'JSS_Primary_Frontend' ) ) {

	/**
	 * Class JSS_Primary_Frontend
	 */
	class JSS_Primary_Frontend {

		/**
		 * @var string
		 */
		protected $query_var_prop = 'primary-category';

		/**
		 * @var string
		 */
		protected $key = 'jss-primary-category';

		/**
		 * Constructor
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			$this->make_primary_category_shortcode();
			add_action( 'query_vars', array( $this, 'jss_register_query_vars' ), 1 );
		}

		/**
		 * Get post meta
		 *
		 * @param $post
		 *
		 * @return mixed
		 */
		public function get_post_primary_meta( $post ) {
			return get_post_meta( $post->ID, $this->key );
		}


		/**
		 * Get posts that have a primary category
		 *
		 * @param $term
		 * @param $term
		 * @param array $args
		 *
		 * @return array|bool|WP_Error
		 */
		public function get_primary_category_posts( $args = array(), $showall = false ) {
			// check if the user is requesting an admin page or not main query.
			if ( is_admin() ) {
				return;
			}

			// Lets set up the WP_Query arguments.
			$defaults = array(
				'post_status'         => 'publish',
				'post_type'           => 'any',
				'ignore_sticky_posts' => 1,
			);

			// Get what is current selected.
			$selectOption = $_POST[ $this->query_var_prop ];

			// if query_var and show all posts is false or if selection option is emtpy. Retrieve queried posts.
			if ( ! empty( get_query_var( $this->query_var_prop ) && $showall === false ) || ! $selectOption === '' ) {
				$meta_query = array(
					'key'     => $this->key,
					'value'   => get_query_var( $this->query_var_prop ),
					'compare' => 'LIKE',
				);
				// Get all posts.
			} else {
				$meta_query = array(
					'key'     => $this->key,
					'compare' => 'EXISTS',
				);
			}

			// parse args.
			$args = wp_parse_args( $args, $defaults );
			// make meta_query array.
			$args['meta_query'] = array( $meta_query );
			// query.
			$query = new WP_Query( $args );
			// if more that 0 posts, return.
			if ( $query->post_count > 0 ) {
				return $query->posts;
			} else {
				echo 'No Primary Categories Selected';

				return;
			}
			// reset postdata.
			wp_reset_postdata();
		}

		/**
		 * Render the frontend
		 *
		 * @return void
		 */
		public function render_frontend() {
			// display the selectbox.
			$this->render_select( args );

			// get all posts with args.
			$posts = $this->get_primary_category_posts();

			// empty array to fill.
			$category_options = array();

			// loop.
			foreach ( $posts as $post ) {
				// format etc.
				$category_helpers = $this->get_category_helpers( $post );
				?>
				<h2>
					<a href="<?php esc_url( the_permalink( $post->ID ) ); ?>">
						<?php echo esc_html( $post->post_title ); ?>
					</a>
				</h2>
				<p><?php echo esc_html( $category_helpers['nicename'] ); ?></p>
				<hr>
				<?php
			}
			// reset array.
			reset( $category_options );
		}

		/**
		 * Helper to format category data
		 *
		 * @param $post
		 *
		 * @return array
		 */
		public function get_category_helpers( $post ) {
			// empty array to fill.
			$category_options = array();

			// format category data.
			$primary_category        = get_post_meta( $post->ID, $this->key, true );
			$primary_category_string = str_replace( array( 'option', '-' ), array( '', ' ' ), $primary_category );
			$primary_category_name   = ucwords( $primary_category_string );

			// add to empty array.
			if ( ! in_array( $primary_category_name, $category_options, true ) ) {
				$category_options['nicename'] = $primary_category_name;
			}

			// add to empty array.
			if ( ! in_array( $primary_category, $category_options, true ) ) {
				$category_options['category'] = $primary_category;
			}

			// return.
			return $category_options;
		}

		/**
		 * Render select box to filter
		 *
		 * @param $args
		 */
		public function render_select( $args ) {
			$posts = $this->get_primary_category_posts( $args, true );
			?>
			<form id="jss-primary-category" post="get" action="">
				<select name="<?php echo $this->query_var_prop ?>" class="jss-primary-category"
				        onchange="this.form.submit()">
					<option value="" selected="selected">Select Primary Category</option>
					<?php

					foreach ( $posts as $post ) {
						$category_helpers = $this->get_category_helpers( $post );
						$match            = esc_html( $category_helpers['category'] );
						$query_var        = get_query_var( $this->query_var_prop );
						echo '<option value ="' . esc_html( $category_helpers['category'] ) . '" ' . selected( $match, $query_var ) . '>' . esc_html( $category_helpers['nicename'] ) . '</option>';
					}
					?>
				</select>
			</form>
			<?php
		}

		/**
		 * Register query vars
		 *
		 * @param $query_vars
		 *
		 * @return array
		 */
		public function jss_register_query_vars( $query_vars ) {
			$query_vars[] = $this->query_var_prop;

			return $query_vars;
		}

		/**
		 * Render shortcode for use in admin
		 *
		 * @return void
		 */
		public function make_primary_category_shortcode() {
			add_shortcode( 'primary_category_query', array( $this, 'render_frontend' ) );
		}

		/**
		 * Shortcode makeup
		 *
		 * @return void
		 */
		public function query_primary_category_post_shortcode() {
			echo esc_html( $this->get_primary_category_posts( 'category' ) );
		}
	}

	new JSS_Primary_Frontend();
}