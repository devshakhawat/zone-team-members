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
		add_action( 'wp_ajax_zteam_load_all_members', array( $this, 'ajax_load_all_members' ) );
		add_action( 'wp_ajax_nopriv_zteam_load_all_members', array( $this, 'ajax_load_all_members' ) );
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
				'number'          => 8,
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

	/**
	 * AJAX handler for loading all members.
	 */
	public function ajax_load_all_members() {
		check_ajax_referer( 'zteam_see_all_nonce', 'nonce' );

		$image_position = isset( $_POST['image_position'] ) ? sanitize_text_field( wp_unslash( $_POST['image_position'] ) ) : 'top';

		$args = array(
			'post_type'      => 'team_member',
			'posts_per_page' => -1,
			'status'         => 'publish',
		);

		$query = new \WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$position  = get_post_meta( get_the_ID(), '_team_member_position', true );
				$bio       = get_post_meta( get_the_ID(), '_team_member_bio', true );
				$picture   = get_post_meta( get_the_ID(), '_team_member_picture', true );
				$image     = $picture ? wp_get_attachment_image( $picture, 'medium' ) : '';
				$permalink = get_permalink();
				?>
				<div class="team-member-item image-<?php echo esc_attr( $image_position ); ?>">
					
					<?php if ( 'top' === $image_position && $image ) : ?>
						<div class="team-member-image">
							<a href="<?php echo esc_url( $permalink ); ?>">
								<?php echo wp_kses_post( $image ); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="team-member-details">
						<h3 class="team-member-name">
							<a href="<?php echo esc_url( $permalink ); ?>"><?php the_title(); ?></a>
						</h3>
						<?php if ( $position ) : ?>
							<p class="team-member-position"><strong><?php echo esc_html( $position ); ?></strong></p>
						<?php endif; ?>
						<?php if ( $bio ) : ?>
							<div class="team-member-bio">
								<?php echo wp_kses_post( wp_trim_words( $bio, 20 ) ); ?>
							</div>
						<?php endif; ?>
					</div>

					<?php if ( 'bottom' === $image_position && $image ) : ?>
						<div class="team-member-image">
							<a href="<?php echo esc_url( $permalink ); ?>">
								<?php echo wp_kses_post( $image ); ?>
							</a>
						</div>
					<?php endif; ?>

				</div>
				<?php
			}
		}

		wp_reset_postdata();
		die();
	}
}
