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

function misha_include_myuploadscript() {
    /*
     * I recommend to add additional conditions just to not to load the scipts on each page
     * like:
     * if ( !in_array('post-new.php','post.php') ) return;
     */
    if ( ! did_action( 'wp_enqueue_media' ) ) {
        wp_enqueue_media();
    }

    wp_enqueue_script( 'myuploadscript',  plugin_dir_url(__FILE__).'js/customjs.js', array('jquery'), null, false );
}

add_action( 'admin_enqueue_scripts', 'misha_include_myuploadscript' );

function misha_image_uploader_field( $name, $value = '') {
    $image = ' button">Upload image';
    $image_size = 'full'; // it would be better to use thumbnail size here (150x150 or so)
    $display = 'none'; // display state ot the "Remove image" button

    if( $image_attributes = wp_get_attachment_image_src( $value, $image_size ) ) {

        // $image_attributes[0] - image URL
        // $image_attributes[1] - image width
        // $image_attributes[2] - image height

        $image = '"><img src="' . $image_attributes[0] . '" style="max-width:95%;display:block;" />';
        $display = 'inline-block';

    } 

    return '
    <div>
        <a href="#" class="misha_upload_image_button' . $image . '</a>
        <input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $value . '" />
        <a href="#" class="misha_remove_image_button" style="display:inline-block;display:' . $display . '">Remove image</a>
    </div>';
}

/*
 * Add a meta box
 */
// add_action( 'admin_menu', 'misha_meta_box_add' );

// function misha_meta_box_add() {
//     add_meta_box('mishadiv', // meta box ID
//         'More settings', // meta box title
//         'misha_print_box', // callback function that prints the meta box HTML 
//         'my-product', // post type where to add it
//         'normal', // priority
//         'high' ); // position
// }

// /*
//  * Meta Box HTML
//  */
// function misha_print_box( $post ) {
//     $meta_key = 'second_featured_img';
//     echo misha_image_uploader_field( $meta_key, get_post_meta($post->ID, $meta_key, true) );
// }

// /*
//  * Save Meta Box data
//  */
// add_action('save_post', 'misha_save');

// function misha_save( $post_id ) {
//     if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
//         return $post_id;

//     $meta_key = 'second_featured_img';
//     // $_POST[$meta_key] = '';

//     update_post_meta( $post_id, $meta_key, $_POST[$meta_key] );

//     // if you would like to attach the uploaded image to this post, uncomment the line:
//     // wp_update_post( array( 'ID' => $_POST[$meta_key], 'post_parent' => $post_id ) );

//     return $post_id;
// }

function product_add_custom_box() {
    $screens = [ 'post', 'wporg_cpt' ];
    foreach ( $screens as $screen ) {
        add_meta_box(
            'product_metabox_id',                 // Unique ID
            'Upload product gallery image',      // Box title
            'wporg_custom_box_html',  // Content callback, must be of type callable
            $screen                            // Post type
        );
    }
}
add_action( 'add_meta_boxes', 'product_add_custom_box' );

function wporg_custom_box_html( $post ) {
    ?>
        <!-- <input type="file" name="my_file_upload[]" id="my_file_upload[]" multiple="multiple"> -->
        <input type="button" class="button button-secondary upload-button" value="Upload Profile Picture" data-group="1">
        <br/>
        <div class="dspimgprev" id="imgpreview">

        </div>
        <!-- <input type="text" name="profile_picture1" id="profile-picture1" value="'.$picture1.'"> -->

        <script type="text/javascript">
            jQuery(document).ready( function($){

            var mediaUploader;

            $('.upload-button').on('click',function(e) {
                e.preventDefault();
                var buttonID = $(this).data('group');

                if( mediaUploader ){
                    mediaUploader.open();
                    return;
                }

            mediaUploader = wp.media.frames.file_frame =wp.media({
                title: 'Choose a Hotel Picture',
                button: {
                    text: 'Choose Picture'
                },
                multiple:true
            });

            mediaUploader.on('select', function(){
                attachment = mediaUploader.state().get('selection').toJSON();
                // console.log(attachment[0]['url']);
                jQuery.each(attachment,function(i){
                    var imgname = attachment[i]['url'];
                    $('#imgpreview').append('<img src="'+imgname+'"/>');
                    $('#imgpreview img').css({"width":"150px","height":"150px","margin-right":"10px"});
                    
                });
                $('#profile-picture'+buttonID).val(attachment.url);
                $('#profile-picture-preview'+buttonID).css('background-image','url(' + attachment.url + ')');
            });
            mediaUploader.open();
            }); });
        </script>
        <?php
}