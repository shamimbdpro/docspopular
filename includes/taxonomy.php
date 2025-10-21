<?php
/**
 * Register Custom Taxonomy for Documentation Categories
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Documentation Category Taxonomy
 */
function docspopular_register_taxonomy() {
    $labels = array(
        'name'                       => _x( 'Doc Categories', 'Taxonomy General Name', 'docspopular' ),
        'singular_name'              => _x( 'Doc Category', 'Taxonomy Singular Name', 'docspopular' ),
        'menu_name'                  => __( 'Categories', 'docspopular' ),
        'all_items'                  => __( 'All Categories', 'docspopular' ),
        'parent_item'                => __( 'Parent Category', 'docspopular' ),
        'parent_item_colon'          => __( 'Parent Category:', 'docspopular' ),
        'new_item_name'              => __( 'New Category Name', 'docspopular' ),
        'add_new_item'               => __( 'Add New Category', 'docspopular' ),
        'edit_item'                  => __( 'Edit Category', 'docspopular' ),
        'update_item'                => __( 'Update Category', 'docspopular' ),
        'view_item'                  => __( 'View Category', 'docspopular' ),
        'separate_items_with_commas' => __( 'Separate categories with commas', 'docspopular' ),
        'add_or_remove_items'        => __( 'Add or remove categories', 'docspopular' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'docspopular' ),
        'popular_items'              => __( 'Popular Categories', 'docspopular' ),
        'search_items'               => __( 'Search Categories', 'docspopular' ),
        'not_found'                  => __( 'Not Found', 'docspopular' ),
        'no_terms'                   => __( 'No categories', 'docspopular' ),
        'items_list'                 => __( 'Categories list', 'docspopular' ),
        'items_list_navigation'      => __( 'Categories list navigation', 'docspopular' ),
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => true,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
        'show_in_rest'               => true,
        'rewrite'                    => array( 'slug' => 'docs' ),
    );

    register_taxonomy( 'docspopular_category', array( 'docspopular' ), $args );
}
add_action( 'init', 'docspopular_register_taxonomy', 0 );

