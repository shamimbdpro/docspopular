<?php
/**
 * Admin Filters and Customizations
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add category filter dropdown to admin list
 */
function docspopular_add_category_filter() {
    global $typenow;
    
    // Only add filter on docspopular post type page
    if ( $typenow !== 'docspopular' ) {
        return;
    }
    
    $taxonomy = 'docspopular_category';
    $selected = isset( $_GET[$taxonomy] ) ? $_GET[$taxonomy] : '';
    
    // Get all categories
    $categories = get_terms( array(
        'taxonomy'   => $taxonomy,
        'hide_empty' => false,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ) );
    
    if ( empty( $categories ) || is_wp_error( $categories ) ) {
        return;
    }
    
    ?>
    <select name="<?php echo esc_attr( $taxonomy ); ?>" id="<?php echo esc_attr( $taxonomy ); ?>" class="postform">
        <option value=""><?php _e( 'All Categories', 'docspopular' ); ?></option>
        <?php foreach ( $categories as $category ) : ?>
            <option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( $selected, $category->slug ); ?>>
                <?php echo esc_html( $category->name ); ?> (<?php echo esc_html( $category->count ); ?>)
            </option>
        <?php endforeach; ?>
    </select>
    <?php
}
add_action( 'restrict_manage_posts', 'docspopular_add_category_filter' );

/**
 * Filter the query to show only selected category
 * This runs with lower priority to not interfere with priority ordering
 */
function docspopular_filter_by_category( $query ) {
    global $pagenow, $typenow;
    
    // Only filter on admin edit page for our post type
    if ( ! is_admin() || $pagenow !== 'edit.php' || $typenow !== 'docspopular' ) {
        return;
    }
    
    $taxonomy = 'docspopular_category';
    
    // Check if category filter is set
    if ( isset( $_GET[$taxonomy] ) && ! empty( $_GET[$taxonomy] ) ) {
        $query->set( 'tax_query', array(
            array(
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $_GET[$taxonomy],
            ),
        ) );
    }
}
add_action( 'pre_get_posts', 'docspopular_filter_by_category', 5 );

/**
 * Add custom column for category in admin list
 */
function docspopular_add_category_column( $columns ) {
    // Remove default taxonomy column if exists
    if ( isset( $columns['taxonomy-docspopular_category'] ) ) {
        unset( $columns['taxonomy-docspopular_category'] );
    }
    
    // Add our custom category column after title
    $new_columns = array();
    foreach ( $columns as $key => $value ) {
        $new_columns[$key] = $value;
        if ( $key === 'title' ) {
            $new_columns['docspopular_category'] = __( 'Doc Category', 'docspopular' );
        }
    }
    
    return $new_columns;
}
add_filter( 'manage_docspopular_posts_columns', 'docspopular_add_category_column' );

/**
 * Display category in custom column
 */
function docspopular_display_category_column( $column, $post_id ) {
    if ( $column === 'docspopular_category' ) {
        $terms = get_the_terms( $post_id, 'docspopular_category' );
        
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $term_names = array();
            foreach ( $terms as $term ) {
                $term_link = add_query_arg( array(
                    'post_type' => 'docspopular',
                    'docspopular_category' => $term->slug,
                ), admin_url( 'edit.php' ) );
                
                $term_names[] = sprintf(
                    '<a href="%s">%s</a>',
                    esc_url( $term_link ),
                    esc_html( $term->name )
                );
            }
            echo implode( ', ', $term_names );
        } else {
            echo '<span style="color: #999;">' . __( 'No Category', 'docspopular' ) . '</span>';
        }
    }
}
add_action( 'manage_docspopular_posts_custom_column', 'docspopular_display_category_column', 10, 2 );

/**
 * Make category column sortable
 */
function docspopular_sortable_category_column( $columns ) {
    $columns['docspopular_category'] = 'docspopular_category';
    return $columns;
}
add_filter( 'manage_edit-docspopular_sortable_columns', 'docspopular_sortable_category_column' );

/**
 * Add notice showing current filter
 */
function docspopular_show_filter_notice() {
    global $typenow, $pagenow;
    
    if ( $pagenow !== 'edit.php' || $typenow !== 'docspopular' ) {
        return;
    }
    
    if ( isset( $_GET['docspopular_category'] ) && ! empty( $_GET['docspopular_category'] ) ) {
        $term = get_term_by( 'slug', $_GET['docspopular_category'], 'docspopular_category' );
        
        if ( $term && ! is_wp_error( $term ) ) {
            $clear_url = remove_query_arg( 'docspopular_category' );
            ?>
            <div class="notice notice-info is-dismissible">
                <p>
                    <?php 
                    printf( 
                        __( 'Showing documentation for: <strong>%s</strong>', 'docspopular' ),
                        esc_html( $term->name )
                    ); 
                    ?>
                    &nbsp;&nbsp;
                    <a href="<?php echo esc_url( $clear_url ); ?>" class="button button-small">
                        <?php _e( 'Show All Categories', 'docspopular' ); ?>
                    </a>
                </p>
            </div>
            <?php
        }
    }
}
add_action( 'admin_notices', 'docspopular_show_filter_notice' );

/**
 * Add quick category stats widget in admin
 */
function docspopular_add_category_stats_widget() {
    $screen = get_current_screen();
    
    if ( $screen->id !== 'edit-docspopular' ) {
        return;
    }
    
    $categories = get_terms( array(
        'taxonomy'   => 'docspopular_category',
        'hide_empty' => false,
        'orderby'    => 'count',
        'order'      => 'DESC',
    ) );
    
    if ( empty( $categories ) || is_wp_error( $categories ) ) {
        return;
    }
    
    ?>
    <style>
        .docspopular-stats {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .docspopular-stats h3 {
            margin: 0 0 15px 0;
            padding: 0;
            font-size: 14px;
            font-weight: 600;
        }
        .docspopular-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }
        .docspopular-stat-item {
            padding: 10px;
            background: #f6f7f7;
            border-left: 3px solid #2271b1;
            border-radius: 3px;
        }
        .docspopular-stat-item:hover {
            background: #e8eaeb;
        }
        .docspopular-stat-item a {
            text-decoration: none;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .docspopular-stat-name {
            font-weight: 500;
            color: #1d2327;
        }
        .docspopular-stat-count {
            background: #2271b1;
            color: #fff;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 600;
        }
    </style>
    <div class="docspopular-stats">
        <h3><?php _e( 'Documentation by Category', 'docspopular' ); ?></h3>
        <div class="docspopular-stats-grid">
            <?php foreach ( $categories as $category ) : 
                $filter_url = add_query_arg( array(
                    'post_type' => 'docspopular',
                    'docspopular_category' => $category->slug,
                ), admin_url( 'edit.php' ) );
            ?>
                <div class="docspopular-stat-item">
                    <a href="<?php echo esc_url( $filter_url ); ?>">
                        <span class="docspopular-stat-name"><?php echo esc_html( $category->name ); ?></span>
                        <span class="docspopular-stat-count"><?php echo esc_html( $category->count ); ?></span>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
}
add_action( 'all_admin_notices', 'docspopular_add_category_stats_widget' );

