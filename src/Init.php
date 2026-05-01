<?php
/**
 * Plugin initialization file.
 *
 * @package TeamMembers
 */

namespace Shakhawat\Team;

/**
 * Class Init
 *
 * Handles plugin initialization.
 */
class Init {

	/**
	 * Instance of this class.
	 *
	 * @var Init
	 */
	public static $instance = null;

	/**
	 * PostType instance.
	 *
	 * @var PostType
	 */
	public $posttype;

	/**
	 * Get the instance of this class.
	 *
	 * @return Init
	 */
	public static function get_instance() {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Init constructor.
	 */
	public function __construct() {

		$this->posttype = new PostType();
		new Fields();
		new Assets();
	}
}
