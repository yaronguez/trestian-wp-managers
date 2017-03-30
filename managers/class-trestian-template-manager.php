<?php

/**
 * Template loading functionality
 *
 * @since      1.0.0
 * @package    TrestianWPManagers
 * @subpackage TrestianWPManagers/managers
 * @author     Yaron Guez <yaron@trestian.com>
 */
class Trestian_Template_Manager {

	/**
	 * @var Trestian_Plugin_Settings
	 */
	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param $settings Trestian_Plugin_Settings
	 */
	public function __construct( Trestian_Plugin_Settings $settings) {
		$this->settings = $settings;
	}

	/**
	 * Gets the plugin file path
	 * @param $path
	 *
	 * @return string
	 */
	public function get_path($path){
		return $this->settings->get_plugin_path(). $path;
	}

	/**
	 * Load a template part while allowing theme and developers to override it
	 * Modeled off of WooCommerce
	 *
	 * @access public
	 * @param string $name (default: '')
	 */
	public function get_template_part( $slug, $name = '', $data=array()) {
		$template = '';

		// Look in yourtheme/slug-name.php
		if ( $name) {
			$template = locate_template( array( "{$slug}-{$name}.php") );
		}

		$template_location = trailingslashit(apply_filters('trestian_template_location', 'templates/public/'));

		// Look for plugin's slug-name.php
		if ( ! $template && $name && file_exists($this->get_path("{$template_location}{$slug}-{$name}.php" ) )) {
			$template = $this->get_path("{$template_location}{$slug}-{$name}.php");
		}

		// If template file doesn't exist, look in yourtheme/slug.php
		if ( ! $template) {
			$template = locate_template( array( "{$slug}.php") );
		}

		// Allow 3rd party plugins to filter template file from their plugin.
		$template = apply_filters( 'trestian_get_template_part', $template, $name );

		if ( !$template ) {
			return;
		}

		// Load globals to be accessible in template
		global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

		// Load any query variables to be accessible in template
		if ( is_array( $wp_query->query_vars ) ) {
			extract( $wp_query->query_vars, EXTR_SKIP );
		}

		// If a search variable was loaded from the query vars, escape its contents
		if ( isset( $s ) ) {
			$s = esc_attr( $s );
		}

		// Load any data passed in as array
		extract($data);

		// Expose manager to template
		$htm = $this;

		// Launch the template!
		require( $template );
	}

	/**
	 * Load a template including any data passed in along with an instance of the template manager
	 * @param $path
	 * @param array $data
	 */
	public function load_template($path, $data=array()){
		// Extract data to be available in template
		extract($data);

		// Expose the template manager to template
		$htm = $this;

		require($this->get_path($path));
	}

	public function messages($success = null, $error=null){
		$path = apply_filters('trestian_messages_template_path', 'templates/public/content-trestian-messages.php');
		$this->load_template($path, array(
			'success'=>$success,
			'error'=>$error
		));
	}

}
