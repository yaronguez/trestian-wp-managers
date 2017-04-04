<?php
/**
 * Created by PhpStorm.
 * User: yaronguez
 * Date: 4/3/17
 * Time: 5:52 PM
 */

interface ITrestian_Options_Manager {
	/**
	 * @param $key string
	 *
	 * @return mixed
	 */
	public function get_option_value($key, $default);

	/**
	 * @param ITrestian_Page $page;
	 *
	 * @return void
	 */
	public function register_page_option(ITrestian_Page $page);

}