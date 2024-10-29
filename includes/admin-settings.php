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
    
    echo '<input type="text" id="custom_robots_path" name="seo_framework_custom_robots_path" value="' . $path . '" />';
    echo '<p><small>Full Path: <a href="' . esc_url($site_url . $path) . '" target="_blank" id="robots_preview_link">' . esc_html($site_url . $path) . '</a></small></p>';
}

// Register the admin menu
function seo_framework_custom_robots_add_admin_menu() {
    add_options_page(
        '',
        'Custom Robots.txt',
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
    <script>
        // Update preview link as path is modified
        document.getElementById('custom_robots_path').addEventListener('input', function() {
            const path = this.value;
            const previewLink = document.getElementById('robots_preview_link');
            const siteUrl = "<?php echo esc_url(site_url('/')); ?>";
            previewLink.href = siteUrl + path;
            previewLink.textContent = siteUrl + path;
        });
    </script>
    <?php
}

// Retrieve robots.txt content
function seo_framework_custom_get_robots_content() {
    if (is_plugin_active('autodescription/autodescription.php')) {
        return \The_SEO_Framework\RobotsTXT\Main::get_robots_txt();
    }
    return __('The SEO Framework plugin is not active. Unable to retrieve robots.txt content.', 'seo_framework_custom_robots_txt');
}
