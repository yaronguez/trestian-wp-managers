<?php
/**
 * Trestian WP Managers loader functionality.
 * This class is used to load the latest version of Trestian WP Managers. It is based off CMB2
 * which does a similar operation. This allows multiple plugins to include Trestian WP Managers
 * and ensure that they will all use the latest version available and only load the dependencies once.
 */

class Trestian_Loader_V1 {
	/**
	 * Current version number
	 *
	 * @var string
	 */
	const VERSION = '1.0.0';

	/**
	 * Path to library
	 * @var string
	 */
	protected $path;

	/**
	 * Trestian_Loader_V1 constructor.
	 *
	 * @param $root_file
	 */
	public function __construct($root_file) {
		$this->path = plugin_dir_path($root_file);
	}

	/**
	 * Load all of the dependencies
	 */
	public function load(){

		/**
		 * If a newer version of Trestian Managers has already been loaded with a lower priority
		 * then go no further
		 */
		if ( defined( 'TRESTIAN_MANAGERS_VERSION' ) ) {
			return;
		}

		/**
		 * Let older versions as well as plugins or themes know that this has been loaded
		 */
		define( 'TRESTIAN_MANAGERS_VERSION', $this::VERSION);


		/**
		 * Load the files!
		 */
		require_once $this->path . 'interfaces/interface-trestian-page.php';
		require_once $this->path . 'managers/class-trestian-ajax-manager.php';
		require_once $this->path . 'managers/class-trestian-page-manager.php';
		require_once $this->path . 'managers/class-trestian-template-manager.php';
		require_once $this->path . 'models/class-trestian-page.php';
		require_once $this->path . 'models/class-trestian-page-container.php';
		require_once $this->path . 'models/class-trestian-plugin-settings.php';

		/**
		 * Let everyone know they can now proceed!
		 */
		do_action('trestian_wp_managers_loaded', $this->version);
	}
}