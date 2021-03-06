<?php
/**
 * Created by PhpStorm.
 * User: yaronguez
 * Date: 4/3/17
 * Time: 5:52 PM
 */

class Trestian_Acf_Manager implements ITrestian_Options_Manager {

	const REGISTER_ACTION = 'init';
	/**
	 * @var Trestian_Plugin_Settings
	 */
	protected $settings;

	public function __construct(Trestian_Plugin_Settings $settings) {
		$this->settings = $settings;
	}

	/**
	 * @param $key string
	 *
	 * @return mixed
	 */
	public function get_option_value( $key, $default=null ) {
		$value = get_field($key, 'options');
		if(is_null($value) && !is_null($default)){
			return $default;
		}
		return $value;
	}

	/**
	 * Register an option field for a page in ACF
	 * @param Trestian_Page_Container $page_container
	 *
	 * @return void
	 */
	public function register_page_options( ITrestian_Page $page) {
		acf_add_local_field(array(
			'key' => $this->settings->get_prefix(). '_' . $page->get_option_field_name(),
			'label' => $page->get_option_field_label(),
			'name' => $page->get_option_field_name(),
			'type' => 'post_object',
			'parent' => $page->get_option_group_key(),
			'post_type' => array('page'),
			'return_format' => 'id',
		));
	}

	/**
	 * Get the action used to register the page options
	 * @return string
	 */
	public function get_register_action() {
		return self::REGISTER_ACTION;
	}
}