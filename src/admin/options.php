<?php
/**
 * Site Options Page
 *
 * @since   1.0.0
 * @package Core_Functionality
 */

namespace Site_Functionality\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Options {

	/**
	 * Instance of the class.
	 * @var object
	 */
	private static $instance;

	/**
	 * Class Instance.
	 * @return Options
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Options ) ) {
			self::$instance = new Options();

			\add_action( 'admin_menu', 				[ self::$instance, 'add_admin_menu' ] );
			\add_action( 'admin_init', 				[ self::$instance, 'init_settings'  ] );
			\add_action( 'admin_enqueue_scripts', 	[ self::$instance, 'admin_enqueue_scripts' ] );

		}
		return self::$instance;
	}

	/**
	 * Add Setting Fields
	 * 
	 * @link https://developer.wordpress.org/reference/functions/add_settings_field/
	 *
	 * @return void
	 */
	public function add_admin_menu() {

		add_options_page(
			esc_html__( 'Site Options', 'site-functionality' ),
			esc_html__( 'Site Options', 'site-functionality' ),
			'manage_options',
			'site-options',
			array( $this, 'page_layout' ),
			1
		);

	}

	/**
	 * Initialize
	 *
	 * @return void
	 */
	public function init_settings() {
		register_setting(
			'site_options',
			'site_options'
		);
		add_settings_section(
			'site_options_section',
			__( 'Term Trending Bar Settings', 'site-functionality' ),
			false,
			'site_options'
		);
		add_settings_field(
			'trending_days',
			__( 'Days', 'site-functionality' ),
			array( $this, 'render_trending_days_field' ),
			'site_options',
			'site_options_section'
		);
		// add_settings_field(
		// 	'trending_taxonomy',
		// 	__( 'Taxonomy', 'site-functionality' ),
		// 	array( $this, 'render_trending_taxonomy_field' ),
		// 	'site_options',
		// 	'site_options_section'
		// );
		add_settings_field(
			'trending_exclude',
			__( 'Exclude terms', 'site-functionality' ),
			array( $this, 'render_trending_exclude_field' ),
			'site_options',
			'site_options_section'
		);
		add_settings_field(
			'trending_number',
			__( 'Number', 'site-functionality' ),
			array( $this, 'render_trending_number_field' ),
			'site_options',
			'site_options_section'
		);


		/* Reading Settings */
		register_setting(
			'reading',
			'home-settings',
			array(
				'type'				=> 'integer',
				'display_in_rest'	=> true
			)
		);
		add_settings_section(
			'home-settings',
			__( 'Homepage Settings', 'site-functionality' ),
			false,
			'reading'
		);
		add_settings_field(
			'posts-per-page-home',
			__( 'Posts on Homepage', 'site-functionality' ),
			array( $this, 'render_posts_per_page_home_field' ),
			'reading',
			'home-settings'
		);

	}

