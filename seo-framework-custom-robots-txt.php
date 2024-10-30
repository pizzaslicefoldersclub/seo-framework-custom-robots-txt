<?php
/**
 * Plugin Name: SEO Framework Custom Robots.txt
 * Plugin URI: https://github.com/pizzaslicefoldersclub/seo-framework-custom-robots-txt
 * Description: Serves a custom robots.txt file from a configurable path if The SEO Framework is active. Allows configuration of the output path.
 * Version: 1.2.5
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

// Prevent canonical redirects for custom robots path
add_filter('redirect_canonical', 'seo_framework_custom_disable_canonical_redirect', 10, 2);
function seo_framework_custom_disable_canonical_redirect($redirect_url, $requested_url) {
    $custom_path = get_option('seo_framework_custom_robots_path', 'robots');
    if (strpos($requested_url, '/' . $custom_path) !== false) {
        error_log('Preventing redirect for custom robots path: ' . $requested_url); // Debug output
        return false; // Prevent redirect for custom robots path
    }
    return $redirect_url;
}

// Early request handling for robots path
add_action('parse_request', 'seo_framework_custom_debug_request_path');
function seo_framework_custom_debug_request_path($wp) {
    $custom_path = get_option('seo_framework_custom_robots_path', 'robots');
    $requested_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    // Debug logging to verify request path
    if ($requested_path === $custom_path) {
        error_log('Detected custom robots path in parse_request: ' . $requested_path);
    }
}

// Intercept the custom robots.txt path and serve content
add_action('template_redirect', 'seo_framework_custom_serve_custom_robots', 0);

// Serve robots.txt content as plain text only, without theme output
function seo_framework_custom_serve_custom_robots() {
    $custom_path = get_option('seo_framework_custom_robots_path', 'robots');
    $requested_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    // Check if the custom header "X-Custom-Robots-Request" is present.
    if (isset($_SERVER['HTTP_X_CUSTOM_ROBOTS_REQUEST'])) {
        seo_framework_custom_output_robots_txt();
    }

    // Check if the requested URL path matches the custom path (ignoring query parameters)
    if ($requested_path === $custom_path) {
        seo_framework_custom_output_robots_txt();
    }
}

// Function to output robots.txt content with cache headers
function seo_framework_custom_output_robots_txt() {
    if (is_plugin_active('autodescription/autodescription.php')) {
        status_header(200); // Ensure 200 status for direct output
        header('Content-Type: text/plain');
        header('Cache-Control: public, max-age=604800'); // Cache for 1 week
        header('Surrogate-Key: robots_txt'); // Fastly cache key

        echo \The_SEO_Framework\RobotsTXT\Main::get_robots_txt();
    } else {
        // Return a 404 if The SEO Framework plugin is not active
        status_header(404);
        echo '404 - Not Found';
    }

    exit; // End processing to prevent further WordPress handling
}

// Hook to clear the Pantheon cache when the custom robots.txt path is updated
add_action('update_option_seo_framework_custom_robots_path', 'seo_framework_custom_clear_robots_cache', 10, 2);

function seo_framework_custom_clear_robots_cache($old_value, $new_value) {
    if (function_exists('pantheon_wp_clear_edge_keys')) {
        pantheon_wp_clear_edge_keys(array('robots_txt')); // Clear Pantheon cache
    }
}
