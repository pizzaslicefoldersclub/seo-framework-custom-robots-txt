=== SEO Framework Custom Robots.txt ===
Contributors: Kyle Taylor
Donate link: https://github.com/kyletaylored
Tags: SEO, robots.txt, custom path, Pantheon, Fastly
Requires at least: 6.5
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.2.5
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Serves a custom robots.txt file from a configurable path if The SEO Framework is active. Adds flexibility to manage caching and serve robots.txt directly without theme output.

== Description ==

The SEO Framework Custom Robots.txt plugin enables users to specify a custom path for their robots.txt file when using The SEO Framework plugin. It intercepts requests for the robots.txt path, checks for an optional custom header, and serves the content directly—bypassing WordPress’s canonical redirection and theme output. 

This plugin is ideal for setups on platforms like Pantheon or Fastly, where flexibility in handling robots.txt paths is beneficial.

== Features ==

* **Custom Path**: Configure a path for robots.txt via the settings page.
* **Header-based Serving**: Serves robots.txt when the `X-Custom-Robots-Request` header is present, regardless of its value.
* **Direct Output**: Outputs robots.txt content as plain text without theme interference.
* **Cache Control**: Automatically tags output with `robots_txt` for Pantheon and Fastly caching.
* **Custom Admin Settings**: Easily configure path settings and view the current robots.txt content.

== Installation ==

1. Upload the `seo-framework-custom-robots-txt` directory to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Ensure The SEO Framework plugin is installed and active.

== Frequently Asked Questions ==

= How does this plugin work with Pantheon or Fastly? =

The plugin sets a `Surrogate-Key` header (`robots_txt`) to improve caching compatibility with Pantheon and Fastly, making cache purging straightforward when the robots.txt content changes.

= What happens if The SEO Framework is not active? =

If The SEO Framework plugin is not active, the plugin will return a `404 - Not Found` response.

== Changelog ==

= 1.2.5 =
* Improved header flexibility for serving robots.txt.
* Added custom rewrite rule to ensure WordPress recognizes the custom path.
* Enhanced canonical redirect prevention for specific path handling.

= 1.2.0 =
* Added support for accessing robots.txt content via a custom header.
* Integrated Pantheon cache tagging for efficient cache management.
* Updated settings page with instructions for using custom paths and headers.

= 1.1.0 =
* Added support for a configurable output path.
* Introduced cache control headers with 1-week caching.

= 1.0.0 =
* Initial release with robots.txt output served directly from a custom path.

== Upgrade Notice ==

= 1.2.5 =
- Added new logic to handle `X-Custom-Robots-Request` header, enabling flexible requests from various platforms.