	/**
	 * Enter admin settings page
	 *
	 * @return void
	 */
	public function page_layout() {

		// Check required user capability
		if ( !current_user_can( 'manage_options' ) )  {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'site-functionality' ) );
		}

		// Admin Page Layout
		echo '<div class="wrap">' . "\n";
		echo '	<h1>' . get_admin_page_title() . '</h1>' . "\n";
		echo '	<form action="options.php" method="post">' . "\n";

		settings_fields( 'site_options' );
		do_settings_sections( 'site_options' );
		submit_button();

		echo '	</form>' . "\n";
		echo '</div>' . "\n";

	}

	/**
	 * Sanitize setting
	 *
	 * @param int $input
	 * @return int $input
	 */
	function home_settings_sanitize( $input ) {
		return absint( $input );
	}

	/**
	 * Render section description
	 *
	 * @return void
	 */
	function render_home_section_description() {
		echo wpautop( esc_html__( 'Number of posts to display on homepage.', 'site-functionality' ) );
	}

	/**
	 * Render field
	 *
	 * @return void
	 */
	function render_posts_per_page_home_field() {
		$options = get_option( 'home-settings' );

		$value = isset( $options['posts-per-page-home'] ) ? $options['posts-per-page-home'] : 12;
		
		?>
		<label for="posts-per-page-home">
			<input id="posts-per-page-home" type="number" value="<?php echo intval( $value ); ?>" min="1" name="home-settings[posts-per-page-home]" class="small-text" /> <?php esc_attr_e( 'items', 'site-functionality' ); ?>
		</label>
		<?php
	}

	/**
	 * Render field
	 *
	 * @return void
	 */
	function render_trending_days_field() {
		$options = get_option( 'site_options' );

		$value = isset( $options['trending_days'] ) ? $options['trending_days'] : 7;
		
		?>
		<label for="trending-days">
			<input id="trending-days" type="number" value="<?php echo intval( $value ); ?>" min="1" name="site_options[trending_days]" class="small-text" /> <?php esc_attr_e( 'previous days', 'site-functionality' ); ?>
			<p><?php esc_html_e( 'Number of days from today to use.', 'site-functionality' ); ?></p>
		</label>
		<?php
	}

	/**
	 * Render Trending Taxonomy field
	 *
	 * @return void
	 */
	function render_trending_taxonomy_field() {
		$options = get_option( 'site_options' );

		$value = isset( $options['trending_taxonomy'] ) ? $options['trending_taxonomy'] : 'post_tag';
		?>

		<select name="site_options[trending_taxonomy]" class="trending_taxonomy_field">
			<option value="post_tag"<?php selected( $value, 'post_tag', false ); ?>> <?php esc_html_e( 'Tag', 'site-functionality' ); ?></option>
			<option value="category"<?php selected( $value, 'category', false ); ?>> <?php esc_html_e( 'Category', 'site-functionality' ); ?></option>
		</select>
		<p class="description"><?php esc_html_e( 'Taxonomy to use.', 'site-functionality' ); ?></p>
	<?php
	}

	/**
	 * Render Trending Exclude terms field
	 *
	 * @return void
	 */
	/**
	 function render_trending_exclude_field() {
		// Retrieve data from the database.
		$options = get_option( 'site_options' );

		// Set default value.
		$value = isset( $options['trending_exclude'] ) ? $options['trending_exclude'] : '';
		
		?>
		<label for="trending-exclude">
			<input id="trending-exclude" type="text" value="<?php echo esc_attr( $value ); ?>" min="1" name="site_options[trending_exclude]" class="medium-text" />
			<p><?php esc_html_e( 'Comma-separated list of terms to exclude.', 'site-functionality' ); ?></p>
		</label>
		<?php
	}
	*/

	/**
	 * Render Trending Exclude terms field
	 *
	 * @return void
	 */
	function render_trending_exclude_field() {
		$options = get_option( 'site_options' );

		$value = isset( $options['trending_exclude'] ) ? $options['trending_exclude'] : [];

		$taxonomy = isset( $options['trending_taxonomy'] ) ? $options['trending_taxonomy'] : 'post_tag';
		$args = array(
			'taxonomy'			=> $taxonomy,
			'orderby'			=> 'name',
			'fields'			=> 'id=>name'
		);
		$terms = get_terms( $args );

		if( !empty( $terms ) && !is_wp_error( $terms ) ) : ?>
			<label for="trending-exclude">
				<select name="site_options[trending_exclude][]" id="trending-exclude" class="select2" multiple>
					<option><?php esc_html_e( 'Select Terms', 'site-functionality' ); ?></option>

				<?php
					foreach( $terms as $id => $name ) : ?>

						<option value="<?php echo intval( $id ); ?>"<?php echo ( in_array( $id, $value ) ) ? ' selected' : '' ; ?>> <?php echo esc_html( $name ); ?></option>
					
					<?php
					endforeach; ?>

				</select>
				<p><?php esc_html_e( 'Select terms to exclude.', 'site-functionality' ); ?></p>
			</label>
		<?php
		endif;
		?>
			
		<?php
	}

	/**
	 * Render Trending Number field
	 *
	 * @return void
	 */
	function render_trending_number_field() {
		$options = get_option( 'site_options' );

		$value = isset( $options['trending_number'] ) ? $options['trending_number'] : 5;
		
		?>
		<label for="trending-number">
			<input id="trending-number" type="number" value="<?php echo intval( $value ); ?>" min="1" name="site_options[trending_number]" class="small-text" /> <?php esc_attr_e( 'terms', 'site-functionality' ); ?>
			<p><?php esc_html_e( 'Number of terms to use.', 'site-functionality' ); ?></p>
		</label>
		<?php
	}

	/**
	 * Load Select 2
	 *
	 * @return void
	 */
	function admin_enqueue_scripts() {

		if( !wp_script_is( 'select2', 'enqueued' ) ) {
			\wp_enqueue_style( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css' );
			\wp_enqueue_script( 'select2', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', array( 'jquery' ) );
		}

		\wp_enqueue_script( 'site-functionality-admin-scripts', SITE_CORE_DIR_URI . 'assets/js/options.js', array( 'select2' ) ); 
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
function options_page() {
	return Options::instance();
}
options_page();
