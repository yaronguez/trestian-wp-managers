<?php

class Trestian_Cmb2_Manager implements ITrestian_Options_Manager {

	/**
	 * @var Trestian_Plugin_Settings
	 */
	protected $settings;

	const OPTION_KEY = 'cmb2_options';

	public function __construct(Trestian_Plugin_Settings $settings) {
		$this->settings = $settings;
	}

	/**
	 * @param $key string
	 *
	 * @return mixed
	 */
	public function get_option_value( $key, $default = null) {
		if ( function_exists( 'cmb2_get_option' ) ) {
			// Use cmb2_get_option as it passes through some key filters.
			return cmb2_get_option( $this->settings->get_prefix() . '_' . self::OPTION_KEY, $key, $default );
		}

		// Fallback to get_option if CMB2 is not loaded yet.
		$opts = get_option( $this->settings->get_prefix() . '_cmb2_options', $key, $default );
		$val = $default;

		if ( 'all' == $key ) {
			$val = $opts;
		} elseif ( array_key_exists( $key, $opts ) && false !== $opts[ $key ] ) {
			$val = $opts[ $key ];
		}

		return $val;
	}

	/**
	 * Register an option field for a page in ACF
	 * @param Trestian_Page_Container $page_container
	 *
	 * @return void
	 */
	public function register_page_option( ITrestian_Page $page) {
		$cmb = new_cmb2_box( array(
			'id'         => $page->get_option_group_key(),
			'hookup'     => false,
			'cmb_styles' => false,
			'show_on'    => array(
				// These are important, don't remove
				'key'   => 'options-page',
				'value' => array( $this->settings->get_prefix() . '_' . self::OPTION_KEY )
			),
		) );

		// Set our CMB2 fields
		$cmb->add_field( array(
			'name' => $page->get_option_field_label(),
			'desc' => 'Select the page',
			'id'   => $this->settings->get_prefix(). '_' . $page->get_option_field_name(),
			'type' => 'post_search_text',
			'post_type'   => 'page',
			'select_type' => 'radio',
			'select_behavior' => 'replace'
		) );
	}
}