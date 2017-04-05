<?php
/**
 * Trestian WP Managers loader functionality.
 * This class is used to load the latest version of Trestian WP Managers. It is based off CMB2
 * which does a similar operation. This allows multiple plugins to include Trestian WP Managers
 * and ensure that they will all use the latest version available and only load the dependencies once.
 */

class Trestian_Loader_V104 {
	/**
	 * Current version number
	 *
	 * @var string
	 */
	const VERSION = '1.0.4';

	/**
	 * Path to library
	 * @var string
	 */
	protected $plugin_path;

	/**
	 * Trestian_Loader_V1 constructor.
	 *
	 * @param $root_file
	 */
	public function __construct($root_file) {
		$this->plugin_path = plugin_dir_path($root_file);
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

		 // Dice - Dependancy injection. Only load if not loaded elsewhere
		if(!class_exists('\\Dice\\Dice', false)) {
			require_once $this->plugin_path . 'libs/Dice.php';
		}
		// Kint
		require_once $this->plugin_path . 'libs/kint/Kint.class.php';
		// Interfaces
		require_once $this->plugin_path . 'interfaces/interface-trestian-page.php';
		require_once $this->plugin_path . 'interfaces/interface-trestian-options-manager.php';
		// Managers
		require_once $this->plugin_path . 'managers/class-trestian-ajax-manager.php';
		require_once $this->plugin_path . 'managers/class-trestian-page-manager.php';
		require_once $this->plugin_path . 'managers/class-trestian-template-manager.php';
		require_once $this->plugin_path . 'managers/class-trestian-acf-manager.php';
		require_once $this->plugin_path . 'managers/class-trestian-cmb2-manager.php';
		// Models
		require_once $this->plugin_path . 'models/class-trestian-page.php';
		require_once $this->plugin_path . 'models/class-trestian-page-container.php';
		require_once $this->plugin_path . 'models/class-trestian-plugin-settings.php';

		// Functions
		require_once $this->plugin_path . 'setup/trestian-functions.php';

		/**
		 * Let everyone know they can now proceed!
		 */
		do_action('trestian_wp_managers_loaded', $this->version);
	}
}