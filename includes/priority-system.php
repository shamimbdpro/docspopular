<?php
/**
 * Priority/Order System for Documentation Items
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add Priority column to admin list
 */
function docspopular_add_priority_column( $columns ) {
    $new_columns = array();
    
    foreach ( $columns as $key => $value ) {
        $new_columns[$key] = $value;
        
        // Add priority column after title
        if ( $key === 'title' ) {
            $new_columns['docspopular_priority'] = __( 'Priority', 'docspopular' );
        }
    }
    
    return $new_columns;
}
add_filter( 'manage_docspopular_posts_columns', 'docspopular_add_priority_column', 20 );

/**
 * Display Priority value with inline edit
 */
function docspopular_display_priority_column( $column, $post_id ) {
    if ( $column === 'docspopular_priority' ) {
        $priority = get_post_meta( $post_id, '_docspopular_priority', true );
        $priority = $priority ? intval( $priority ) : 0;
        
        ?>
        <div class="docspopular-priority-wrapper">
            <span class="docspopular-priority-display" style="font-weight: 600; font-size: 14px; color: #2271b1;">
                <?php echo esc_html( $priority ); ?>
            </span>
        </div>
        <?php
    }
}
add_action( 'manage_docspopular_posts_custom_column', 'docspopular_display_priority_column', 20, 2 );

/**
 * Make Priority column sortable
 */
function docspopular_sortable_priority_column( $columns ) {
    $columns['docspopular_priority'] = 'docspopular_priority';
    return $columns;
}
add_filter( 'manage_edit-docspopular_sortable_columns', 'docspopular_sortable_priority_column' );

/**
 * Handle sorting by priority and set default order
 * Runs at priority 10 (after category filter at priority 5)
 */
function docspopular_priority_orderby( $query ) {
    if ( ! is_admin() || ! $query->is_main_query() ) {
        return;
    }
    
    global $typenow, $pagenow;
    
    // Only for docspopular post type on edit.php page
    if ( $typenow !== 'docspopular' || $pagenow !== 'edit.php' ) {
        return;
    }
    
    $orderby = $query->get( 'orderby' );
    
    // If explicitly sorting by priority column
    if ( 'docspopular_priority' === $orderby ) {
        $query->set( 'meta_key', '_docspopular_priority' );
        $query->set( 'orderby', 'meta_value_num' );
    }
    
    // Set default ordering by priority if no orderby is set
    // This ensures priority order is maintained even with category filtering
    if ( empty( $orderby ) ) {
        $query->set( 'meta_key', '_docspopular_priority' );
        $query->set( 'orderby', 'meta_value_num' );
        $query->set( 'order', 'ASC' );
    }
}
add_action( 'pre_get_posts', 'docspopular_priority_orderby', 10 );

/**
 * Add meta box for priority in edit screen
 */
