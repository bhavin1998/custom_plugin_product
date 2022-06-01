<?php
//Template Name: Single My Product
get_header();

global $post;
?>
<h2><?php echo $post->post_title; ?></h2>
<?php

if (has_post_thumbnail( $post->ID ) ) {
        $feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
        ?>
        <div>
                <div class="main_product_image_contain">
                        <div>
                                <img src="<?php echo $feat_image ?>" class="main_product_img" alt="">
                        </div>
                </div>
                <div class="product_gallery_image">
                        <?php
                                $newmetadata = get_post_meta($post->ID);
                                $productgallery = $newmetadata['product_gallery_imgs'][0];
                                $new122 = unserialize($productgallery);
                                $newwww = unserialize($new122);
                                foreach ($newwww as $newgalleryimg){
                                        echo "<div>";
                                        echo '<img src="'.$newgalleryimg.'" class="customprodgallery"/>';
                                        echo "</div>";
                                } 
                        ?>
                </div>

                <div class="productcontent">
                        <p><?php echo "<p>".$post->post_content."</p>" ?></p>
                </div>
        </div>
        <?php
}
else{
        echo "No image found";
}
?>
<?php

$args = array('post_type' => 'my_product', 'posts_per_page' => 3);
$query = new WP_Query($args);
$terms = wp_get_post_terms($query->post->ID, array('colors', 'prod-category')); ?>
<?php foreach ($terms as $term) : ?>
        <p><?php echo $term->taxonomy; ?>: <?php echo $term->name; ?></p>
<?php endforeach; ?>

<?php



get_footer();
