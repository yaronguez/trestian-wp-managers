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
 * @deprecated use twpm_setup instead
 * @return \Dice\Dice|WP_Error
 */
function twpm_setup_dice($plugin_name, $version, $plugin_url, $plugin_path, $prefix, $custom_fields = 'ACF', \Dice\Dice $dice = null, $internal_options = array()){
	$options = new Trestian_Setup_Options();
	$options->plugin_name = $plugin_name;
	$options->version = $version;
	$options->plugin_path = $plugin_path;
	$options->plugin_url = $plugin_url;
	$options->prefix = $prefix;
	$options->custom_fields_manager = $custom_fields;
	$options->dice = $dice;
	if(isset($internal_options['cmb2_options_key'])){
		$options->cmb2_options_key = $internal_options['cmb2_options_key'];
	}
	return twpm_setup($options);
}

/**
 * @param Trestian_Setup_Options $options
 *
 * @return \Dice\Dice
 */
function twpm_setup(Trestian_Setup_Options $options){
	$dice = $options->dice;
	if(is_null($dice)){
		$dice = new \Dice\Dice;
	}

	// Set up internal options object
	$dice->addRule('Trestian_Options', [
		'shared'=>true,
		'constructParams' => [$options->cmb2_options_key]
	]);

	// Configure plugin settings
	$dice->addRule( 'Trestian_Plugin_Settings', [
		'shared' => true,
		'constructParams' => [$options->plugin_name, $options->version, $options->plugin_url, $options->plugin_path, $options->prefix]
	]);

	// Determine Options Manager
	$options_manager = Trestian_Options_Managers::get_class($options->custom_fields_manager);
	if(!$options_manager){
		throw new InvalidArgumentException('Invalid custom fields manager specified');
	}

	// Set up substitutions with Options Manager
	$substitutions = array_merge($options->substitutions, [
		'ITrestian_Options_Manager' => [
			'instance'=>$options_manager
		]
	]);
	$dice->addRule('*', ['substitutions' => $substitutions]);

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