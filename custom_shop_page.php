<?php
get_header();

$args = array(
        'post_type'=> 'my_product',
        'orderby'    => 'ID',
        'post_status' => 'publish',
        'order'    => 'DESC',
        'posts_per_page' => -1 // this will retrive all the post that is published 
    );
    $result = new WP_Query( $args );
    echo "12 <pre>";
    print_r($result);

get_footer();