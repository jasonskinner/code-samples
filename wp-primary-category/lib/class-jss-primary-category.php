<?php
/**
 * Main class for plugin
 *
 * @package JSS_Primary_Category
 */

// If called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'JSS_Primary_Category' ) ) {
	/**
	 * Main Class JSS_Primary_Category
	 */
	class JSS_Primary_Category {


		/**
		 * Return instance of class JSS_Primary_Category_Admin
		 *
		 * @return JSS_Primary_Category instance of class
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
		 * JSS_Primary_Category constructor.
		 */
		public function __construct() {
			// require files.
			$this->required_files();
		}


		/**
		 * Load required files
		 */
		private function required_files() {
			require_once JSS_PATH . 'lib/class-metabox-primary-category.php';
			require_once JSS_PATH . 'lib/class-jss-primary-term.php';
			require_once JSS_PATH . 'lib/class-jss-primary-frontend.php';
		}

	}

	new JSS_Primary_Category();
}