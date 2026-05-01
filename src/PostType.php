<?php
/**
 * PostType registration file.
 *
 * @package TeamMembers
 */

namespace Shakhawat\Team;

/**
 * Class PostType
 *
 * Handles custom post type registration.
 */
class PostType {

	/**
	 * PostType constructor.
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_team_member_post_type' ) );
	}

	/**
	 * Register the 'team_member' custom post type.
	 */
	public function register_team_member_post_type() {
		$labels = array(
			'name'               => _x( 'Team Members', 'post type general name', 'teamzone' ),
			'singular_name'      => _x( 'Team Member', 'post type singular name', 'teamzone' ),
			'menu_name'          => _x( 'Team Members', 'admin menu', 'teamzone' ),
			'name_admin_bar'     => _x( 'Team Member', 'add new on admin bar', 'teamzone' ),
			'add_new'            => _x( 'Add New', 'team member', 'teamzone' ),
			'add_new_item'       => __( 'Add New Team Member', 'teamzone' ),
			'new_item'           => __( 'New Team Member', 'teamzone' ),
			'edit_item'          => __( 'Edit Team Member', 'teamzone' ),
			'view_item'          => __( 'View Team Member', 'teamzone' ),
			'all_items'          => __( 'All Team Members', 'teamzone' ),
			'search_items'       => __( 'Search Team Members', 'teamzone' ),
			'parent_item_colon'  => __( 'Parent Team Members:', 'teamzone' ),
			'not_found'          => __( 'No team members found.', 'teamzone' ),
			'not_found_in_trash' => __( 'No team members found in Trash.', 'teamzone' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'team-member' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'menu_icon'          => 'dashicons-groups',
			'supports'           => array( 'title' ),
			'show_in_rest'       => false,
		);

		register_post_type( 'team_member', $args );
	}
}
