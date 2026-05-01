<?php 
/**
 * Register assets
 *
 * @package TeamMembers
 */

namespace Shakhawat\Team;

class Assets {

    /**
	 * PostType constructor.
	 */
	public function __construct() {
		
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_scripts' ) );
	}

    /**
	 * Enqueue admin scripts and styles.
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

			wp_enqueue_style(
				'team-member-admin',
				ZTEAM_PLUGIN_URI . '/assets/css/admin.css',
				array(),
				ZTEAM_VERSION
			);

			wp_enqueue_script(
				'team-member-admin',
				ZTEAM_PLUGIN_URI . '/assets/js/admin.js',
				array( 'jquery' ),
				ZTEAM_VERSION,
				true
			);
		}
	}

	/**
	 * Enqueue frontend scripts and styles.
	 */
	public function enqueue_frontend_scripts() {
		wp_enqueue_style(
			'team-member-frontend',
			ZTEAM_PLUGIN_URI . '/assets/css/frontend.css',
			array(),
			ZTEAM_VERSION
		);
	}

}