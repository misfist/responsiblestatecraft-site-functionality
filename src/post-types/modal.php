<?php
/**
 * Custom Post Type
 *
 * @since   0.1.3
 * @package Site_Functionality
 */

namespace Site_Functionality\Post_Types;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Modal {

    /**
	 * Instance of the class.
	 * @var object
	 */
	private static $instance;

	/**
	 * Taxonomy data
	 */
	public const POST_TYPE = [
		'id'       		=> 'modal',
		'archive'  		=> false,
		'menu'    		=> 'Modals',
		'title'    		=> 'Modals',
		'singular' 		=> 'Modal',
		'icon'     		=> 'dashicons-testimonial',
        'menu_position' => 20,
		'taxonomies'	=> [],
	];

	/**
	 * Class Instance.
	 * @return Modal
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Modal ) ) {
            self::$instance = new Modal();
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
		add_action( 'init', [ $this, 'register' ] );
	}
	/**
	 * Register post type
	 *
	 * @since 0.1.3
	 */
	public function register() {
		$labels = array(
			'name'                  => _x( $this::POST_TYPE['title'], 'Post Type General Name', 'wp-action-network-events' ),
			'singular_name'         => _x( $this::POST_TYPE['singular'], 'Post Type Singular Name', 'wp-action-network-events' ),
			'menu_name'             => __( $this::POST_TYPE['menu'], 'wp-action-network-events' ),
			'name_admin_bar'        => __( $this::POST_TYPE['singular'], 'wp-action-network-events' ),

			'add_new'        => sprintf( /* translators: %s: post type singular title */ __( 'New %s', 'wp-action-network-events' ), $this::POST_TYPE['singular'] ),
			'add_new_item'   => sprintf( /* translators: %s: post type singular title */ __( 'Add New %s', 'wp-action-network-events' ), $this::POST_TYPE['singular'] ),
			'new_item'       => sprintf( /* translators: %s: post type singular title */ __( 'New %s', 'wp-action-network-events' ), $this::POST_TYPE['singular'] ),
			'edit_item'      => sprintf( /* translators: %s: post type singular title */ __( 'Edit %s', 'wp-action-network-events' ), $this::POST_TYPE['singular'] ),
			'view_item'      => sprintf( /* translators: %s: post type singular title */ __( 'View %s', 'wp-action-network-events' ), $this::POST_TYPE['singular'] ),
			'view_items'     => sprintf( /* translators: %s: post type title */ __( 'View %s', 'wp-action-network-events' ), $this::POST_TYPE['title'] ),
			'all_items'      => sprintf( /* translators: %s: post type title */ __( 'All %s', 'wp-action-network-events' ), $this::POST_TYPE['title'] ),
			'search_items'   => sprintf( /* translators: %s: post type title */ __( 'Search %s', 'wp-action-network-events' ), $this::POST_TYPE['title'] ),


			'archives'              => sprintf( /* translators: %s: post type title */ __( '%s Archives', 'wp-action-network-events' ), $this::POST_TYPE['singular'] ),
			'attributes'            => sprintf( /* translators: %s: post type title */ __( '%s Attributes', 'wp-action-network-events' ), $this::POST_TYPE['singular'] ),
			'parent_item_colon'     => sprintf( /* translators: %s: post type title */ __( 'Parent %s:', 'wp-action-network-events' ), $this::POST_TYPE['singular'] ),
			'update_item'           => sprintf( /* translators: %s: post type title */ __( 'Update %s', 'wp-action-network-events' ), $this::POST_TYPE['singular'] ),
			'items_list'            => sprintf( /* translators: %s: post type singular title */ __( '%s List', 'wp-action-network-events' ), $this::POST_TYPE['title'] ),
			'items_list_navigation' => sprintf( /* translators: %s: post type singular title */ __( '%s list navigation', 'wp-action-network-events' ), $this::POST_TYPE['title'] ),

			'insert_into_item'      => sprintf( /* translators: %s: post type title */ __( 'Insert into %s', 'wp-action-network-events' ), strtolower( $this::POST_TYPE['singular'] ) ),
			'uploaded_to_this_item' => sprintf( /* translators: %s: post type title */ __( 'Uploaded to this %s', 'wp-action-network-events' ), strtolower( $this::POST_TYPE['singular'] ) ),
			'filter_items_list'     => sprintf( /* translators: %s: post type title */ __( 'Filter %s list', 'wp-action-network-events' ), strtolower( $this::POST_TYPE['title'] ) ),
			'featured_image'        => __( 'Featured Image', 'wp-action-network-events' ),
		);
		$args = array(
			'label'                 => $this::POST_TYPE['title'],
			'labels'                => $labels,
			'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
			'taxonomies'            => $this::POST_TYPE['taxonomies'],
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_icon'          	=> $this::POST_TYPE['icon'],
			'menu_position'         => $this::POST_TYPE['menu_position'],
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'        	=> $this::POST_TYPE['archive'],
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'post',
			'show_in_rest'          => true,
			'rest_base'             => $this::POST_TYPE['archive'],
		);
		\register_post_type( 
			$this::POST_TYPE['id'], 
			\apply_filters( \get_class() . '\Args', $args )
		);
	
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
function modal() {
	return Modal::instance();
}
modal();
