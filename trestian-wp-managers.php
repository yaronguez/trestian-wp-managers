<?php
/**
 * Created by PhpStorm.
 * User: yaronguez
 * Date: 3/28/17
 * Time: 4:07 PM
 */

/**
 * Ensure that this version of Trestian WP Managers has not already been loaded
 */
if ( class_exists( 'Trestian_Loader_V1', false ) ) {
	return;
}

/** Require the loader file for this version Trestian WP Managers. */
require plugin_dir_path( __FILE__ ) . 'setup/class-trestian-loader.php';

/**
 * Decrement priority with each release so it loads before older versions
 */
$priority = 9999;
$trestian_loader = new Trestian_Loader_V1(__FILE__);

/**
 * Load managers!
 */
add_action( 'plugins_loaded', array( $trestian_loader, 'load' ), $priority );

