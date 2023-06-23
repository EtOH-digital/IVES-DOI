<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://https://www.fiverr.com/mustafaje
 * @since             1.0.0
 * @package           Doi_Creator
 *
 * @wordpress-plugin
 * Plugin Name:       DOI Creator
 * Plugin URI:        https://www.fiverr.com/gm__developer
 * Description:       A plugin to create and submit DOIs to the CROSSREF API.
 * Version:           1.0.0
 * Author:            GM
 * Author URI:        https://https://www.fiverr.com/mustafaje
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       doi-creator
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'DOI_CREATOR_VERSION', '1.0.0' );
define( 'DOI_CREATOR_SLUG', 'doi-creator' );
define( 'DOI_CREATOR_FILE', __FILE__ );
define( 'DOI_CREATOR_URL', plugin_dir_url( __FILE__ ) );
define( 'DOI_CREATOR_PATH', plugin_dir_path( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-doi-creator-activator.php
 */
function activate_doi_creator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-doi-creator-activator.php';
	Doi_Creator_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-doi-creator-deactivator.php
 */
function deactivate_doi_creator() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-doi-creator-deactivator.php';
	Doi_Creator_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_doi_creator' );
register_deactivation_hook( __FILE__, 'deactivate_doi_creator' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-doi-creator.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_doi_creator() {

	$plugin = new Doi_Creator();
	$plugin->run();

}
run_doi_creator();
