<?php
/**
 * Single Team Member template.
 *
 * @package TeamMembers
 */

get_header();

while ( have_posts() ) :
	the_post();

	$position = get_post_meta( get_the_ID(), '_team_member_position', true );
	$bio      = get_post_meta( get_the_ID(), '_team_member_bio', true );
	$picture  = get_post_meta( get_the_ID(), '_team_member_picture', true );
	$image    = $picture ? wp_get_attachment_image( $picture, 'large' ) : '';
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'team-member-single' ); ?>>
		<div class="container">
			<header class="entry-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php if ( $position ) : ?>
					<div class="team-member-position">
						<strong><?php echo esc_html( $position ); ?></strong>
					</div>
				<?php endif; ?>
			</header>

			<div class="entry-content">
				<?php if ( $image ) : ?>
					<div class="team-member-picture">
						<?php echo wp_kses_post( $image ); ?>
					</div>
				<?php endif; ?>

				<div class="team-member-bio-full">
					<?php
					if ( ! empty( $bio ) ) {
						echo wp_kses_post( apply_filters( 'the_content', $bio ) );
					}
					?>
				</div>
			</div>

			<footer class="entry-footer">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'team_member' ) ); ?>" class="button">
					<?php esc_html_e( '&larr; Back to Team', 'teamzone' ); ?>
				</a>
			</footer>
		</div>
	</article>

	<?php
endwhile;

get_footer();
