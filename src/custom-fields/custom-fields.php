<?php
/**
 * Custom Fields
 *
 * @since   0.1.6
 * @package Site_Functionality
 */

namespace Site_Functionality\CustomFields;

use Site_Functionality\CustomFields\User;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class CustomFields {

	/**
	 * Instance of the class.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Class Instance.
	 *
	 * @return CustomFields
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof CustomFields ) ) {
			self::$instance = new CustomFields();
			self::$instance->init();
		}
		return self::$instance;
	}

	/**
	 * Initialize the class.
	 *
	 * @since 0.1.3
	 */
	public function init() {
		/**
		 * This general class is always being instantiated as requested in the Bootstrap class
		 *
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here
		 */
		$this->dependencies();

		apply_filters( 'acf/settings/l10n_textdomain', array( $this, 'text_domain' ) );
	}

	/**
	 * Load dependencies
	 *
	 * @return void
	 */
	public function dependencies() : void {
		include_once SITE_CORE_DIR . '/src/custom-fields/user.php';
		$user = User::instance();
	}

	/**
	 * Modify Setting
	 *
	 * @link https://www.advancedcustomfields.com/resources/acf-settings/
	 *
	 * @param  string $value
	 * @return string
	 */
	public function text_domain( $value ) : string {
		$value = 'site-functionality';
		return $value;
	}

}


/**
 * The function provides access to the class methods.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * @return object
 */
function init() {
	return CustomFields::instance();
}
init();
