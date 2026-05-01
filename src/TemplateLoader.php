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
	}

	/**
	 * Load single team member template from plugin if it exists.
	 *
	 * @param string $template Current template path.
	 * @return string Modified template path.
	 */
	public function load_single_template( $template ) {
		global $post;

		if ( 'team_member' === $post->post_type ) {
			$plugin_template = ZTEAM_PLUGIN_DIR . 'templates/single-team-member.php';
			if ( file_exists( $plugin_template ) ) {
				return $plugin_template;
			}
		}

		return $template;
	}
}
