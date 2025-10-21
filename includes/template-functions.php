<?php
/**
 * Template Functions
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add custom page template
 */
function docspopular_add_page_template( $templates ) {
    $templates['docspopular-template.php'] = __( 'DocsPopular', 'docspopular' );
    return $templates;
}
add_filter( 'theme_page_templates', 'docspopular_add_page_template' );

/**
 * Load custom page template
 */
function docspopular_load_page_template( $template ) {
    if ( is_page_template( 'docspopular-template.php' ) ) {
        $plugin_template = DOCSPOPULAR_PLUGIN_DIR . 'templates/docspopular-template.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter( 'template_include', 'docspopular_load_page_template' );

/**
 * Load taxonomy template
 */
function docspopular_load_taxonomy_template( $template ) {
    if ( is_tax( 'docspopular_category' ) ) {
        $plugin_template = DOCSPOPULAR_PLUGIN_DIR . 'templates/taxonomy-docspopular_category.php';
        if ( file_exists( $plugin_template ) ) {
            return $plugin_template;
        }
    }
    return $template;
}
add_filter( 'template_include', 'docspopular_load_taxonomy_template', 99 );

/**
 * Get docs navigation for a category
 * Ordered by priority (lowest number first), then by title
 */
function docspopular_get_category_navigation( $category_id ) {
    $args = array(
        'post_type'      => 'docspopular',
        'posts_per_page' => -1,
        'meta_key'       => '_docspopular_priority',
        'orderby'        => array(
            'meta_value_num' => 'ASC',   // Priority: 0 first, then 1, 2, 3...
            'title'          => 'ASC',   // Then alphabetically
        ),
        'tax_query'      => array(
            array(
                'taxonomy' => 'docspopular_category',
                'field'    => 'term_id',
                'terms'    => $category_id,
            ),
        ),
    );

    return get_posts( $args );
}

