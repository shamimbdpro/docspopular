<?php
/**
 * Plugin Name: DocsPopular
 * Plugin URI: https://docspopular.com
 * Description: A beautiful and minimal documentation system for WordPress themes and plugins
 * Version: 1.0.0
 * Author: Shamim Hasan
 * Author URI: https://codepopular.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: docspopular
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'DOCSPOPULAR_VERSION', '1.0.0' );
define( 'DOCSPOPULAR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'DOCSPOPULAR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files
require_once DOCSPOPULAR_PLUGIN_DIR . 'includes/post-type.php';
require_once DOCSPOPULAR_PLUGIN_DIR . 'includes/taxonomy.php';
require_once DOCSPOPULAR_PLUGIN_DIR . 'includes/shortcodes.php';
require_once DOCSPOPULAR_PLUGIN_DIR . 'includes/template-functions.php';
require_once DOCSPOPULAR_PLUGIN_DIR . 'includes/admin-filters.php';
require_once DOCSPOPULAR_PLUGIN_DIR . 'includes/priority-system.php';

/**
 * Check if we should load DocsPopular assets
 */
function docspopular_should_load_assets() {
    // Load on single documentation pages
    if ( is_singular( 'docspopular' ) ) {
        return true;
    }
    
    // Load on documentation category archives
    if ( is_tax( 'docspopular_category' ) ) {
        return true;
    }
    
    // Load on pages using DocsPopular template
    if ( is_page_template( 'docspopular-template.php' ) ) {
        return true;
    }
    
    // Load if shortcode is present in the content
    global $post;
    if ( is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'docspopular_categories' ) ) {
        return true;
    }
    
    return false;
}

/**
 * Enqueue plugin styles and scripts only when needed
 */
function docspopular_enqueue_assets() {
    // Only load assets on DocsPopular related pages
    if ( ! docspopular_should_load_assets() ) {
        return;
    }
    
    wp_enqueue_style( 
        'docspopular-styles', 
        DOCSPOPULAR_PLUGIN_URL . 'assets/css/docspopular.css', 
        array(), 
        DOCSPOPULAR_VERSION 
    );
    
    wp_enqueue_script( 
        'docspopular-scripts', 
        DOCSPOPULAR_PLUGIN_URL . 'assets/js/docspopular.js', 
        array( 'jquery' ), 
        DOCSPOPULAR_VERSION, 
        true 
    );
}
add_action( 'wp_enqueue_scripts', 'docspopular_enqueue_assets' );

/**
 * Activation hook
 */
function docspopular_activate() {
    // Register post type and taxonomy
    docspopular_register_post_type();
    docspopular_register_taxonomy();
    
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'docspopular_activate' );

/**
 * Deactivation hook
 */
function docspopular_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'docspopular_deactivate' );

