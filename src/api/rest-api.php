<?php
/**
 * Rest API Functions
 *
 * @since   1.0.0
 * @package Site_Functionality
 */
namespace Site_Functionality\API;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WP Rest API
 */
class Rest_API {

	/**
	 * Instance of the class.
	 * @var object
	 */
	private static $instance;

	/**
	 * Route endpoint
	 *
	 * @var string
	 */
	private $endpoint = 'responsible-statecraft';

	/**
	 * Route version
	 *
	 * @var string
	 */
	private $vers = 'v1';

	/**
	 * Class Instance.
	 * @return Rest_API
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Rest_API ) ) {
			self::$instance = new Rest_API();

			self::$instance->actions();

		}
		return self::$instance;
	}

	/**
	 * Add Actions
	 *
	 * @return void
	 */
	public function actions() {
		\add_action( 'rest_api_init', array( $this, 'register_thumbnail_field' ) );

		if ( function_exists( '\get_coauthors' ) ) {
			add_action( 'rest_api_init', array( $this, 'register_coauthors_field' ) );

			/**
			 * Add rest api filters
			 * @since 0.1.4
			 */
			\add_filter( 'rest_query_vars', 				[ $this, 'rest_query_vars' ] );
			\add_filter( 'rest_post_query', 				[ $this, 'rest_query_author' ], 10, 2 );
			\add_filter( 'rest_post_collection_params', 	[ $this, 'rest_collection_params' ], 10, 2 );
		}



		// \add_action( 'rest_api_init', array( $this, 'register_terms_field' ) );

		\add_action( 'rest_api_init', array( $this, 'register_main_tag_field' ) );
	}

	/**
	 * Register Field
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_rest_field/
	 *
	 * @return void
	 */
	public function register_thumbnail_field() {
		\register_rest_field(
			'post',
			'featured_image_src',
			array(
				'get_callback'    => array( $this, 'get_img_src' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);
	}

	/**
	 * Register Field
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_rest_field/
	 *
	 * @return void
	 */
	function register_coauthors_field() {
		\register_rest_field( 'post',
			'coauthors',
			array(
				'get_callback'    => array( $this, 'get_coauthors' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);
	}

	/**
	 * Register Field
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_rest_field/
	 *
	 * @return void
	 */
	public function register_terms_field() {
		\register_rest_field( 'post',
			'terms',
			array(
				'get_callback'    => array( $this, 'get_terms' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);
	}

	/**
	 * Register Field
	 *
	 * @link https://developer.wordpress.org/reference/functions/register_rest_field/
	 *
	 * @return void
	 */
	public function register_main_tag_field() {
		\register_rest_field( 'post',
			'main_tag',
			array(
				'get_callback'    => array( $this, 'get_main_tag' ),
				'update_callback' => null,
				'schema'          => null,
			)
		);
	}

	/**
	 * Get the image source
	 *
	 * @link https://developer.wordpress.org/reference/functions/wp_get_attachment_image_src/
	 *
	 * @param obj $object
	 * @param string $field_name
	 * @param obj $request
	 * @return string $featured_img
	 */
	public function get_img_src( $object, $field_name, $request ) {
		$featured_img = \wp_get_attachment_image_src(
			$object['featured_media'],
			'medium',
			true
		);
		return $featured_img[0];
	}

	/**
	 * Get the image source
	 *
	 * @link https://github.com/Automattic/Co-Authors-Plus/blob/master/template-tags.php
	 *
	 * @param obj $object
	 * @param string $field_name
	 * @param obj $request
	 * @return string $featured_img
	 */
	public function get_coauthors( $object, $field_name, $request ) {
		$coauthors = \get_coauthors( $object['id'] );

		$authors = array();
		foreach ( $coauthors as $author ) {
			$authors[] = array(
				'id'	=> $author->ID,
				'name' 	=> $author->display_name,
				'link'	=> \get_author_posts_url( $author->ID )
			);
		};

		return $authors;
	}

	/**
	 * Add Query Vars
	 *
	 * @since 0.1.4
	 *
	 * @param array $vars
	 * @return void
	 */
	public function rest_query_vars( $vars ) {
		$vars = array_merge( $vars, [ 'author_email' ] );
		return $vars;
	}

	/**
	 * Add Author Params
	 * Enables adding extra arguments or setting defaults for a post collection request.
	 *
	 * @since 0.1.4
	 *
	 * @link https://developer.wordpress.org/reference/hooks/rest_this-post_type_query/
	 * @link https://wordpress.org/support/topic/query-all-posts-of-author-even-if-he-she-is-co-author/
	 *
	 * @param array $params
	 * @param object $request
	 * @return array
	 */
	public function rest_query_author( $params, $request ) : array {
		if ( isset( $request['author_email'] ) ) {

			if ( $request['author_email'] ) {
				$user = \get_user_by( 'email', $request['author_email'] );
				$params['author_name'] = isset( $user->user_nicename ) ? $user->user_nicename : false;
			}

		}
		return $params;
	}

	/**
	 * Register Author Params
	 * Registers the collection parameter, but does not map the collection parameter to an internal WP_Query parameter
	 *
	 * @since 0.1.4
	 *
	 * @link https://developer.wordpress.org/reference/hooks/rest_this-post_type_collection_params/
	 *
	 * @param array $params
	 * @param object $post_type
	 * @return array
	 */
	public function rest_collection_params( $params, $post_type ) : array {
		$params['author_email'] = [
			'description' => __( 'Limit posts to author associated with email.', 'site-functionality' ),
			'type'        => 'string'
		];
		return $params;
	}

	/**
	 * Get Terms
	 *
	 * @param obj $object
	 * @param string $field_name
	 * @param obj $request
	 * @return array $terms
	 */
	public function get_terms( $object, $field_name, $request ) {
		$terms = [];

		$fields = 'all';

		$categories = \wp_get_post_terms( $object['id'], 'category', $fields );
		$tags = \wp_get_post_terms( $object['id'], 'post_tag', $fields );

		$terms['category'] = $categories;
		$terms['tag'] = $tags;

		return $terms;
	}

	/**
	 * Get Main Tag
	 *
	 * @param [type] $object
	 * @param [type] $field_name
	 * @param [type] $request
	 * @return void
	 */
	public function get_main_tag( $object, $field_name, $request ) {
		$post_id = $object['id'];
		$taxonomy = 'post_tag';
		$tag = [];
		if ( $main_tag = \get_post_meta( $post_id, 'primary_tag', true ) ) {
			$tag = get_tag( (int) $main_tag );

		} elseif ( $main_tag = \get_post_meta( $post_id, 'main_tag', true ) ) {
			$tag_slug = sanitize_title( $main_tag );
			$tag = \get_term_by( 'slug', $tag_slug, $taxonomy );
		} elseif ( $tags = \wp_get_post_terms( $post_id, $taxonomy ) ) {
			$tag = $tags[0];
		}
		if ( ! empty( $tag ) ) {
			$tag->link = \get_term_link( $tag, $taxonomy );
		}
		return $tag;
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
function rest_api_init() {
	return Rest_API::instance();
}
rest_api_init();
