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
		<div class="container" style="max-width: 800px; margin: 40px auto; padding: 0 20px;">
			<header class="entry-header" style="text-align: center; margin-bottom: 30px;">
				<h1 class="entry-title"><?php the_title(); ?></h1>
				<?php if ( $position ) : ?>
					<div class="team-member-position" style="font-size: 1.2rem; color: #666; margin-top: 10px;">
						<strong><?php echo esc_html( $position ); ?></strong>
					</div>
				<?php endif; ?>
			</header>

			<div class="entry-content" style="display: flex; flex-direction: column; align-items: center; gap: 30px;">
				<?php if ( $image ) : ?>
					<div class="team-member-picture">
						<?php echo $image; ?>
					</div>
				<?php endif; ?>

				<div class="team-member-bio-full" style="line-height: 1.8; color: #333;">
					<?php echo wp_kses_post( $bio ); ?>
				</div>
			</div>

			<footer class="entry-footer" style="margin-top: 40px; text-align: center;">
				<a href="<?php echo esc_url( get_post_type_archive_link( 'team_member' ) ); ?>" class="button">
					<?php esc_html_e( '&larr; Back to Team', 'teamzone' ); ?>
				</a>
			</footer>
		</div>
	</article>

	<?php
endwhile;

get_footer();
