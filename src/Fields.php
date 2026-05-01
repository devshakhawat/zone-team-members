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
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Enqueue admin scripts for media uploader.
	 *
	 * @param string $hook The current admin page hook.
	 */
	public function enqueue_admin_scripts( $hook ) {
		global $post_type;

		if ( 'team_member' !== $post_type ) {
			return;
		}

		if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
			wp_enqueue_media();
			wp_enqueue_script(
				'team-member-admin',
				plugin_dir_url( dirname( __FILE__ ) ) . 'assets/js/admin.js',
				array( 'jquery' ),
				'1.0.0',
				true
			);
		}
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

		$full_name = get_post_meta( $post->ID, '_team_member_full_name', true );
		$position  = get_post_meta( $post->ID, '_team_member_position', true );
		$bio       = get_post_meta( $post->ID, '_team_member_bio', true );
		$picture   = get_post_meta( $post->ID, '_team_member_picture', true );
		$picture_url = $picture ? wp_get_attachment_url( $picture ) : '';

		?>
		<div class="team-member-field-group">
			<p>
				<label for="team_member_full_name"><?php esc_html_e( 'Full Name', 'teamzone' ); ?></label><br>
				<input type="text" id="team_member_full_name" name="team_member_full_name" value="<?php echo esc_attr( $full_name ); ?>" class="widefat">
			</p>
			<p>
				<label for="team_member_position"><?php esc_html_e( 'Position', 'teamzone' ); ?></label><br>
				<input type="text" id="team_member_position" name="team_member_position" value="<?php echo esc_attr( $position ); ?>" class="widefat">
			</p>
			<p>
				<label for="team_member_bio"><?php esc_html_e( 'Bio', 'teamzone' ); ?></label><br>
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
			</p>
			<p>
				<label><?php esc_html_e( 'Picture', 'teamzone' ); ?></label><br>
				<div class="team-member-image-preview" style="margin-bottom: 10px;">
					<img src="<?php echo esc_url( $picture_url ); ?>" style="max-width: 150px; display: <?php echo $picture ? 'block' : 'none'; ?>;" id="team_member_picture_preview">
				</div>
				<input type="hidden" name="team_member_picture" id="team_member_picture" value="<?php echo esc_attr( $picture ); ?>">
				<button type="button" class="button" id="team_member_picture_upload"><?php esc_html_e( 'Upload Image', 'teamzone' ); ?></button>
				<button type="button" class="button" id="team_member_picture_remove" style="display: <?php echo $picture ? 'inline-block' : 'none'; ?>;"><?php esc_html_e( 'Remove Image', 'teamzone' ); ?></button>
			</p>
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
		if ( isset( $_POST['team_member_full_name'] ) ) {
			update_post_meta( $post_id, '_team_member_full_name', sanitize_text_field( $_POST['team_member_full_name'] ) );
		}

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
