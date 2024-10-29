<?php
/**
 * Plugin Name: SEO Framework Custom Robots.txt
 * Plugin URI: https://github.com/pizzaslicefoldersclub/seo-framework-custom-robots-txt
 * Description: Serves a custom robots.txt file from a configurable path if The SEO Framework is active. Allows configuration of the output path.
 * Version: 1.2.0
 * Author: Kyle Taylor
 * Author URI: https://github.com/kyletaylored
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: seo_framework_custom_robots_txt
 * Requires PHP: 7.4
 * Requires at least: 6.5
 * Requires Plugins: autodescription
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Include admin settings for handling the plugin configuration
require_once plugin_dir_path(__FILE__) . 'includes/admin-settings.php';

// Intercept the custom robots.txt path and serve content
add_action('template_redirect', 'seo_framework_custom_serve_custom_robots');

// Serve robots.txt content as plain text only, without theme output
function seo_framework_custom_serve_custom_robots() {
    // Check if the custom header "X-Custom-Robots-Request" is present and set to "true"
    if (isset($_SERVER['HTTP_X_CUSTOM_ROBOTS_REQUEST']) && $_SERVER['HTTP_X_CUSTOM_ROBOTS_REQUEST'] === 'true') {
        seo_framework_custom_output_robots_txt();
    }

    // Get the saved path, default to "robots" if not set
    $custom_path = get_option('seo_framework_custom_robots_path', 'robots');

    // Check if the requested URL matches the custom path
    if (trim($_SERVER['REQUEST_URI'], '/') === $custom_path) {
        seo_framework_custom_output_robots_txt();
    }
}

// Function to output robots.txt content with cache headers
function seo_framework_custom_output_robots_txt() {
    // Check if The SEO Framework plugin is active
    if (is_plugin_active('autodescription/autodescription.php')) {
        // Set the status header to 200 OK
        status_header(200);

        // Set content-type and cache-control headers
        header('Content-Type: text/plain');
        header('Cache-Control: public, max-age=604800'); // Cache for 1 week

        // Add Pantheon cache tags
        header('Surrogate-Key: robots_txt'); // Custom tag for robots.txt

        // Output the robots.txt content
        echo \The_SEO_Framework\RobotsTXT\Main::get_robots_txt();
        exit;  // Prevent any further output or theme processing
    } else {
        // Return a 404 if The SEO Framework plugin is not active
        status_header(404);
        echo '404 - Not Found';
        exit;
    }
}

// Hook to clear the Pantheon cache when the custom robots.txt path is updated
add_action('update_option_seo_framework_custom_robots_path', 'seo_framework_custom_clear_robots_cache', 10, 2);

function seo_framework_custom_clear_robots_cache($old_value, $new_value) {
    // Check if the function exists to avoid errors if not on Pantheon
    if (function_exists('pantheon_wp_clear_edge_keys')) {
        // Clear the cache for the robots.txt output
        pantheon_wp_clear_edge_keys(array('robots_txt'));
    }
}
