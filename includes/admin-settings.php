<?php

// Register settings, admin menu, and enqueue scripts for admin page
// add_action('admin_menu', 'seo_framework_custom_robots_add_admin_menu');
add_action('admin_menu', 'seo_framework_custom_add_submenu', 20);
add_action('admin_init', 'seo_framework_custom_robots_settings_init');

// Settings Initialization
function seo_framework_custom_robots_settings_init() {
    register_setting('seo_framework_custom_robots', 'seo_framework_custom_robots_path', [
        'default' => 'robots'
    ]);

    add_settings_section(
        'seo_framework_custom_robots_section',
        __('Custom Robots.txt Settings', 'seo_framework_custom_robots_txt'),
        null,
        'seo_framework_custom_robots'
    );

    add_settings_field(
        'seo_framework_custom_robots_path',
        __('Custom Robots.txt Path', 'seo_framework_custom_robots_txt'),
        'seo_framework_custom_robots_path_render',
        'seo_framework_custom_robots',
        'seo_framework_custom_robots_section'
    );
}

// Render the path input field with auto-updating link preview
function seo_framework_custom_robots_path_render() {
    $path = esc_attr(get_option('seo_framework_custom_robots_path', 'robots'));
    $site_url = site_url('/');
    
    echo $site_url . '<input type="text" id="custom_robots_path" name="seo_framework_custom_robots_path" value="' . $path . '" />';
}

// Register the admin menu
// function seo_framework_custom_robots_add_admin_menu() {
//     add_options_page(
//         '',
//         'Custom Robots.txt',
//         'manage_options',
//         'seo_framework_custom_robots',
//         'seo_framework_custom_robots_options_page'
//     );
// }

// Register the submenu under The SEO Framework menu
function seo_framework_custom_add_submenu() {
    add_submenu_page(
        'theseoframework-settings',       // Parent slug
        'Custom Robots.txt',              // Page title
        'Custom Robots.txt',              // Menu title
        'manage_options',                 // Capability
        'seo_framework_custom_robots',    // Menu slug
        'seo_framework_custom_robots_options_page' // Callback function
    );
}

// Add Settings link to the plugin listing
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'seo_framework_custom_add_settings_link');
function seo_framework_custom_add_settings_link($links) {
    $settings_link = '<a href="' . esc_url(admin_url('admin.php?page=seo_framework_custom_robots')) . '">' . __('Settings') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Admin page content
function seo_framework_custom_robots_options_page() {
    ?>
    <div class="wrap">
        <form action="options.php" method="post">
            <?php
            settings_fields('seo_framework_custom_robots');
            do_settings_sections('seo_framework_custom_robots');
            submit_button();
            ?>

            <h2><?php _e('Access Options', 'seo_framework_custom_robots_txt'); ?></h2>
            <p><?php _e('You can access the custom robots.txt output in two ways:', 'seo_framework_custom_robots_txt'); ?></p>
            <ul>
                <li>
                    <strong><?php _e('Custom Path:', 'seo_framework_custom_robots_txt'); ?></strong>
                    <a href="<?php echo esc_url(site_url('/') . get_option('seo_framework_custom_robots_path', 'robots')); ?>" target="_blank">
                        <?php echo esc_html(site_url('/') . get_option('seo_framework_custom_robots_path', 'robots')); ?>
                    </a>
                </li>
                <li>
                    <strong><?php _e('Custom Header:', 'seo_framework_custom_robots_txt'); ?></strong>
                    <?php _e('Send a request with the header <code>X-Custom-Robots-Request: true</code> to fetch the robots.txt content from any URL on the site.', 'seo_framework_custom_robots_txt'); ?>
                </li>
            </ul>

            <h2><?php _e('Caching and Purging', 'seo_framework_custom_robots_txt'); ?></h2>
            <p><?php _e('The output is cached with the tag <code>robots_txt</code> to allow for efficient cache purging on Pantheon. The cache is cleared automatically when you update the settings.', 'seo_framework_custom_robots_txt'); ?></p>

            <h2><?php _e('Current Robots.txt Content', 'seo_framework_custom_robots_txt'); ?></h2>
            <textarea rows="10" cols="50" readonly><?php echo esc_textarea(seo_framework_custom_get_robots_content()); ?></textarea>
        </form>
    </div>
    <?php
}

// Retrieve robots.txt content
function seo_framework_custom_get_robots_content() {
    if (is_plugin_active('autodescription/autodescription.php')) {
        return \The_SEO_Framework\RobotsTXT\Main::get_robots_txt();
    }
    return __('The SEO Framework plugin is not active. Unable to retrieve robots.txt content.', 'seo_framework_custom_robots_txt');
}
