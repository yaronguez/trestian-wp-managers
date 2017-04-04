<?php
/**
 *
 * @link              http://trestian.com
 * @since             1.0.0
 * @package           TrestianWPManagers
 *
 * @wordpress-plugin
 * Plugin Name:         Trestian WP Managers
 * Plugin URI:          https://github.com/yaronguez/trestian-wp-managers
 * Description:         Set of shared libraries and tools for plugin development
 * Version:             1.0.3
 * Author:              Yaron Guez
 * Author URI:          http://trestian.com
 * License:             GPL-2.0+
 * License URI:         http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:         twpm
 * Domain Path:         /languages
 * GitHub Plugin URI:   https://github.com/yaronguez/trestian-wp-managers
*  GitHub Branch:       master
 */

/**
 * Ensure that this version of Trestian WP Managers has not already been loaded
 */
// plugin
if ( class_exists( 'Trestian_Loader_V103', false ) ) {
	return;
}

/** Require the loader file for this version Trestian WP Managers. */
require plugin_dir_path( __FILE__ ) . 'setup/class-trestian-loader.php';


/**
 * Execute the plugin on plugins_loaded with a priority that decrements with
 * each release so the latest release loads first
 */
$priority = 9998;
$trestian_loader = new Trestian_Loader_V103(__FILE__);
add_action( 'plugins_loaded', array($trestian_loader, 'load'), $priority );



