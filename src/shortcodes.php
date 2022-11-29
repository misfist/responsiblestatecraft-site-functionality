<?php
/**
 * Shortcodes
 *
 * @since   0.1.5
 * @package Site_Functionality
 */

namespace Site_Functionality;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Shortcodes {

	/**
	 * Instance of the class.
	 *
	 * @var object
	 */
	private static $instance;


	/**
	 * Class Instance.
	 *
	 * @return Shortcodes
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Shortcodes ) ) {
			self::$instance = new Shortcodes();

			add_shortcode( 'donor-direct-form', array( self::$instance, 'donor_direct' ) );
			add_shortcode( 'giving-tools-form', array( self::$instance, 'giving_tools' ), 10, 2 );

		}
		return self::$instance;
	}

	/**
	 * Donor Direct Shortcode
	 *
	 * @link https://developer.wordpress.org/reference/functions/add_shortcode/
	 *
	 * @param array $atts
	 * @return string $output
	 */
	function donor_direct( $atts ) {

		$atts = shortcode_atts(
			array(
				'settings' => '842285143_2011_83bd22cb-46f5-4ce3-a6d6-d0cf151780c8',
			),
			$atts,
			'donor-direct-form'
		);

		ob_start(); ?>
		
			<div class="donor-direct-form responsive-embed">
				<script type = "text/javascript">_dafdirect_settings="<?php echo esc_attr( $atts['settings'] ); ?>"</script>
				<script type = "text/javascript" src = "<?php echo esc_url( 'https://www.dafdirect.org/ddirect/dafdirect4.js' ); ?>"></script>
			</div>
		
		<?php
		$output = ob_get_clean();

		return $output;

	}

	/**
	 * GivingTools Shortcode
	 *
	 *  @link https://developer.wordpress.org/reference/functions/add_shortcode/
	 *
	 * @param array  $atts
	 * @param string $content
	 * @return string $output
	 */
	function giving_tools( $atts, $content = null ) {

		if ( ! $content ) {
			$content = 'Form secured by <a href="' . esc_url( 'https://givingtools.com' ) . '" target="_blank" rel="noopener nofollow">
			<em>GivingTools</em></a> online giving.';
		}

		$atts = shortcode_atts(
			array(
				'url' => 'https://givingtools.com/give/embed/939/3107',
			),
			$atts,
			'giving-tools-form'
		);

		$unique_id = uniqid( 'gtEmbedFrame_' );

		ob_start();
		?>
		
			<div class="giving-tools-form responsive-embed">
				<iframe id="<?php echo $unique_id; ?>" src="<?php echo esc_url( $atts['url'] . '?style-bg=transparent' ); ?>" style="border:0;width:100%;"></iframe>
			
				<footer class="form-footer">
					<?php echo $content; ?>
				</footer>
			
				<script 
					src="<?php echo esc_url( 'https://givingtools.com/iframeResizer.min.0580bafee10734d0dc52.js' ); ?>" 
					integrity="sha256-kjBnXrBNDceEMg278ZjsCUEJ8VrWP2Tp158N9u7Yhdk=" 
					crossorigin="anonymous" 
					onload="iFrameResize({}, '#<?php echo $unique_id; ?>');">
				</script>
			</div>
		
		
		<?php
		$output = ob_get_clean();

		return $output;

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
function shortcodes() {
	return Shortcodes::instance();
}
shortcodes();
