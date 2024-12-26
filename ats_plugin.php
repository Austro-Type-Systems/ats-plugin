	<?php
/*
	Plugin Name: ats_plugin    
	Description: Plugin for the ats_theme
	Version: 1.0
	Author: r3n0
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load textdomain
function ats_plugin_load_textdomain() {
    load_plugin_textdomain('ats_plugin', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'ats_plugin_load_textdomain');

// Define plugin paths
if (!defined('ATS_PLUGIN_PATH')) {
    define('ATS_PLUGIN_PATH', plugin_dir_path(__FILE__));
}
if (!defined('ATS_PLUGIN_URL')) {
    define('ATS_PLUGIN_URL', plugin_dir_url(__FILE__));
}

// Custom post types
require_once ATS_PLUGIN_PATH . 'includes/custom-post-types.php';
add_action('init', 'ats_plugin_work_custom_post_type');
add_action('init', 'ats_plugin_reel_custom_post_type');

// Enqueue libraries
require_once ATS_PLUGIN_PATH . 'includes/enqueue.php';
add_action('wp_enqueue_scripts', 'ats_plugin_enqueue_scripts');

// Add custom block category
require_once ATS_PLUGIN_PATH . 'includes/custom-block-category.php';
add_filter('block_categories_all', 'ats_plugin_custom_block_categories', 10, 2);

