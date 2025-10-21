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
                                    <svg class="docspopular-nav-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="16" y1="13" x2="8" y2="13"></line>
                                        <line x1="16" y1="17" x2="8" y2="17"></line>
                                        <polyline points="10 9 9 9 8 9"></polyline>
                                    </svg>
                                    <span class="docspopular-nav-text"><?php echo esc_html( $doc->post_title ); ?></span>
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
                <?php 
                $total_docs = count( $docs );
                foreach ( $docs as $index => $doc ) : 
                    setup_postdata( $GLOBALS['post'] =& $doc );
                    
                    // Get previous and next docs
                    $prev_doc = ( $index > 0 ) ? $docs[ $index - 1 ] : null;
                    $next_doc = ( $index < $total_docs - 1 ) ? $docs[ $index + 1 ] : null;
                ?>
                    <article id="doc-<?php echo esc_attr( $doc->ID ); ?>" 
                             class="docspopular-article <?php echo $index === 0 ? 'active' : ''; ?>"
                             data-index="<?php echo esc_attr( $index ); ?>">
                        <header class="docspopular-article-header">
                            <h1 class="docspopular-article-title"><?php echo esc_html( $doc->post_title ); ?></h1>
                        </header>
                        
                        <div class="docspopular-article-content">
                            <?php echo apply_filters( 'the_content', $doc->post_content ); ?>
                        </div>
                        
                        <!-- Navigation Buttons -->
                        <div class="docspopular-navigation">
                            <?php if ( $prev_doc ) : 
                                // Truncate to 3 words
                                $prev_title = $prev_doc->post_title;
                                $prev_words = explode( ' ', $prev_title );
                                if ( count( $prev_words ) > 3 ) {
                                    $prev_title = implode( ' ', array_slice( $prev_words, 0, 3 ) ) . '...';
                                }
                            ?>
                                <a href="#doc-<?php echo esc_attr( $prev_doc->ID ); ?>" 
                                   class="docspopular-nav-button docspopular-nav-prev"
                                   data-doc-id="<?php echo esc_attr( $prev_doc->ID ); ?>"
                                   title="<?php echo esc_attr( $prev_doc->post_title ); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>
                                    <div class="docspopular-nav-button-content">
                                        <span class="docspopular-nav-button-label"><?php _e( 'Previous', 'docspopular' ); ?></span>
                                        <span class="docspopular-nav-button-title"><?php echo esc_html( $prev_title ); ?></span>
                                    </div>
                                </a>
                            <?php else : ?>
                                <div class="docspopular-nav-button-placeholder"></div>
                            <?php endif; ?>
                            
                            <?php if ( $next_doc ) : 
                                // Truncate to 3 words
                                $next_title = $next_doc->post_title;
                                $next_words = explode( ' ', $next_title );
                                if ( count( $next_words ) > 3 ) {
                                    $next_title = implode( ' ', array_slice( $next_words, 0, 3 ) ) . '...';
                                }
                            ?>
                                <a href="#doc-<?php echo esc_attr( $next_doc->ID ); ?>" 
                                   class="docspopular-nav-button docspopular-nav-next"
                                   data-doc-id="<?php echo esc_attr( $next_doc->ID ); ?>"
                                   title="<?php echo esc_attr( $next_doc->post_title ); ?>">
                                    <div class="docspopular-nav-button-content">
                                        <span class="docspopular-nav-button-label"><?php _e( 'Next', 'docspopular' ); ?></span>
                                        <span class="docspopular-nav-button-title"><?php echo esc_html( $next_title ); ?></span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </a>
                            <?php endif; ?>
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

