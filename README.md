# Trestian WP Managers
This a set of helpful managers, interfaces, and models I use in all of my custom WordPress plugins. This is my first attempt at centralizing them in a versioned way that allows them to be added as a library to any plugin without resulting in versioning conflicts.

I used CMB2 as a guide for versioning.

While I use these managers in production currently, loading them in this manner is very much a work in progress so be warned. This is also a very opinionated set of managers. I instantiate these managers using Dice, a dependency injection framework for PHP by Tom Butler, using a shared rule which is why there's no singletons in place. I highly recommend Dice.

This documentation is extremely thin. More including examples as I find the time.

# How to Load
You can either install Trestian WP Managers as a standard WordPress plugin or 
include it in your plugin as a library. 

If installing it as plugin, be sure to install the [GitHub Updater plugin](https://github.com/afragen/github-updater) for updates.

If including it as a library, just require `trestian-wp-managers.php` within your plugin 
root file. 

In either case, it will be necessary for Trestian WP Managers to fully load before your
 plugin can load its own dependencies since your plugin code will extend and inject the
 contents of Trestian WP Managers. So, instead of using the `plugins_loaded` action hook 
 to kick off your plugin, use the `trestian_wp_managers_loaded` action hook instead. 
 This will ensure that the latest version of Trestian WP Managers has been loaded, 
 either from your own plugin, the standalone plugin, or another plugin that also includes it.

After your plugin's dependencies have been loaded, generate a configured [Dice dependency 
injection](https://r.je/dice.html) container for your plugin by calling the function 
`twpm_setup_dice()` using the following parameters:
```php
 /**
  *
  * $plugin_name      - Your plugin name
  * $version          - Your plugin version
  * $plugin_url       - The absolute URL to your plugin root folder
  * $plugin_path      - The absolute path to your plugin root folder
  * $prefix           - The unique prefix identifier for options and other identifiers.
  * string $custom_fields - Which custom fields manager you are using.
 								'ACF' or 'CMB2'. Defaults to ACF.
  * \Dice\Dice|null $dice - Optionally provide a pre-existing instance of dice
  **/
```
If you already have your own instance of Dice, you can pass it in to this function. If not,
the function will generate an instance for you. 

Finally, use the configured instance of Dice that the function returns to create the 
first object within your plugin's object graph. For example: 
```php
$hooks = $this->dice->create('My_Plugin_Hooks');
```
At this point, dependency injection will do its thing. Any time a class in your object graph
needs a Trestian WP manager, just type-hint it from within the class constructor and 
it will be injected. For example:
```php
class MyClass {
	/**	 
	 * @var Trestian_Plugin_Settings
	 */
	protected $settings;
	
	/**
	 * @var Trestian_Template_Manager
	 */
	protected $template_manager;

	public function __construct(Trestian_Plugin_Settings $settings, Trestian_Template_Manager $template_manager){
		$this->settings = $settings;		
		$this->template_manager = $template_manager;		
	}
	
	public function load_some_scripts(){
		wp_enqueue_script( $this->settings->get_plugin_name() . '-script', $this->settings->get_plugin_url(). 'assets/js/some-script.js', array( 'jquery' ), $this->settings->get_version(), false );
	}
	
	public function load_some_template(){
		$vm = [
			'message' => 'some message',
			'error' => 'some error'
			];
		$this->template_manager->load_template('templates/public/some-template.php', $vm);
	}
}
```
# What's Included
## Interfaces
### Page
This defines the interface used by the Page Manager, described later.

## Models
### Plugin Settings
Model for storing a plugin's name, version, URL and file path. These values are set in the constructor and configured using the constructorParams feature of Dice eg:
```
$this->dice->addRule('Trestian_Plugin_Settings', array(
			'shared' => true,
			'constructParams' => [$this->plugin_name, $this->version, $this->plugin_url, $this->plugin_path]
		));
```

I dependency inject this model into any plugin class that needs access to these variables rather than use globals or statics.

### Page
This is an abstract class that implements the Page interface and that can be extended to instantiate pages in an MVC fashion using the Page Manager. More on this later.

### Page Container
A container class for pages that wraps functionality surrounding restricting pages and returning content. Also configures options for each page using Advanced Custom Fields (CMB2 version in the works).

## Managers
### AJAX Manager
Helpful functions for working with AJAX requests within WordPress including validating and sanitizing inputs as well as returning JSON responses.

### Template Manager
Load templates in an MVC fashion modeled off of WooCommerce and others. 

### Page Manager
Allows you to add new pages easily, each with its access rules, content, and view model. Currently tied to Advanced Custom Fields for tieing an instance of a page to a WordPress page but a CMB2 option is in the works.



