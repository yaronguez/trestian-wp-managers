<?php

/**
 * Page container class
 *
 * User: yaronguez
 * Date: 3/16/17
 * Time: 7:15 PM
 */
class Trestian_Page_Container {
	/**
	 * @var ITrestian_Page
	 */
	public $page;

	/**
	 * @var string
	 */
	protected $prefix;

	/**
	 * Trestian_Page_Container constructor.
	 *
	 * @param ITrestian_Page $page
	 * @param $prefix string
	 */
	public function __construct(ITrestian_Page $page, $prefix) {
		$this->page = $page;
		$this->prefix = $prefix;
	}

	/**
	 * Restrict access to page based
	 */
	public function restrict_page(){
		if($this->is_page()) {
			$this->page->restrict_page();
		}
	}

	/**
	 * Display content on page
	 */
	public function display_content($content){
		if($this->is_page()){
			return $this->page->display_content($content);
		}

		return $content;
	}

	/**
	 * Load styles on page
	 */
	public function load_styles(){
		if($this->is_page()){
			$this->page->load_styles();
		}
	}

	/**
	 * Load scripts on page
	 */
	public function load_scripts(){
		if($this->is_page()){
			$this->page->load_scripts();
		}
	}

	/**
	 * Register ACF option fields for page
	 */
	public function create_option_field(){
		acf_add_local_field(array(
			'key' => $this->prefix . '_' . $this->page->get_option_field_name(),
			'label' => $this->page->get_option_field_label(),
			'name' => $this->page->get_option_field_name(),
			'type' => 'post_object',
			'parent' => $this->page->get_option_group_key(),
			'post_type' => array('page'),
			'return_format' => 'id',
		));
	}

	/**
	 * Helper function to determine if user is on page
	 * @return bool
	 */
	private function is_page(){
		return is_page($this->page->get_page_id());
	}

}