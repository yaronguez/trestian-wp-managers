<?php
/**
 * Configure and optionally create an instance of Dice configured for your plugin to work with Trestian WP Managers
 *
 * @param $plugin_name      - Your plugin name
 * @param $version          - Your plugin version
 * @param $plugin_url       - The absolute URL to your plugin root folder
 * @param $plugin_path      - The absolute path to your plugin root folder
 * @param $prefix           - The unique prefix identifier for options and other identifiers.
 * @param string $custom_fields - Which custom fields manager you are using.
								'ACF' or 'CMB2'. Defaults to ACF.
 * @param \Dice\Dice|null $dice - Optionally provide a pre-existing instance of dice
								to configure. If none is provided, a new one will be created.
 *
 * @return \Dice\Dice|WP_Error
 */
function twpm_setup_dice($plugin_name, $version, $plugin_url, $plugin_path, $prefix, $custom_fields = 'ACF', \Dice\Dice $dice = null, $options = array()){
	if(is_null($dice)){
		$dice = new \Dice\Dice;
	}

	// Parse optios and defaults
	$options = wp_parse_args($options, [
		'cmb2_options_key' => $prefix . '_' . Trestian_Constants::CMB2_OPTIONS_KEY
	]);

	// Set up options object
	$dice->addRule('Trestian_Options', [
		'shared'=>true,
		'constructParams' => [$options['cmb2_options_key']]
	]);

	// Configure plugin settings
	$dice->addRule( 'Trestian_Plugin_Settings', [
		'shared' => true,
		'constructParams' => [$plugin_name, $version, $plugin_url, $plugin_path, $prefix]
	]);

	// Determine Options Manager
	if($custom_fields == 'ACF') {
		$options_manager = 'Trestian_Acf_Manager';
	} else if($custom_fields == 'CMB2') {
		$options_manager = 'Trestian_Cmb2_Manager';
	} else {
		return new WP_Error('invalid_custom_fields_manager', 'Invalid custom fields manager provided. Only ACF or CMB2 are supported.');
	}

	// Set Options Manager
	$dice->addRule('*', ['substitutions' => [
		'ITrestian_Options_Manager' => [
			'instance'=>$options_manager
		]
	]]);



	// Set all Trestian WP Managers as shared instances
	$managers = [
		'Trestian_Acf_Manager',
		'Trestian_Cmb2_Manager',
		'Trestian_Ajax_Manager',
		'Trestian_Page_Manager',
		'Trestian_Template_Manager'
	];

	foreach ($managers as $manager){
		$dice->addRule($manager, ['shared'=>true]);

	}

	return $dice;
}