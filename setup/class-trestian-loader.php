<?php
/**
 * Trestian WP Managers loader functionality.
 * This class is used to load the latest version of Trestian WP Managers. It is based off CMB2
 * which does a similar operation. This allows multiple plugins to include Trestian WP Managers
 * and ensure that they will all use the latest version available and only load the dependencies once.
 */

class Trestian_Loader_V1013 {
	/**
	 * Current version number
	 *
	 * @var string
	 */
	const VERSION = '1.0.13';

	/**
	 * Path to library
	 * @var string
	 */
	protected $plugin_path;

	/**
	 * URL to library
	 * @var string
	 */
	protected $plugin_url;

	/**
	 * @param $root_file
	 */
	public function __construct($root_file) {
		$this->plugin_path = plugin_dir_path($root_file);
		$this->plugin_url = plugin_dir_url($root_file);
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
		require_once $this->plugin_path . 'models/class-trestian-constants.php';
		require_once $this->plugin_path . 'models/class-trestian-options.php';

		// Functions
		require_once $this->plugin_path . 'setup/trestian-functions.php';

		// Register scripts for loading as dependencies
		add_action('wp_enqueue_scripts', array($this, 'register_scripts'), 0);
		add_action('admin_enqueue_scripts', array($this, 'register_scripts'), 0);

		/**
		 * Let everyone know they can now proceed!
		 */
		do_action('trestian_wp_managers_loaded', self::VERSION);
	}

	/**
	 * Register scripts for loading as dependencies
	 */
	public function register_scripts(){
		wp_register_script('Trestian_WPM', $this->plugin_url . 'assets/js/trestian-wpm.js', array('jquery', 'jquery-form'), self::VERSION);
		wp_register_style('Trestian_WPM', $this->plugin_url . 'assets/css/trestian-wpm.css', self::VERSION);
	}
}