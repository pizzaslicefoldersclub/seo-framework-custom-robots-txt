=== SEO Framework Custom Robots.txt ===
Contributors: Kyle Taylor
Tags: robots.txt, seo, custom path, cache control, header access
Requires at least: 6.5
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.2.0
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Serve a custom robots.txt file with a configurable path and cache controls. Works with The SEO Framework plugin and integrates with Pantheon cache tags for automatic cache clearing.

== Description ==

This plugin allows you to serve a custom robots.txt file from a configurable path if The SEO Framework plugin is active. Features include:
* Customizable output path.
* Access control through a custom header.
* Cache control headers for optimal caching.
* Automatic cache purging with Pantheon Advanced Page Cache.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/seo-framework-custom-robots-txt` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Configure the output path in **Settings > Custom Robots.txt**.

== Usage ==

The custom robots.txt file can be accessed in two ways:

1. **Custom Path**: Access the file at the custom path configured in the settings.
2. **Custom Header**: Send a request with the header `X-Custom-Robots-Request: true` to fetch the robots.txt content from any URL on the site.

== Cache Control and Purging ==

The output is cached on Pantheon with a cache tag (`robots_txt`). Whenever the settings are updated, the cache is purged automatically.

== Frequently Asked Questions ==

= What happens if The SEO Framework plugin is inactive? =

The plugin will return a 404 if The SEO Framework plugin is inactive, as it relies on The SEO Framework to generate the robots.txt content.

= How can I clear the cache manually? =

The cache for the robots.txt output can be cleared manually by updating the settings in **Settings > Custom Robots.txt**, which triggers an automatic purge.

== Changelog ==

= 1.2.0 =
* Added support for accessing robots.txt content via a custom header.
* Integrated Pantheon cache tagging for efficient cache management.
* Updated settings page with instructions for using custom paths and headers.

= 1.1.0 =
* Added support for a configurable output path.
* Introduced cache control headers with 1-week caching.

= 1.0.0 =
* Initial release with robots.txt output served directly from a custom path.
