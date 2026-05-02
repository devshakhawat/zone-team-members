<?php
/**
 * Archive Team Member template.
 *
 * @package TeamMembers
 */

global $wp_query;

get_header(); ?>

<div class="team-member-archive-container">
	<header class="archive-header">
		<h1 class="archive-title"><?php post_type_archive_title(); ?></h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="team-members-container">
			<div class="team-members-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$position  = get_post_meta( get_the_ID(), '_team_member_position', true );
					$bio       = get_post_meta( get_the_ID(), '_team_member_bio', true );
					$picture   = get_post_meta( get_the_ID(), '_team_member_picture', true );
					$image     = $picture ? wp_get_attachment_image( $picture, 'medium' ) : '';
					$permalink = get_permalink();
					?>
					<div class="team-member-item image-top">
						
						<?php if ( $image ) : ?>
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

					</div>
				<?php endwhile; ?>
			</div>

			<div class="pagination">
				<?php
				echo wp_kses_post(
					paginate_links(
						array(
							'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
							'format'    => '?paged=%#%',
							'current'   => max( 1, get_query_var( 'paged' ) ),
							'total'     => $wp_query->max_num_pages,
							'prev_text' => '&laquo; ' . __( 'Prev', 'teamzone' ),
							'next_text' => __( 'Next', 'teamzone' ) . ' &raquo;',
						)
					)
				);
				?>
			</div>
		</div>
	<?php else : ?>
		<p><?php esc_html_e( 'No team members found.', 'teamzone' ); ?></p>
	<?php endif; ?>
</div>

<?php
get_footer();
