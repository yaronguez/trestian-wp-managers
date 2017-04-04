<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Configure new pages
 *
 *
 * @package    TrestianWPManagers
 * @subpackage TrestianWPManagers/managers
 * @author     Yaron Guez <yaron@trestian.com>
 */
class Trestian_Page_Manager{
	/**
	 * @var ITrestian_Page[]
	 */
	public $pages;

	/**
	 * @var Trestian_Page_Container[]
	 */
	public $page_containers;

	/**
	 * @var Trestian_Plugin_Settings
	 */
	protected $settings;

	/**
	 * @var ITrestian_Options_Manager
	 */
	protected $options_manager;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param $options_prefix
	 */
    public function __construct(Trestian_Plugin_Settings $settings, ITrestian_Options_Manager $options_manager ) {
		$this->pages = array();
		$this->page_containers = array();
		$this->settings = $settings;
		$this->options_manager = $options_manager;
    }

	/**
	 * Add page to be setup on plugin load
	 *
	 * @param ITrestian_Page $page
	 */
    public function add_page( ITrestian_Page $page){
	    // Set Page ID
	    $this->set_page_id($page);

	    // Append to list of pages indexed by option field name
    	$this->pages[$page->get_option_field_name()] = $page;
    }

	/**
	 * Load all pages
	 */
    public function load(){
	    foreach ($this->pages as $page){
		    $this->page_containers[] = $this->setup_page($page);
	    }
	}

	/**
	 * Given a class matching the Trestian page interface, and a page field, configure it for restricting access and displaying content
	 *
	 * @param ITrestian_Page $page
	 * @param $page_field
	 *
	 * @return Trestian_Page_Container
	 */
    public function setup_page( ITrestian_Page $page){
		// Create page container for page specific hooks
    	$page_container = new Trestian_Page_Container($page, $this->options_manager);

    	// Register all page hooks
	    add_action('init', array($page_container, 'create_option_field'));
	    add_action('template_redirect', array($page_container, 'restrict_page' ));
	    add_action('the_content', array($page_container, 'display_content'));
	    add_action('wp_enqueue_scripts', array($page_container, 'load_scripts'));
	    add_action('wp_enqueue_scripts', array($page_container, 'load_styles'));

	    return $page_container;
	}

	protected function set_page_id( ITrestian_Page $page){
		// Fetch the page ID using the page field from options
    	$page_id = $this->options_manager->get_option_value($page->get_option_field_name());

		// is_page(null) and is_page(0) returns true for some odd reason so default these to -1 to be safe
		$page_id = is_null($page_id) ? -1 : $page_id;
		$page->set_page_id($page_id);
	}


}

