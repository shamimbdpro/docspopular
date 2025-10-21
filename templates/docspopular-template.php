<?php
/**
 * Template Name: DocsPopular
 * Description: Page template for displaying documentation with sidebar navigation
 */

get_header();

// Get the selected category from URL parameter or page meta
$category_slug = get_query_var( 'doc_category' );
if ( empty( $category_slug ) ) {
    $category_slug = get_post_meta( get_the_ID(), '_docspopular_category', true );
}

// Get all categories if no specific one is selected
$categories = get_terms( array(
    'taxonomy'   => 'docspopular_category',
    'hide_empty' => false,
) );
?>

<div class="docspopular-wrapper docspopular-page-template">
    <div class="docspopular-container">
        
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            
            <div class="docspopular-page-header">
                <h1 class="docspopular-page-title"><?php the_title(); ?></h1>
                <?php if ( has_excerpt() ) : ?>
                    <div class="docspopular-page-excerpt">
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="docspopular-page-content">
                <?php the_content(); ?>
            </div>
            
        <?php endwhile; endif; ?>
        
        <!-- Display categories grid -->
        <?php if ( ! empty( $categories ) ) : ?>
            <div class="docspopular-categories-section">
                <h2 class="docspopular-section-title"><?php _e( 'Browse Documentation', 'docspopular' ); ?></h2>
                <?php echo do_shortcode( '[docspopular_categories]' ); ?>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<?php get_footer(); ?>

