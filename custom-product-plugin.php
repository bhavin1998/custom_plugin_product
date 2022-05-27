<?php
/**
 * Plugin Name: Custom Product Plugin 
 * Plugin URI:  https://wordpress.org/plugins
 * Description: Enable custom product post type.
 * Version:     1.0.0
 * Author:      Bhavin Gediya
 */


    // include plugin_dir_path( __FILE__ ) . 'class_product_plugin_activate.php';
    add_action( 'init', 'custom_product_post_type', 0 );
    
    // Let us create Taxonomy for Custom Post Type
    add_action( 'init', 'crunchify_create_deals_custom_product_taxonomy', 0 );

// Creating a My Products Custom Post Type
function custom_product_post_type() {
    $labels = array(
        'name'                => __( 'My Products' ),
        'singular_name'       => __( 'My Product'),
        'menu_name'           => __( 'My Products'),
        'parent_item_colon'   => __( 'Parent My Product'),
        'all_items'           => __( 'All My Products'),
        'view_item'           => __( 'View My Product'),
        'add_new_item'        => __( 'Add New My Product'),
        'add_new'             => __( 'Add New'),
        'edit_item'           => __( 'Edit My Product'),
        'update_item'         => __( 'Update My Product'),
        'search_items'        => __( 'Search My Product'),
        'not_found'           => __( 'Not Found'),
        'not_found_in_trash'  => __( 'Not found in Trash')
    );
    $args = array(
        'label'               => __( 'my-product'),
        'description'         => __( 'Best Crunchify My Products'),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'revisions'),
        'public'              => true,
        'hierarchical'        => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'has_archive'         => true,
        'can_export'          => true,
        'exclude_from_search' => false,
            'yarpp_support'       => true,
        'taxonomies' 	      => array('post_tag'),
        'publicly_queryable'  => true,
        'capability_type'     => 'page'
    );
    register_post_type( 'my-product', $args );
}

//create a custom taxonomy name it "type" for your posts
function crunchify_create_deals_custom_product_taxonomy() {
 
    $labels = array(
      'name' => _x( 'Types', 'taxonomy general name' ),
      'singular_name' => _x( 'Type', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Types' ),
      'all_items' => __( 'All Types' ),
      'parent_item' => __( 'Parent Type' ),
      'parent_item_colon' => __( 'Parent Type:' ),
      'edit_item' => __( 'Edit Type' ), 
      'update_item' => __( 'Update Type' ),
      'add_new_item' => __( 'Add New Type' ),
      'new_item_name' => __( 'New Type Name' ),
      'menu_name' => __( 'Types' ),
    ); 	
   
    register_taxonomy('types',array('my-product'), array(
      'hierarchical' => true,
      'labels' => $labels,
      'show_ui' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'type' ),
    ));
}