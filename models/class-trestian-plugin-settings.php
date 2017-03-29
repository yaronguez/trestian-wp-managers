<?php
/**
 * Created by PhpStorm.
 * User: yaronguez
 * Date: 3/22/17
 * Time: 4:47 PM
 */

class Trestian_Plugin_Settings {
	/**
	 * @var string
	 */
	protected $plugin_name;

	/**
	 * @var string
	 */
	protected $version;

	/**
	 * @var string
	 */
	protected $plugin_url;

	/**
	 * @var string
	 */
	protected $plugin_path;

	/**
	 * Hifoo_Trestian_Settings constructor.
	 *
	 * @param string $plugin_name
	 * @param string $version
	 * @param string $plugin_url
	 * @param string $plugin_path
	 */
	public function __construct( $plugin_name, $version, $plugin_url, $plugin_path ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
		$this->plugin_url  = $plugin_url;
		$this->plugin_path = $plugin_path;
	}


	/**
	 * @return mixed
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * @return mixed
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * @return mixed
	 */
	public function get_plugin_url() {
		return $this->plugin_url;
	}

	/**
	 * @return mixed
	 */
	public function get_plugin_path() {
		return $this->plugin_path;
	}

}