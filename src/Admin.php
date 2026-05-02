<?php
/**
 * Admin logic for Dummy Data management.
 *
 * @package TeamMembers
 */

namespace Shakhawat\Team;

/**
 * Class Admin
 *
 * Handles admin menu and dummy data import/removal.
 */
class Admin {

	/**
	 * Admin constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
	}

	/**
	 * Add submenu page under 'team_member' post type.
	 */
	public function add_menu_pages() {
		add_submenu_page(
			'edit.php?post_type=team_member',
			__( 'Dummy Data', 'teamzone' ),
			__( 'Dummy Data', 'teamzone' ),
			'manage_options',
			'team-dummy-data',
			array( $this, 'render_dummy_data_page' )
		);
	}

	/**
	 * Render the Dummy Data management page.
	 */
	public function render_dummy_data_page() {
		if ( isset( $_POST['team_action'] ) && check_admin_referer( 'team_dummy_data_action', 'team_dummy_data_nonce' ) ) {
			if ( 'import' === $_POST['team_action'] ) {
				$this->import_dummy_data();
				echo '<div class="updated"><p>' . esc_html__( 'Dummy data imported successfully.', 'teamzone' ) . '</p></div>';
			} elseif ( 'remove' === $_POST['team_action'] ) {
				$this->remove_dummy_data();
				echo '<div class="updated"><p>' . esc_html__( 'All dummy data removed successfully.', 'teamzone' ) . '</p></div>';
			}
		}

		$is_imported = $this->is_dummy_data_imported();

		?>
		<div class="wrap">
			<div class="zteam-dummy-data-wrap">
				<h1><?php esc_html_e( 'Install Demo Data', 'teamzone' ); ?></h1>
				<p class="subtitle"><?php esc_html_e( 'Quick start with Zone7 Plugins by installing the demo data', 'teamzone' ); ?></p>

				<h2><?php esc_html_e( 'Import All Data', 'teamzone' ); ?></h2>
				<p class="import-info"><?php esc_html_e( 'Following data will get imported:', 'teamzone' ); ?></p>

				<ul>
					<li><?php esc_html_e( '12 Team Members', 'teamzone' ); ?></li>
					<li><?php esc_html_e( '12 Attachments for Team Members', 'teamzone' ); ?></li>
				</ul>

				<div class="zteam-dummy-data-actions">
					<form method="post" action="">
						<?php wp_nonce_field( 'team_dummy_data_action', 'team_dummy_data_nonce' ); ?>
						<input type="hidden" name="team_action" value="import">
						<button type="submit" class="button button-import" <?php disabled( $is_imported ); ?>>
							<span class="dashicons <?php echo $is_imported ? 'dashicons-cloud-saved' : 'dashicons-cloud-upload'; ?>"></span>
							<?php echo $is_imported ? esc_html__( 'ALREADY IMPORTED', 'teamzone' ) : esc_html__( 'IMPORT DATA', 'teamzone' ); ?>
						</button>
					</form>

					<form method="post" action="">
						<?php wp_nonce_field( 'team_dummy_data_action', 'team_dummy_data_nonce' ); ?>
						<input type="hidden" name="team_action" value="remove">
						<button type="submit" class="button button-remove" onclick="return confirm('<?php esc_attr_e( 'Are you sure you want to remove all dummy team members?', 'teamzone' ); ?>');">
							<span class="dashicons dashicons-trash"></span>
							<?php esc_html_e( 'REMOVE DATA', 'teamzone' ); ?>
						</button>
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Check if dummy data has already been imported.
	 *
	 * @return bool
	 */
	private function is_dummy_data_imported() {
		$posts = get_posts(
			array(
				'post_type'      => 'team_member',
				'posts_per_page' => 1,
				'meta_query'     => array(
					array(
						'key'   => '_is_dummy_data',
						'value' => 'yes',
					),
				),
			)
		);

		return ! empty( $posts );
	}

	/**
	 * Import dummy team members.
	 */
	private function import_dummy_data() {
		$images_dir = ZTEAM_PLUGIN_DIR . 'assets/images/';
		$images     = array(
			'zteam-member-1.jpg'  => array( 'Name' => 'John Doe', 'Position' => 'CEO', 'Bio' => 'John is the CEO of Zone7.' ),
			'zteam-member-2.jpg'  => array( 'Name' => 'Jane Smith', 'Position' => 'CTO', 'Bio' => 'Jane leads the technical team.' ),
			'zteam-member-3.jpg'  => array( 'Name' => 'Michael Brown', 'Position' => 'Lead Developer', 'Bio' => 'Michael is an expert in WordPress.' ),
			'zteam-member-4.jpg'  => array( 'Name' => 'Emily Davis', 'Position' => 'UI/UX Designer', 'Bio' => 'Emily creates beautiful designs.' ),
			'zteam-member-5.jpg'  => array( 'Name' => 'Robert Wilson', 'Position' => 'Marketing Manager', 'Bio' => 'Robert handles all marketing efforts.' ),
			'zteam-member-6.jpg'  => array( 'Name' => 'Sarah Johnson', 'Position' => 'Project Manager', 'Bio' => 'Sarah keeps everything on track.' ),
			'zteam-member-7.jpg'  => array( 'Name' => 'William Jones', 'Position' => 'Frontend Developer', 'Bio' => 'William loves React and Vue.' ),
			'zteam-member-8.jpg'  => array( 'Name' => 'Jessica Taylor', 'Position' => 'Backend Developer', 'Bio' => 'Jessica is a PHP wizard.' ),
			'zteam-member-9.jpg'  => array( 'Name' => 'David Miller', 'Position' => 'SEO Specialist', 'Bio' => 'David optimizes our web presence.' ),
			'zteam-member-10.jpg' => array( 'Name' => 'Linda Moore', 'Position' => 'Content Writer', 'Bio' => 'Linda writes engaging content.' ),
			'zteam-member-11.jpg' => array( 'Name' => 'Richard Anderson', 'Position' => 'QA Engineer', 'Bio' => 'Richard ensures high quality.' ),
			'zteam-member-12.jpg' => array( 'Name' => 'Karen Thomas', 'Position' => 'HR Manager', 'Bio' => 'Karen takes care of our team.' ),
		);

		foreach ( $images as $filename => $data ) {
			$file_path = $images_dir . $filename;

			if ( ! file_exists( $file_path ) ) {
				continue;
			}

			// Create post.
			$post_id = wp_insert_post(
				array(
					'post_title'  => $data['Name'],
					'post_type'   => 'team_member',
					'post_status' => 'publish',
					'meta_input'  => array(
						'_team_member_position' => $data['Position'],
						'_team_member_bio'      => $data['Bio'],
						'_is_dummy_data'        => 'yes',
					),
				)
			);

			if ( is_wp_error( $post_id ) ) {
				continue;
			}

			// Upload image and attach to post.
			$attachment_id = $this->upload_image_from_path( $file_path, $post_id );
			if ( $attachment_id ) {
				update_post_meta( $post_id, '_team_member_picture', $attachment_id );
				update_post_meta( $attachment_id, '_is_dummy_data', 'yes' );
			}
		}
	}

	/**
	 * Upload an image from a local path to the Media Library.
	 *
	 * @param string $file_path Absolute path to the file.
	 * @param int    $post_id   Post ID to attach to.
	 * @return int|false Attachment ID or false on failure.
	 */
	private function upload_image_from_path( $file_path, $post_id ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/media.php';

		$filename = basename( $file_path );
		$upload   = wp_upload_bits( $filename, null, file_get_contents( $file_path ) );

		if ( isset( $upload['error'] ) && $upload['error'] ) {
			return false;
		}

		$wp_filetype = wp_check_filetype( $filename, null );
		$attachment  = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name( $filename ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		);

		$attachment_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );

		if ( ! is_wp_error( $attachment_id ) ) {
			$attachment_data = wp_generate_attachment_metadata( $attachment_id, $upload['file'] );
			wp_update_attachment_metadata( $attachment_id, $attachment_data );
			return $attachment_id;
		}

		return false;
	}

	/**
	 * Remove all dummy team members and their associated dummy attachments.
	 */
	private function remove_dummy_data() {
		$posts = get_posts(
			array(
				'post_type'      => 'team_member',
				'posts_per_page' => -1,
				'meta_query'     => array(
					array(
						'key'   => '_is_dummy_data',
						'value' => 'yes',
					),
				),
			)
		);

		foreach ( $posts as $post ) {
			wp_delete_post( $post->ID, true );
		}

		// Also remove dummy attachments.
		$attachments = get_posts(
			array(
				'post_type'      => 'attachment',
				'posts_per_page' => -1,
				'post_status'    => 'any',
				'meta_query'     => array(
					array(
						'key'   => '_is_dummy_data',
						'value' => 'yes',
					),
				),
			)
		);

		foreach ( $attachments as $attachment ) {
			wp_delete_attachment( $attachment->ID, true );
		}
	}
}
