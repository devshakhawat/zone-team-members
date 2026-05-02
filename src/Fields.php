<?php
/**
 * Custom fields for Team Member post type.
 *
 * @package TeamMembers
 */

namespace Shakhawat\Team;

/**
 * Class Fields
 *
 * Handles custom meta boxes for the 'team_member' post type.
 */
class Fields {

	/**
	 * Fields constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_team_member_meta_box' ) );
		add_action( 'save_post', array( $this, 'save_team_member_meta' ) );
	}

	/**
	 * Add Meta Box to 'team_member' post type.
	 */
	public function add_team_member_meta_box() {
		add_meta_box(
			'team_member_details',
			__( 'Team Member Details', 'teamzone' ),
			array( $this, 'render_team_member_meta_box' ),
			'team_member',
			'normal',
			'high'
		);
	}

	/**
	 * Render the Meta Box HTML.
	 *
	 * @param \WP_Post $post The post object.
	 */
	public function render_team_member_meta_box( $post ) {
		// Add a nonce field for security.
		wp_nonce_field( 'team_member_meta_box_nonce', 'team_member_meta_box_nonce_field' );

		$position  = get_post_meta( $post->ID, '_team_member_position', true );
		$bio       = get_post_meta( $post->ID, '_team_member_bio', true );
		$picture   = get_post_meta( $post->ID, '_team_member_picture', true );
		$picture_url = $picture ? wp_get_attachment_url( $picture ) : '';

		?>
		<div class="zteam-meta-box-wrap">
			<div class="zteam-meta-row">
				<div class="zteam-meta-label">
					<label for="team_member_position"><?php esc_html_e( 'Position', 'teamzone' ); ?></label>
					<p class="description"><?php esc_html_e( 'Enter the team member\'s job title.', 'teamzone' ); ?></p>
				</div>
				<div class="zteam-meta-field">
					<input type="text" id="team_member_position" name="team_member_position" value="<?php echo esc_attr( $position ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. Senior Developer', 'teamzone' ); ?>">
				</div>
			</div>

			<div class="zteam-meta-row">
				<div class="zteam-meta-label">
					<label for="team_member_bio"><?php esc_html_e( 'Biography', 'teamzone' ); ?></label>
					<p class="description"><?php esc_html_e( 'A short description about the team member.', 'teamzone' ); ?></p>
				</div>
				<div class="zteam-meta-field">
					<?php
					wp_editor(
						$bio,
						'team_member_bio',
						array(
							'textarea_name' => 'team_member_bio',
							'media_buttons' => false,
							'textarea_rows' => 5,
							'teeny'         => true,
						)
					);
					?>
				</div>
			</div>

			<div class="zteam-meta-row">
				<div class="zteam-meta-label">
					<label><?php esc_html_e( 'Profile Picture', 'teamzone' ); ?></label>
					<p class="description"><?php esc_html_e( 'Upload or select a profile image.', 'teamzone' ); ?></p>
				</div>
				<div class="zteam-meta-field">
					<div class="zteam-image-preview-wrapper <?php echo $picture ? 'has-image' : ''; ?>">
						<div class="zteam-image-preview">
							<img src="<?php echo esc_url( $picture_url ); ?>" id="team_member_picture_preview" style="<?php echo $picture ? '' : 'display: none;'; ?>">
							<span class="zteam-placeholder dashicons dashicons-admin-users" style="<?php echo $picture ? 'display: none;' : ''; ?>"></span>
						</div>
						<div class="zteam-image-actions">
							<input type="hidden" name="team_member_picture" id="team_member_picture" value="<?php echo esc_attr( $picture ); ?>">
							<button type="button" class="button" id="team_member_picture_upload">
								<span class="dashicons dashicons-upload"></span> <?php esc_html_e( 'Select Image', 'teamzone' ); ?>
							</button>
							<button type="button" class="button button-link-delete" id="team_member_picture_remove" style="<?php echo $picture ? '' : 'display: none;'; ?>">
								<?php esc_html_e( 'Remove', 'teamzone' ); ?>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Save Meta Box data.
	 *
	 * @param int $post_id The post ID.
	 */
	public function save_team_member_meta( $post_id ) {
		// Check if nonce is set.
		if ( ! isset( $_POST['team_member_meta_box_nonce_field'] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $_POST['team_member_meta_box_nonce_field'], 'team_member_meta_box_nonce' ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'team_member' === $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}
		}

		// Sanitize and save fields.
		if ( isset( $_POST['team_member_position'] ) ) {
			update_post_meta( $post_id, '_team_member_position', sanitize_text_field( $_POST['team_member_position'] ) );
		}

		if ( isset( $_POST['team_member_bio'] ) ) {
			update_post_meta( $post_id, '_team_member_bio', wp_kses_post( $_POST['team_member_bio'] ) );
		}

		if ( isset( $_POST['team_member_picture'] ) ) {
			update_post_meta( $post_id, '_team_member_picture', sanitize_text_field( $_POST['team_member_picture'] ) );
		}
	}
}
