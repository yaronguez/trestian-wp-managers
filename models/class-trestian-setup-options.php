<?php
class Trestian_Setup_Options {
	/**
	 * @var string
	 */
	public $plugin_name;

	/**
	 * @var string
	 */
	public $version;

	/**
	 * @var
	 */
	public $plugin_path;

	/**
	 * @var string
	 */
	public $plugin_url;


	/**
	 * @var string
	 */
	public $prefix;

	/**
	 * @var string
	 */
	public $custom_fields_manager = Trestian_Options_Managers::ACF;

	/**
	 * @var \Dice\Dice
	 */
	public $dice = null;

	/**
	 * @var array
	 */
	public $substitutions = array();

	/**
	 * @var string
	 */
	public $cmb2_options_key;

	public function __construct() {
		$this->cmb2_options_key = $this->prefix . '_' . Trestian_Constants::CMB2_OPTIONS_KEY;
	}
}