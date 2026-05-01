<?php
/**
 * Shortcode implementation.
 *
 * @package TeamMembers
 */

namespace Shakhawat\Team;

/**
 * Class Shortcode
 *
 * Handles the [team_members] shortcode.
 */
class Shortcode {

	/**
	 * Shortcode constructor.
	 */
	public function __construct() {
		add_shortcode( 'team_members', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Render the [team_members] shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string Shortcode output.
	 */
	public function render_shortcode( $atts ) {
		$atts = shortcode_atts(
			array(
				'number'          => 3,
				'image_position'  => 'top',
				'show_all_button' => 'true',
			),
			$atts,
			'team_members'
		);

		// Convert string boolean to actual boolean.
		$show_all_button = filter_var( $atts['show_all_button'], FILTER_VALIDATE_BOOLEAN );

		$args = array(
			'post_type'      => 'team_member',
			'posts_per_page' => (int) $atts['number'],
			'status'         => 'publish',
		);

		$query = new \WP_Query( $args );

		if ( ! $query->have_posts() ) {
			return '';
		}

		ob_start();
		
		$template_path = ZTEAM_PLUGIN_DIR . 'templates/team-members-list.php';
		
		if ( file_exists( $template_path ) ) {
			include $template_path;
		} else {
			echo '<p>' . esc_html__( 'Template not found.', 'teamzone' ) . '</p>';
		}

		wp_reset_postdata();

		return ob_get_clean();
	}
}
