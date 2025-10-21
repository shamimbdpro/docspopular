<?php
/**
 * Register Custom Post Type for Documentation
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register Documentation Post Type
 */
function docspopular_register_post_type() {
    $labels = array(
        'name'                  => _x( 'Documentation', 'Post Type General Name', 'docspopular' ),
        'singular_name'         => _x( 'Doc', 'Post Type Singular Name', 'docspopular' ),
        'menu_name'             => __( 'DocsPopular', 'docspopular' ),
        'name_admin_bar'        => __( 'Documentation', 'docspopular' ),
        'archives'              => __( 'Doc Archives', 'docspopular' ),
        'attributes'            => __( 'Doc Attributes', 'docspopular' ),
        'parent_item_colon'     => __( 'Parent Doc:', 'docspopular' ),
        'all_items'             => __( 'All Docs', 'docspopular' ),
        'add_new_item'          => __( 'Add New Doc', 'docspopular' ),
        'add_new'               => __( 'Add New', 'docspopular' ),
        'new_item'              => __( 'New Doc', 'docspopular' ),
        'edit_item'             => __( 'Edit Doc', 'docspopular' ),
        'update_item'           => __( 'Update Doc', 'docspopular' ),
        'view_item'             => __( 'View Doc', 'docspopular' ),
        'view_items'            => __( 'View Docs', 'docspopular' ),
        'search_items'          => __( 'Search Doc', 'docspopular' ),
        'not_found'             => __( 'Not found', 'docspopular' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'docspopular' ),
        'featured_image'        => __( 'Featured Image', 'docspopular' ),
        'set_featured_image'    => __( 'Set featured image', 'docspopular' ),
        'remove_featured_image' => __( 'Remove featured image', 'docspopular' ),
        'use_featured_image'    => __( 'Use as featured image', 'docspopular' ),
        'insert_into_item'      => __( 'Insert into doc', 'docspopular' ),
        'uploaded_to_this_item' => __( 'Uploaded to this doc', 'docspopular' ),
        'items_list'            => __( 'Docs list', 'docspopular' ),
        'items_list_navigation' => __( 'Docs list navigation', 'docspopular' ),
        'filter_items_list'     => __( 'Filter docs list', 'docspopular' ),
    );

    $args = array(
        'label'                 => __( 'Documentation', 'docspopular' ),
        'description'           => __( 'Documentation pages', 'docspopular' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'thumbnail', 'revisions', 'page-attributes' ),
        'hierarchical'          => true,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-book-alt',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => array( 'slug' => 'doc' ),
    );

    register_post_type( 'docspopular', $args );
}
add_action( 'init', 'docspopular_register_post_type', 0 );

