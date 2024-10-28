<?php

// Register settings, admin menu, and enqueue scripts for admin page
add_action('admin_menu', 'seo_framework_custom_robots_add_admin_menu');
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
        __('Robots.txt Output Path', 'seo_framework_custom_robots_txt'),
        'seo_framework_custom_robots_path_render',
        'seo_framework_custom_robots',
        'seo_framework_custom_robots_section'
    );
}

// Render the path input field
function seo_framework_custom_robots_path_render() {
    $path = esc_attr(get_option('seo_framework_custom_robots_path', 'robots'));
    echo '<input type="text" name="seo_framework_custom_robots_path" value="' . $path . '" />';
}

// Register the admin menu
function seo_framework_custom_robots_add_admin_menu() {
    add_options_page(
        'Custom Robots.txt Settings',
        'Robots.txt Settings',
        'manage_options',
        'seo_framework_custom_robots',
        'seo_framework_custom_robots_options_page'
    );
}

// Admin page content
function seo_framework_custom_robots_options_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Custom Robots.txt Settings', 'seo_framework_custom_robots_txt'); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('seo_framework_custom_robots');
            do_settings_sections('seo_framework_custom_robots');
            submit_button();
            ?>

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
