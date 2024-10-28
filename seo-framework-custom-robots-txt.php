<?php
/**
 * Plugin Name: SEO Framework Custom Robots.txt
 * Plugin URI: https://github.com/pizzaslicefoldersclub/seo-framework-custom-robots-txt
 * Description: Serves a custom robots.txt file from a different path if The SEO Framework is active. Allows configuration of the output path.
 * Version: 1.0.0
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

// Register custom rewrite rule on init
add_action('init', 'seo_framework_custom_register_robots_route');

// Add the query var to detect robots requests
add_filter('query_vars', function($vars) {
    $vars[] = 'custom_robots';
    return $vars;
});

// Serve the robots.txt content
add_action('template_redirect', 'seo_framework_custom_serve_robots_txt');

// Add plugin activation and deactivation hooks
register_activation_hook(__FILE__, 'seo_framework_custom_flush_rewrite_rules');
register_deactivation_hook(__FILE__, 'seo_framework_custom_flush_rewrite_rules_deactivation');

// Register the custom robots path for rewrite rules
function seo_framework_custom_register_robots_route() {
    $path = get_option('seo_framework_custom_robots_path', 'robots');
    add_rewrite_rule('^' . $path . '/?$', 'index.php?custom_robots=1', 'top');
}

// Serve robots.txt content
function seo_framework_custom_serve_robots_txt() {
    if (get_query_var('custom_robots') === '1') {
        if (is_plugin_active('autodescription/autodescription.php')) {
            $robots_content = \The_SEO_Framework\RobotsTXT\Main::get_robots_txt();
            header('Content-Type: text/plain');
            echo $robots_content;
            exit;
        } else {
            status_header(404);
            echo '404 - Not Found';
            exit;
        }
    }
}

// Flush rewrite rules on plugin activation
function seo_framework_custom_flush_rewrite_rules() {
    seo_framework_custom_register_robots_route();
    flush_rewrite_rules();
}

// Flush rewrite rules on plugin deactivation
function seo_framework_custom_flush_rewrite_rules_deactivation() {
    flush_rewrite_rules();
}
