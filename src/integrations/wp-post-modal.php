<?php
/**
 * Integrations with WP Post Modal
 *
 * @since   0.1.5
 * @package Site_Functionality
 */

namespace Site_Functionality\Integrations;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Modal {

	/**
	 * Instance of the class.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Class Instance.
	 *
	 * @return Post_Modal
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Post_Modal ) ) {
			self::$instance = new Post_Modal();
			self::$instance->init();
		}
		return self::$instance;
	}

	/**
	 * Initialize the class.
	 *
	 * @since 0.1.4
	 */
	public function init() {
		/**
		 * This general class is always being instantiated as requested in the Bootstrap class
		 *
		 * @see Bootstrap::__construct
		 *
		 * Add plugin code here
		 */

		if ( class_exists( '\WP_Post_Modal' ) ) {
			$plugin = new \WP_Post_Modal();
			\remove_action( 'the_content', array( $plugin, 'wrap_content' ) );
			\add_action( 'the_content', array( $this, 'wrap_content' ) );
		}
	}

	/**
	 * Modify content
	 * 
	 * @link https://wordpress.org/plugins/wp-post-modal/
	 *
	 * @param string $content
	 * @return mixed string || false
	 */
	public function wrap_content( $content ) {
		if ( ! empty( $content ) ) {
			$div_id = \apply_filters( 'define_wrapping_div_id', null ) ? \apply_filters( 'define_wrapping_div_id', null ) : 'modal-ready';
			$post_title = \get_the_title( \get_the_ID() );

			return sprintf( '<div id="%s"><h2 class="modal-title has-text-align-center">%s</h2>%s</div>', $div_id, $post_title, $content );

			// return '<div id="' . $div_id . '">' . $content . '</div>';
		}

		return false;
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
function wp_post_modal() {
	return Post_Modal::instance();
}
wp_post_modal();
