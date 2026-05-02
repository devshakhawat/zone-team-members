<?php
/**
 * Team members list template.
 *
 * @package TeamMembers
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Variables available from Shortcode.php:
 * $query - WP_Query instance
 * $atts  - Shortcode attributes
 * $show_all_button - boolean
 */
?>

<div class="team-members-container" data-image-position="<?php echo esc_attr( $atts['image_position'] ); ?>">
	<div class="team-members-grid">
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			$position  = get_post_meta( get_the_ID(), '_team_member_position', true );
			$bio       = get_post_meta( get_the_ID(), '_team_member_bio', true );
			$picture   = get_post_meta( get_the_ID(), '_team_member_picture', true );
			$image     = $picture ? wp_get_attachment_image( $picture, 'medium' ) : '';
			$permalink = get_permalink();
			?>
			<div class="team-member-item image-<?php echo esc_attr( $atts['image_position'] ); ?>">
				
				<?php if ( 'top' === $atts['image_position'] && $image ) : ?>
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

				<?php if ( 'bottom' === $atts['image_position'] && $image ) : ?>
					<div class="team-member-image">
						<a href="<?php echo esc_url( $permalink ); ?>">
							<?php echo wp_kses_post( $image ); ?>
						</a>
					</div>
				<?php endif; ?>

			</div>
		<?php endwhile; ?>
	</div>

	<?php if ( $show_all_button ) : ?>
		<div class="team-members-footer">
			<a href="#" class="button team-member-see-all" id="zteam-see-all">
				<?php esc_html_e( 'See All', 'teamzone' ); ?>
			</a>
		</div>
	<?php endif; ?>
</div>
