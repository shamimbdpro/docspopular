<?php
/**
 * DocsPopular Shortcodes
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue assets when shortcode is used
 */
function docspopular_enqueue_shortcode_assets() {
    if ( ! wp_style_is( 'docspopular-styles', 'enqueued' ) ) {
        wp_enqueue_style( 
            'docspopular-styles', 
            DOCSPOPULAR_PLUGIN_URL . 'assets/css/docspopular.css', 
            array(), 
            DOCSPOPULAR_VERSION 
        );
    }
    
    if ( ! wp_script_is( 'docspopular-scripts', 'enqueued' ) ) {
        wp_enqueue_script( 
            'docspopular-scripts', 
            DOCSPOPULAR_PLUGIN_URL . 'assets/js/docspopular.js', 
            array( 'jquery' ), 
            DOCSPOPULAR_VERSION, 
            true 
        );
    }
}

/**
 * Shortcode to display documentation categories with post counts
 * Usage: [docspopular_categories]
 */
function docspopular_categories_shortcode( $atts ) {
    // Enqueue assets when shortcode is used
    docspopular_enqueue_shortcode_assets();
    
    $atts = shortcode_atts( array(
        'orderby' => 'name',
        'order'   => 'ASC',
    ), $atts, 'docspopular_categories' );

    $terms = get_terms( array(
        'taxonomy'   => 'docspopular_category',
        'hide_empty' => false,
        'orderby'    => $atts['orderby'],
        'order'      => $atts['order'],
    ) );

    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return '<p class="docspopular-no-categories">' . __( 'No documentation categories found.', 'docspopular' ) . '</p>';
    }

    ob_start();
    ?>
    <div class="docspopular-categories-grid">
        <?php foreach ( $terms as $term ) : 
            $term_link = get_term_link( $term );
            $doc_count = $term->count;
        ?>
            <div class="docspopular-category-card">
                <a href="<?php echo esc_url( $term_link ); ?>" class="docspopular-category-link">
                    <div class="docspopular-category-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path>
                            <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path>
                        </svg>
                    </div>
                    <h3 class="docspopular-category-title"><?php echo esc_html( $term->name ); ?></h3>
                    <?php if ( ! empty( $term->description ) ) : ?>
                        <p class="docspopular-category-description"><?php echo esc_html( $term->description ); ?></p>
                    <?php endif; ?>
                    <div class="docspopular-category-count">
                        <span class="count-number"><?php echo esc_html( $doc_count ); ?></span>
                        <span class="count-text"><?php echo esc_html( _n( 'Article', 'Articles', $doc_count, 'docspopular' ) ); ?></span>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'docspopular_categories', 'docspopular_categories_shortcode' );

