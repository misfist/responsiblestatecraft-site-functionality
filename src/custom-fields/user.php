<?php
/**
 * Custom Fields - User
 *
 * @since   0.1.6
 * @package Site_Functionality
 */

namespace Site_Functionality\CustomFields;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class User {

	/**
	 * Instance of the class.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Class Instance.
	 *
	 * @return User
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof User ) ) {
			self::$instance = new User();
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
		add_action( 'acf/include_fields', array( $this, 'register' ) );
	}

	/**
	 * Register Fields
	 *
	 * @link https://www.advancedcustomfields.com/resources/register-fields-via-php/
	 *
	 * @return void
	 */
	public function register() {
		acf_add_local_field_group(
			array(
				'key'                   => 'group_user_fields',
				'title'                 => __( 'Profile Fields', 'site-functionality' ),
				'fields'                => array(
					array(
						'key'               => 'field_5dab816ee2184',
						'label'             => __( 'Twitter Username', 'site-functionality' ),
						'name'              => 'twitter',
						'aria-label'        => '',
						'type'              => 'text',
						'instructions'      => '',
						'required'          => 0,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'placeholder'       => '',
						'prepend'           => 'twitterhandle',
						'append'            => '',
						'maxlength'         => '',
					),
					array(
						'key'                => 'field_5db795b3080bf',
						'label'              => __( 'Title', 'site-functionality' ),
						'name'               => 'expert_title',
						'aria-label'         => '',
						'type'               => 'text',
						'instructions'       => '',
						'required'           => 1,
						'conditional_logic'  => 0,
						'wrapper'            => array(
							'width' => '100',
							'class' => '',
							'id'    => '',
						),
						'relevanssi_exclude' => 0,
						'default_value'      => '',
						'maxlength'          => '',
						'placeholder'        => '',
						'prepend'            => '',
						'append'             => '',
					),
					array(
						'key'               => 'field_5dbde994078f1',
						'label'             => __( 'Extended Bio', 'site-functionality' ),
						'name'              => 'expert_extended_bio',
						'aria-label'        => '',
						'type'              => 'wysiwyg',
						'instructions'      => esc_html__( 'Extended biography text for Expert\'s Biography page', 'site-functionality' ),
						'required'          => 1,
						'conditional_logic' => 0,
						'wrapper'           => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'default_value'     => '',
						'tabs'              => 'all',
						'toolbar'           => 'full',
						'media_upload'      => 1,
						'delay'             => 0,
					),
					array(
						'key'                => 'field_private_profile',
						'label'              => __( 'Private Profile', 'site-functionality' ),
						'name'               => 'private_profile',
						'aria-label'         => '',
						'type'               => 'true_false',
						'instructions'       => esc_html__( 'Select to hide profile on website.', 'site-functionality' ),
						'required'           => 0,
						'conditional_logic'  => 0,
						'wrapper'            => array(
							'width' => '',
							'class' => '',
							'id'    => '',
						),
						'relevanssi_exclude' => 0,
						'message'            => '',
						'default_value'      => 0,
						'ui_on_text'         => 'Private',
						'ui_off_text'        => 'Public',
						'ui'                 => 1,
					),
				),
				'location'              => array(
					array(
						array(
							'param'    => 'user_role',
							'operator' => '==',
							'value'    => 'author',
						),
					),
				),
				'menu_order'            => 0,
				'position'              => 'normal',
				'style'                 => 'default',
				'label_placement'       => 'top',
				'instruction_placement' => 'label',
				'hide_on_screen'        => '',
				'active'                => true,
				'description'           => '',
				'show_in_rest'          => 1,
			)
		);
	}

}
