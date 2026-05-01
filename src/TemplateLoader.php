<?php
/**
 * Template loader for the plugin.
 *
 * @package TeamMembers
 */

namespace Shakhawat\Team;

/**
 * Class TemplateLoader
 *
 * Handles loading of plugin-specific templates.
 */
class TemplateLoader {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'single_template', array( $this, 'load_single_template' ) );
		add_filter( 'archive_template', array( $this, 'load_archive_template' ) );
	}

	/**
	 * Load single team member template from plugin if it exists.
	 *
	 * @param string $template Current template path.
	 * @return string Modified template path.
	 */
	public function load_single_template( $template ) {
		global $post;

		if ( is_singular( 'team_member' ) ) {
			$plugin_template = ZTEAM_PLUGIN_DIR . 'templates/single-team_member.php';
			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}

		return $template;
	}

	/**
	 * Load archive team member template from plugin if it exists.
	 *
	 * @param string $template Current template path.
	 * @return string Modified template path.
	 */
	public function load_archive_template( $template ) {
		if ( is_post_type_archive( 'team_member' ) ) {
			$plugin_template = ZTEAM_PLUGIN_DIR . 'templates/archive-team-member.php';
			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}

		return $template;
	}
}
