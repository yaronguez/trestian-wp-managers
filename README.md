# Trestian WP Managers
This a set of helpful managers, interfaces, and models I use in all of my custom WordPress plugins. This is my first attempt at centralizing them in a versioned way that allows them to be added as a library to any plugin without resulting in versioning conflicts.

I used CMB2 as a guide for versioning.

While I use these managers in production currently, loading them in this manner is very much a work in progress so be warned. This is also a very opinionated set of managers. I instantiate these managers using Dice, a dependency injection framework for PHP by Tom Butler, using a shared rule which is why there's no singletons in place. I highly recommend Dice.

This documentation is extremely thin. More including examples as I find the time.

# How to Load
Include `trestian-wp-managers.php` in your plugin root file. Instead of using the `plugins_loaded` action hook to load your plugins depenencies and hook, use the `trestian_wp_managers_loaded` action hook instead. This will ensure that the latest version of the dependencies have been loaded, either from this plugin or another using the same library.

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