function docspopular_add_priority_meta_box() {
    add_meta_box(
        'docspopular_priority_box',
        __( 'Display Priority', 'docspopular' ),
        'docspopular_priority_meta_box_callback',
        'docspopular',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'docspopular_add_priority_meta_box' );

/**
 * Priority meta box callback
 */
function docspopular_priority_meta_box_callback( $post ) {
    wp_nonce_field( 'docspopular_priority_nonce', 'docspopular_priority_nonce' );
    
    $priority = get_post_meta( $post->ID, '_docspopular_priority', true );
    
    // For new posts, show auto-assigned value or calculate next
    if ( $post->post_status === 'auto-draft' || $priority === '' ) {
        global $wpdb;
        $max_priority = $wpdb->get_var( 
            "SELECT MAX(CAST(meta_value AS UNSIGNED)) 
             FROM {$wpdb->postmeta} 
             WHERE meta_key = '_docspopular_priority' 
             AND post_id IN (
                 SELECT ID FROM {$wpdb->posts} 
                 WHERE post_type = 'docspopular' 
                 AND post_status != 'trash'
             )"
        );
        $priority = $max_priority !== null ? intval( $max_priority ) + 1 : 0;
        $is_auto = true;
    } else {
        $priority = intval( $priority );
        $is_auto = false;
    }
    
    ?>
    <p>
        <label for="docspopular_priority_field" style="display: block; margin-bottom: 5px; font-weight: 600;">
            <?php _e( 'Priority Order:', 'docspopular' ); ?>
        </label>
        <input type="number" 
               id="docspopular_priority_field" 
               name="docspopular_priority" 
               value="<?php echo esc_attr( $priority ); ?>" 
               min="0" 
               step="1"
               style="width: 100%; padding: 5px;">
        <span style="display: block; margin-top: 5px; color: #666; font-size: 12px;">
            <?php 
            if ( $is_auto ) {
                printf(
                    __( 'Auto-assigned: %d (next available). Lower numbers appear first.', 'docspopular' ),
                    $priority
                );
            } else {
                _e( 'Lower numbers appear first: 0 = top, 1 = second, 2 = third, etc.', 'docspopular' );
            }
            ?>
        </span>
    </p>
    <?php
}

/**
 * Auto-assign priority to new posts
 */
function docspopular_auto_assign_priority( $post_id, $post, $update ) {
    // Only for new posts (not updates)
    if ( $update ) {
        return;
    }
    
    // Check if priority already set
    $existing_priority = get_post_meta( $post_id, '_docspopular_priority', true );
    if ( $existing_priority !== '' ) {
        return;
    }
    
    // Get the highest priority value from all docs
    global $wpdb;
    $max_priority = $wpdb->get_var( 
        "SELECT MAX(CAST(meta_value AS UNSIGNED)) 
         FROM {$wpdb->postmeta} 
         WHERE meta_key = '_docspopular_priority' 
         AND post_id IN (
             SELECT ID FROM {$wpdb->posts} 
             WHERE post_type = 'docspopular' 
             AND post_status != 'trash'
         )"
    );
    
    // Set next priority (max + 1, or 0 if no docs exist)
    $next_priority = $max_priority !== null ? intval( $max_priority ) + 1 : 0;
    
    update_post_meta( $post_id, '_docspopular_priority', $next_priority );
}
add_action( 'wp_insert_post', 'docspopular_auto_assign_priority', 10, 3 );

/**
 * Save priority meta value
 */
function docspopular_save_priority_meta( $post_id ) {
    // Check nonce
    if ( ! isset( $_POST['docspopular_priority_nonce'] ) ) {
        return;
    }
    
    if ( ! wp_verify_nonce( $_POST['docspopular_priority_nonce'], 'docspopular_priority_nonce' ) ) {
        return;
    }
    
    // Check autosave
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    // Check permissions
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    // Save priority
    if ( isset( $_POST['docspopular_priority'] ) ) {
        $priority = intval( $_POST['docspopular_priority'] );
        update_post_meta( $post_id, '_docspopular_priority', $priority );
    }
}
add_action( 'save_post_docspopular', 'docspopular_save_priority_meta' );

/**
 * AJAX handler for inline priority update
 */
function docspopular_ajax_update_priority() {
    // Check nonce
    check_ajax_referer( 'docspopular-priority-nonce', 'nonce' );
    
    // Check permissions
    if ( ! current_user_can( 'edit_posts' ) ) {
        wp_send_json_error( array( 'message' => __( 'Permission denied', 'docspopular' ) ) );
    }
    
    $post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
    $priority = isset( $_POST['priority'] ) ? intval( $_POST['priority'] ) : 0;
    
    if ( ! $post_id ) {
        wp_send_json_error( array( 'message' => __( 'Invalid post ID', 'docspopular' ) ) );
    }
    
    // Check if user can edit this post
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        wp_send_json_error( array( 'message' => __( 'Permission denied for this post', 'docspopular' ) ) );
    }
    
    // Update priority
    update_post_meta( $post_id, '_docspopular_priority', $priority );
    
    wp_send_json_success( array( 
        'message' => __( 'Priority updated', 'docspopular' ),
        'priority' => $priority
    ) );
}
add_action( 'wp_ajax_docspopular_update_priority', 'docspopular_ajax_update_priority' );

/**
 * Enqueue admin styles
 */
function docspopular_enqueue_admin_scripts( $hook ) {
    global $post_type;
    
    if ( $post_type !== 'docspopular' || $hook !== 'edit.php' ) {
        return;
    }
    
    ?>
    <style>
        .docspopular-priority-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .docspopular-priority-display {
            background: #f0f6fc;
            border: 1px solid #c3dafe;
            border-radius: 4px;
            padding: 4px 12px;
            display: inline-block;
            min-width: 30px;
            text-align: center;
        }
        
        .column-docspopular_priority {
            width: 80px;
            text-align: center;
        }
        
        /* Highlight on hover */
        tr:hover .docspopular-priority-display {
            background: #e0efff;
            border-color: #2271b1;
        }
    </style>
    <?php
}
add_action( 'admin_footer', 'docspopular_enqueue_admin_scripts' );

/**
 * Add quick edit field for priority
 */
function docspopular_quick_edit_priority( $column_name, $post_type ) {
    if ( $post_type !== 'docspopular' || $column_name !== 'docspopular_priority' ) {
        return;
    }
    
    ?>
    <fieldset class="inline-edit-col-right">
        <div class="inline-edit-col">
            <label>
                <span class="title"><?php _e( 'Priority', 'docspopular' ); ?></span>
                <span class="input-text-wrap">
                    <input type="number" name="docspopular_priority" class="docspopular-priority-quick-edit" value="0" min="0" step="1">
                </span>
            </label>
            <span class="description"><?php _e( 'Lower numbers appear first (0, 1, 2...)', 'docspopular' ); ?></span>
        </div>
    </fieldset>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#the-list').on('click', '.editinline', function() {
            const postId = $(this).closest('tr').attr('id').replace('post-', '');
            const $row = $('#post-' + postId);
            const priority = $row.find('.docspopular-priority-display').text().trim() || 0;
            
            $('.docspopular-priority-quick-edit').val(priority);
        });
    });
    </script>
    <?php
}
add_action( 'quick_edit_custom_box', 'docspopular_quick_edit_priority', 10, 2 );

/**
 * Save quick edit priority
 */
function docspopular_save_quick_edit_priority( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    
    if ( isset( $_POST['docspopular_priority'] ) ) {
        $priority = intval( $_POST['docspopular_priority'] );
        update_post_meta( $post_id, '_docspopular_priority', $priority );
    }
}
add_action( 'save_post_docspopular', 'docspopular_save_quick_edit_priority', 20 );

