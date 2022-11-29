<?php
/**
 * Content Filters
 *
 * @since   1.0.0
 * @package Site_Functionality
 */

namespace Site_Functionality;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Filters {

	/**
	 * Instance of the class.
	 * @var object
	 */
	private static $instance;


	/**
	 * Class Instance.
	 * @return Filters
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Filters ) ) {
			self::$instance = new Filters();

			self::$instance->actions();

			add_filter( 'acf/settings/save_json', 	array( self::$instance, 'acf_fields_location' ) );

			add_filter( 'the_excerpt_rss', 			array( self::$instance, 'post_thumbnails_in_feeds' ) );
			add_filter( 'the_content_feed', 		array( self::$instance, 'post_thumbnails_in_feeds' ) );
		}
		return self::$instance;
	}

	/**
	 * Add Actions
	 *
	 * @return void
	 */
	function actions() {}

	/**
	 * Set Path for ACF Synced Field Data
	 * 
	 * @link https://www.advancedcustomfields.com/resources/local-json/
	 *
	 * @param array $path
	 * @return array $path
	 */
	function acf_fields_location( $path ) {
		$path = SITE_CORE_DIR . '/data';
		return $path;
	}

	/**
	 * Add featured image to RSS
	 * 
	 * @link https://developer.wordpress.org/reference/hooks/the_excerpt_rss/
	 * @link https://developer.wordpress.org/reference/hooks/the_content_feed/
	 *
	 * @param string $content
	 * @return string $content
	 */
	function post_thumbnails_in_feeds( $content ) {
		global $post;
		if( has_post_thumbnail( $post->ID ) ) {
			$content = '<p>' . get_the_post_thumbnail( $post->ID ) . '</p>' . $content;
		}
		return $content;
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
function filters() {
	return Filters::instance();
}
filters();
