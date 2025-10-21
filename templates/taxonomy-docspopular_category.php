<?php
/**
 * Template for displaying documentation category archive
 */

get_header();

$current_term = get_queried_object();
$docs = docspopular_get_category_navigation( $current_term->term_id );
?>

<div class="docspopular-wrapper">
    <div class="docspopular-container">
        
        <!-- Left Sidebar Navigation -->
        <aside class="docspopular-sidebar">
            <div class="docspopular-sidebar-header">
                <h2 class="docspopular-sidebar-title"><?php echo esc_html( $current_term->name ); ?></h2>
                <?php if ( ! empty( $current_term->description ) ) : ?>
                    <p class="docspopular-sidebar-description"><?php echo esc_html( $current_term->description ); ?></p>
                <?php endif; ?>
            </div>
            
            <nav class="docspopular-nav">
                <?php if ( ! empty( $docs ) ) : ?>
                    <ul class="docspopular-nav-list">
                        <?php foreach ( $docs as $doc ) : ?>
                            <li class="docspopular-nav-item">
                                <a href="#doc-<?php echo esc_attr( $doc->ID ); ?>" 
                                   class="docspopular-nav-link" 
                                   data-doc-id="<?php echo esc_attr( $doc->ID ); ?>">
                                    <?php echo esc_html( $doc->post_title ); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p class="docspopular-no-docs"><?php _e( 'No documentation found.', 'docspopular' ); ?></p>
                <?php endif; ?>
            </nav>
        </aside>
        
        <!-- Right Content Area -->
        <main class="docspopular-content">
            <?php if ( ! empty( $docs ) ) : ?>
                <?php foreach ( $docs as $index => $doc ) : 
                    setup_postdata( $GLOBALS['post'] =& $doc );
                ?>
                    <article id="doc-<?php echo esc_attr( $doc->ID ); ?>" 
                             class="docspopular-article <?php echo $index === 0 ? 'active' : ''; ?>">
                        <header class="docspopular-article-header">
                            <h1 class="docspopular-article-title"><?php echo esc_html( $doc->post_title ); ?></h1>
                        </header>
                        
                        <div class="docspopular-article-content">
                            <?php echo apply_filters( 'the_content', $doc->post_content ); ?>
                        </div>
                    </article>
                <?php 
                    wp_reset_postdata();
                endforeach; ?>
            <?php else : ?>
                <div class="docspopular-empty">
                    <h2><?php _e( 'No Documentation Available', 'docspopular' ); ?></h2>
                    <p><?php _e( 'Documentation will be added soon.', 'docspopular' ); ?></p>
                </div>
            <?php endif; ?>
        </main>
        
    </div>
</div>

<?php get_footer(); ?>

