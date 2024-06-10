<?php
/**
 * Plugin Name: Neuralab Branding
 * Plugin URI: https://www.neuralab.net
 * Description: Adds Neuralab branding to WordPress dashboard and login.
 * Version: 1.1.3
 * License: MIT License
 * Author: development@neuralab.net
 * Author URI: https://www.neuralab.net
 * Requires at least: 6.5
 * Requires PHP: 8.1
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

// Define plugin root file.
if ( ! defined( 'NRLB_BRANDING_ROOT_FILE' ) ) {
	define( 'NRLB_BRANDING_ROOT_FILE', __FILE__ );
}

// Include the main plugin class.
if ( ! class_exists( 'NRLB_Branding' ) ) {
	include_once dirname( __FILE__ ) . '/includes/class-nrlb-branding.php';
}

/**
 * Init the plugin.
 *
 * @return NRLB_Branding Instance of NRLB_Branding class.
 */
function nrlb_branding() : NRLB_Branding {
	return NRLB_Branding::get_instance();
}

nrlb_branding();
