<?php
/**
 * Plugin Name: Custom Product Plugin 
 * Plugin URI:  https://wordpress.org/plugins
 * Description: Enable custom product post type.
 * Version:     1.0.0
 * Author:      Bhavin Gediya
 */

// die (plugin_dir_path( __FILE__ ) . 'single-my_product.php');
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
        'label'               => __( 'my_product'),
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
    register_post_type( 'my_product', $args );
}

//create a custom taxonomy name it "type" for your posts
function crunchify_create_deals_custom_product_taxonomy() {
 
    $labels = array(
      'name' => _x( 'Colors', 'taxonomy general name' ),
      'singular_name' => _x( 'Color', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Colors' ),
      'all_items' => __( 'All Colors' ),
      'parent_item' => __( 'Parent Color' ),
      'parent_item_colon' => __( 'Parent Color:' ),
      'edit_item' => __( 'Edit Color' ), 
      'update_item' => __( 'Update Color' ),
      'add_new_item' => __( 'Add New Color' ),
      'new_item_name' => __( 'New Color Name' ),
      'menu_name' => __( 'Colors' ),
    ); 	
   
    register_taxonomy('colors',array('my_product'), 
        array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'color' ),
        )
    );

    $labels = array(
        'name' => _x( 'Categories', 'taxonomy general name' ),
        'singular_name' => _x( 'Categorie', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Categories' ),
        'all_items' => __( 'All Categories' ),
        'parent_item' => __( 'Parent Categorie' ),
        'parent_item_colon' => __( 'Parent Categorie:' ),
        'edit_item' => __( 'Edit Categorie' ), 
        'update_item' => __( 'Update Categorie' ),
        'add_new_item' => __( 'Add New Categorie' ),
        'new_item_name' => __( 'New Categorie Name' ),
        'menu_name' => __( 'Categories' ),
      );

    register_taxonomy('prod-category',array('my_product'), 
        array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'prod-category' ),
        )
    );
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

    // wp_enqueue_script( 'originaljquery',  plugin_dir_url(__FILE__).'js/jquery.js', array('jquery'), null, false );
    wp_enqueue_script( 'slick-js',  plugin_dir_url(__FILE__).'js/slick.min.js', array('jquery'), null, false );
    wp_enqueue_script( 'myuploadscript',  plugin_dir_url(__FILE__).'js/customjs.js', array('jquery'), null, false );
    wp_enqueue_style( 'slick-css',  plugin_dir_url(__FILE__).'css/slick.css', array(), null, 'all' );
    wp_enqueue_style( 'myproductcss',  plugin_dir_url(__FILE__).'css/custom_product.css', array(), null, 'all' );
}

add_action( 'admin_enqueue_scripts', 'misha_include_myuploadscript' );
add_action( 'wp_enqueue_scripts', 'misha_include_myuploadscript' );

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


function product_add_custom_box() {
    $screens = [ 'my_product', 'wporg_cpt' ];
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
        <?php
            $productpostid = get_post_meta( get_the_ID(), 'product_gallery_imgs',true);
            $newdata = unserialize($productpostid);
            // print_r($newdata);
            if(!empty($newdata[0])) {
                foreach ($newdata as $prodgallery){
                    echo '<img src="'.$prodgallery.'" class="customprodgallery"/>';
                    echo '<input type="hidden" name="productimggallery[]" value="'.$prodgallery.'"/>';
                }
            }
            
        ?>
        <!-- <input type="hidden" name="productimggallery[]" value= <?php echo get_the_ID(); ?>/> -->
        </div>

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
                    $('#imgpreview').append('<img src="'+imgname+'" class="customprodgallery"/>');
                    $('#imgpreview').append('<input type="hidden" name="productimggallery[]" value="'+imgname+'"/>');
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

function wporg_save_postdata( $post_id ) {
    $post = get_post($post_id);
    if (isset($_POST['productimggallery'])) {
        $productimggallery = $_POST['productimggallery'];
    }
    $productgalleryimgs = serialize($productimggallery);
    update_post_meta( $post_id, 'product_gallery_imgs', $productgalleryimgs );
    
}
add_action( 'save_post', 'wporg_save_postdata' );

function single_page_template($single_template) {
    global $post;

    if ($post->post_type == 'my_product') {
        $single_template = plugin_dir_path( __FILE__ ). '/single-my_product.php';
    }

    return $single_template;
}
add_filter( 'single_template', 'single_page_template' );